<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Client\Client;
use App\Models\Project\Project;
use App\Models\Project\ProjectCustomScreener;
use App\Models\Project\ProjectTopic;
use App\Models\Project\StudyType;
use App\Models\Source\Source;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectSecurityScreenerController extends Controller
{
    public function index(Request $request, $id)
    {
        $project = Project::find($id);
        $projectClient = Client::where('id', '=', $project->client_id)->with('securityImpl')->first();
        $projectCustomScreener = ProjectCustomScreener::where('project_id', '=', $id)->first();

        $screenerData = [];
        if ($projectCustomScreener) {
            $screenerData = json_decode($projectCustomScreener->screener_json, true);
        }

        return view('internal.project.edit.security_screener')
            ->with('project', $project)
            ->with('projectClient', $projectClient)
            ->with('custom_screener', $screenerData);
    }

    public function updateSecurityScreener(Request $request, $id)
    {
        $project = Project::find($id);
        $projectData = $request->input('project', false);
        if ($projectData) {
            $project->loi_validation = $projectData['loi_validation'];
            if (!empty($projectData['loi_validation_time'])) {
                $project->loi_validation_time = $projectData['loi_validation_time'];
            }
        }

        $clientScreenerData = $request->input('client', false);
        if ($clientScreenerData) {
            $project->client_screener_redirect_flag = !empty($clientScreenerData['redirect_flag']);
            if (!empty($clientScreenerData['redirect_flag']) && !empty($clientScreenerData['parameter'])) {
                $parameters = json_encode($clientScreenerData['parameter']);
                $project->client_screener_redirect_data = $parameters;
            }
        }
        $project->save();
        $customScreenerData = $request->input('custom', false);
        if($customScreenerData){
            $encoded_data = json_encode($customScreenerData);
            ProjectCustomScreener::updateOrCreate(
                ['project_id' => $id],
                ['project_id' => $id, 'screener_json' => $encoded_data]
            );
        }
        return redirect()->back()->withFlashSuccess('Details Updated');
    }

    public function showCustomScreenerPreview(Request $request)
    {
        $input = $request->input('params');
        return view("internal.project.includes.security_screener.screener_preview")
            ->with('screener_data', $input)
            ->render();

    }

    public function runCustomScreenerPreview(Request $request)
    {
        $input = $request->except('token');
        $postedData = array();
        parse_str($input['serialize_output'], $postedData);
        $customScreenerData = $postedData['custom'];

        $indexData = array_values($customScreenerData);

        $firstElement = $indexData[0];
        return view("internal.project.includes.security_screener.screener_run")
            ->with('screener_data', json_encode($indexData) )
            ->with('current_question', $firstElement)
            ->with('current_question_json', json_encode($firstElement))
            ->render();
    }

    function fetchNextCustomScreenerPreview(Request $request)
    {
        $current = $request->input('current_data');

        $allData = $request->input('screenerdata');
        $currentAnswer = $request->input('answer');

        $currentQuestion = json_decode($current, true);
        $allDecodedData = json_decode($allData, true);

        $currentQuestionName = $currentQuestion['name'];
        $currentIndex = array_search($currentQuestionName, array_column($allDecodedData, 'name'));

        $priority = ['screen_out', 'screen_in', 'skip_to', 'default_action'];

        if(empty($currentAnswer)){
            $selectedAnswer = ['action'=>'default_action'];
        }else{
            if($currentQuestion['type'] == 'message'){
                $selectedAnswer = reset($currentQuestion['answer']);
            }else if($currentQuestion['type'] == 'multiple'){
                $allAnswers = array_diff(array_combine(array_keys($currentQuestion['answer']), array_column($currentQuestion['answer'], 'action')), [null]);
                $differenceAnswers = array_intersect_key($allAnswers,$currentAnswer);

                uasort($differenceAnswers, function ($a, $b) use ($priority) {
                    $aOrder = array_search($a, $priority);
                    $bOrder = array_search($b, $priority);
                    if ($aOrder == $bOrder) return 0;
                    return ($aOrder > $bOrder) ? 1 : -1;
                });
                $selectedAnswerIndex = array_key_first($differenceAnswers);
                $selectedAnswer = $currentQuestion['answer'][$selectedAnswerIndex];
            }else{
                $selectedAnswer = isset($currentQuestion['answer'][$currentAnswer])?$currentQuestion['answer'][$currentAnswer]:['action'=>'default_action'];
            }
        }


        $selectedAnswerAction = $selectedAnswer['action'];

        $nextQuestion = $this->getNextQuestion($currentIndex, $allDecodedData, $selectedAnswer);

        if ($selectedAnswerAction == 'default_action' || $selectedAnswerAction == 'skip_action' ) {

            if(empty($nextQuestion) && $selectedAnswerAction == 'default_action')
            {
                return view("internal.project.includes.security_screener.custom_screener.screen_complete");
            }

            $currentQuestion = $nextQuestion;
            return view("internal.project.includes.security_screener.screener_single_question")
                ->with('screener_data', json_encode($allDecodedData))
                ->with('current_question', $currentQuestion)
                ->with('current_question_json', json_encode($currentQuestion))
                ->render();
        }else if ( $selectedAnswerAction == 'screen_out' ) {
            return view("internal.project.includes.security_screener.custom_screener.screen_out")
                ->render();
        }else if ( $selectedAnswerAction == 'screen_in' ) {
            return view("internal.project.includes.security_screener.custom_screener.screen_in")
                ->render();
        }


    }

    private function getNextQuestion($currentIndex, $screenerData, $selectedAnswer)
    {
        if ($selectedAnswer['action'] == 'default_action') {
            return $this->get_next($screenerData, $currentIndex);
        }else if($selectedAnswer['action'] == 'skip_action'){
            $skipQuestion = $selectedAnswer['skip_to'];
            $nextIndex = array_search($skipQuestion, array_column($screenerData, 'name'));
            return $screenerData[$nextIndex];
        }else if($selectedAnswer['action'] == 'screen_out'){
            return false;
        }else if($selectedAnswer['action'] == 'screen_in'){
            return false;
        }
    }

    private function get_next($array, $key) {
        $currentKey = key($array);
        while ($currentKey !== null && $currentKey != $key) {
            next($array);
            $currentKey = key($array);
        }
        return next($array);
    }
}
