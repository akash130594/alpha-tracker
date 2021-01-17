<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Project\Project;
use App\Repositories\Internal\General\GeneralRepository;
use App\Repositories\Internal\MasterQuestion\ProfileQuestionsRepository;
use App\Repositories\Internal\Project\ProjectRepository;
use App\Repositories\Internal\MasterQuestion\GlobalQuestionsRepository;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client as GClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Sirprize\PostalCodeValidator\Validator;

class ProjectQuotaController extends Controller
{
    protected $api_url = 'http://sjpanel-v2.local/api/';

    public $project_repo, $survey_repo, $globalQuesRepo, $profileQuesRepo;
    public function __construct(
        ProjectRepository $project_repository,
        GeneralRepository $surveyRepo,
        GlobalQuestionsRepository $globalQuesRepo,
        ProfileQuestionsRepository $profileQuesRepo
    )
    {
        $this->project_repo = $project_repository;
        $this->survey_repo = $surveyRepo;
        $this->globalQuesRepo = $globalQuesRepo;
        $this->profileQuesRepo = $profileQuesRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }

    public function editProjectRespondents(Request $request, $id)
    {
        $project = Project::find($id);
        //dd($project->country_code, $project->language_code);
        //$profileQuestions = $this->getProfileQuestions($country_code, $language_code);
        $projectQuota = $this->project_repo->getProjectQuota($id);
        $globalQuestions = $this->globalQuesRepo->getGlobalQuestionsByLocale($project->country_code, $project->language_code);
        $profileQuestions = $this->profileQuesRepo->getProfileQuestionsByLocale($project->country_code, $project->language_code);
        $profileQuestions = $profileQuestions->groupBy('profile_section_code');
        //dd($profileQuestions->first());

        return view('internal.project.edit.quota')
            ->with('project', $project)
            ->with('profileQuestions', $profileQuestions)
            ->with('globalQuestions', $globalQuestions)
            ->with('quotaData', $projectQuota);
    }

    public function updateProjectRespondents(Request $request, $id)
    {
        $formdata = $request->except('_token', '_method');
        $project = Project::find($id);
        $this->project_repo->createProjectQuotas($project, $formdata);
        return redirect()->route('internal.project.edit.respondent.show', [$id]);
    }

    public function getProfileQuestions($country_code, $language_code)
    {

        Cache::flush();
        $profileQuestions = array();
        return Cache::remember('view.survey.quota.partial.profilequestions.'.$country_code, 600,
            function () use ($country_code, $language_code, $profileQuestions) {
                $client = new GClient(['base_uri' => $this->api_url]);

                $queryString = [
                    'country_code' => $country_code,
                    'language_code' => $language_code,
                ];

                try{
                    $response = $client->request('GET', 'project/profile-detail',[
                        'query' => $queryString,
                        $this->getApiHeaders(),
                    ]);
                } catch(ServerException $e) {
                    dd($e->getResponse()->getBody()->getContents());
                }

                if( $response->getStatusCode() == 200 ){
                    $profileQuestions = json_decode($response->getBody()->getContents());
                    //dd($profileQuestions);

                    // json_last_error();
                }
                return $profileQuestions;
            });
    }

    public function getApiHeaders()
    {
        return array(
            'headers' => [
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'X-Foo'      => ['Bar', 'Baz']
            ]
        );
    }

    public function verifyPostcodes(Request $request)
    {
        $postCodes = $request->post_codes;
        $project_id = $request->project_id;
        $project = $this->project_repo->getProjectDetails($project_id);
        $country_code = $project->country_code;
        $postCodes = explode(',', $postCodes);
        $validator = new Validator();
        $json_data = [];
        $count_of_total_postcode = count($postCodes);

        $valid = [];
        $invalid = [];
        foreach ($postCodes as $code){
            $validate = $validator->isValid($country_code,$code );
            if($validate){
                $valid[] = $code;
            }else{
                $invalid[] = $code;
            }
        }
             $count_valid = count($valid);
            $count_invalid = count($invalid);
            $json_data['total'] = $count_of_total_postcode;
            $json_data['valid'] = [
                'count' => $count_valid,
                'data' => implode(',',$valid)
            ];
        $json_data['invalid'] = [
            'count' => $count_invalid,
            'data' => implode(',',$invalid)
        ];
       return response()->json($json_data);
    }

    public function editQuotaStatus(Request $request)
    {
        $quota_id = $request->project_quota_id;
        $change_status = $this->project_repo->changeStatusQuota($quota_id);
        if($change_status){
            return \Redirect::back()
                ->withFlashSuccess("Quota Disabled");
        } else{
            return \Redirect::back()
                ->withDanger("Quota cannot be disabled");
        }
    }
}
