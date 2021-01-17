<?php

namespace App\Http\Controllers\Web\Internal\Survey;

use App\Models\Client\Client;
use App\Models\Source\Source;
use App\Models\Survey\StudyType;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyTopic;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SurveyController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $surveys = Survey::all()->take(10);
        return view('internal.survey.index')
            ->with('surveys', $surveys);
    }

    public function datatable()
    {
        return Laratables::recordsOf(Survey::class);
    }

    public function editSurvey(Request $request, $id)
    {
        $survey = Survey::find($id);
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $survey_topics = SurveyTopic::all()->pluck('name', 'id')->toArray();
        $clients = Client::all()->pluck('name', 'id')->toArray();

        return view('internal.survey.edit.index')
            ->with('survey', $survey)
            ->with('clients', $clients)
            ->with('survey_topics', $survey_topics)
            ->with('study_types', $study_types);
    }

    public function postCreateSurvey(Request $request, $id)
    {
        /*TODO: manage nullable fields like client_name*/
    }

    public function fetchClientVars(Request $request)
    {
        /*TODO: add Cache here to make this request faster*/
        $client_id = $request->input('client_id', false);
        $client = Client::find($client_id);
        $client_vars = explode(',' , $client->cvars);

        return response()->json($client_vars);
    }

    public function editSurveyRespondents(Request $request, $id)
    {
        $survey = Survey::find($id);
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $survey_topics = SurveyTopic::all()->pluck('name', 'id')->toArray();
        $clients = Client::all()->pluck('name', 'id')->toArray();

        return view('internal.survey.edit.respondents')
            ->with('survey', $survey)
            ->with('clients', $clients)
            ->with('survey_topics', $survey_topics)
            ->with('study_types', $study_types);
    }

    public function editSurveyVendors(Request $request, $id)
    {
        $survey = Survey::find($id);
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $survey_topics = SurveyTopic::all()->pluck('name', 'id')->toArray();
        $sources = Source::all()->pluck('name', 'id')->toArray();

        return view('internal.survey.edit.vendors')
            ->with('survey', $survey)
            ->with('sources', $sources)
            ->with('survey_topics', $survey_topics)
            ->with('study_types', $study_types);
    }
}
