<?php

namespace App\Http\Controllers\Web\Internal\General;

use App\Models\General\Country;
use App\Models\General\Language;
use App\Models\Project\StudyType;
use App\Models\Project\ProjectTopic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\Repositories\Internal\General\GeneralRepository;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\Internal\General\Country\CreateRequest;
use App\Http\Requests\Internal\General\StudyType\StudyCreateRequest;
use App\Http\Requests\Internal\General\Language\CreateLanguageRequest;
use App\Http\Requests\Internal\General\SurveyTopic\CreateSurveyTopicRequest;
use App\Http\Requests\Internal\General\Country\EditRequest;
use App\Http\Requests\Internal\General\Language\EditLanguageRequest;

/**
 * This class is used for updating, creating and delete countries, languages, survey topic, study type..
 *
 * Class GeneralController
 * @author Pankaj Jha
 * @author Akash Sharma
 * @access public
 * @package  App\Http\Controllers\Web\Internal\General\GeneralController
 */
class GeneralController extends Controller
{
    /**
     * @var GeneralRepository
     * @param $general_repo
     */
    public $general_repo;

    /**
     * GeneralController constructor.
     * @param GeneralRepository $generalRepo
     */
    public function __construct(GeneralRepository $generalRepo)
    {
        $this->general_repo = $generalRepo;
    }

    /**
     * This function is used for displaying all the countries data and redirecting the user to the view of Country
     *
     * @access public
     * @return resource country/index.blade.php
     */
    public function index()
    {
        return View('internal.general.country.index');
    }


    /**
     * This function helps in query on country models to get all the data required to show in datatable.
     *
     * @access public
     * @return array
     */
    public function datatable()
    {
        return Laratables::recordsOf(Country::class);
    }

    /**
     * This function used to redirect the user to edit country view page.
     *
     * @access public
     * @param Request $request
     * @return resource country/edit.blade.php
     */
    public function editCountry(Request $request)
    {
        $id = $request->id;
        $country = $this->general_repo->findId($id);
        $language = explode(",", $country['language']);
        $get_lang_name = [];
            for($i=0;$i<count($language);$i++){
                $get_lang_name[] = $language[$i];
            }
            $name = $this->general_repo->getLanguage($get_lang_name);

        $lang_details = $this->general_repo->langDetails()->pluck('name', 'code')->toArray();

        //dd($lang_details);

        return View('internal.general.country.edit')
            ->with('id',$id)
            ->with('country', $country)
            ->with('language_name',$name)
            ->with('languages',$lang_details);
    }

    /**
     * This function is used for posting the edited country nd saving the details.
     *
     * @access public
     * @param EditRequest $request
     * @return mixed
     */
    public function postCountry(EditRequest $request)
    {
        $id = $request->id;
        $input = $request->except(['_token','language']);
        $language = $request->input('language',false);
        $lang_code = implode(",",$language );
        $country_code = $request->input('country_code',false);
        $name = $request->input('name',false);
        $currency_code =  $request->input('currency_code',false);

        $data = [
        'country_code' => $country_code,
            'name' => $name,
            'currency_code' => $currency_code,
            'language' => $lang_code,
            ];
        $status = $this->general_repo->updateCountry($id, $data);
        if($status){
            return Redirect::back()
                ->withFlashSuccess("country updated");
        } else{
            return Redirect::back()
                ->withFlashError("country not updated");
        }
    }

    /**
     * This function is used to redirect the user to create form view page.
     *
     * @access public
     * @return resource country/create.blade.php
     */
    public function createCountry()
    {
        $lang_details = $this->general_repo->langDetails()->pluck('name', 'code')->toArray();
        return View('internal.general.country.create')
            ->with('languages',$lang_details);
    }

    /**
     * This function is used for posting the create country data and saving it.
     *
     * @access public
     * @param CreateRequest $request
     * @return mixed
     */
    public function postCreateCountry(CreateRequest $request)
    {
       $input = $request->except(['_token','language']);
        $lang_details = $request->input('language',false);
        $lang_code = implode(",",$lang_details);
        $status = $this->general_repo->createCountry($input,$lang_code);
        if($status){
            return Redirect::back()
                ->withFlashSuccess("Country Created");
        } else{
            return Redirect::back()
                ->withFlashError("Something wrong");
        }
    }

    /**
     * This function us used to delete Country Data.
     *
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function deleteCountry(Request $request, $id)
    {
        $status = $this->general_repo->deleteCountry($id);
        return redirect()->route('internal.general.country.index')
           ->withFlashSuccess("deleted");
    }

    /**
     * This function is used to get all the languages and redirecting the user to all Languages view.
     *
     * @return resource language/index.blade.php
     */
    public function languageDetails()
    {
        return View('internal.general.language.index');
    }

    /**
     * This function helps in query on language model to get all the data required to show in datatable.
     *
     * @access public
     * @return array
     */
   public function datatableLanguage()
   {
       return Laratables::recordsOf(Language::class);
   }

    /**
     * This function is used is used for redirecting the user to edit language view.
     *
     * @access public
     * @param Request $request
     * @return resource language/edit.blade.php
     */
    public function editLanguage(Request $request)
    {
        $id = $request->id;
        $get_lang = $this->general_repo->getLanguageDetails($id);

        return view('internal.general.language.edit')
            ->with('language', $get_lang);

    }

    /**
     * This function is used for posting the edited language and saving it.
     *
     * @access public
     * @param EditLanguageRequest $request
     * @return mixed
     */
    public function postLanguage(EditLanguageRequest $request)
    {
        $id = $request->id;
        $input = $request->except(['_token']);
        $update_lang = $this->general_repo->updateLang($id,$input);
        if($update_lang){
            return redirect()->route('internal.general.language.index')
                ->withFlashSuccess("Language updated");
        } else{
            return redirect()->route('internal.general.language.index')
                ->withFlashError("country not updated");
        }
    }

    /**This function is used for creating new language and redirecting it to view of create form.
     *
     * @access public
     * @return resource language/create.blade.php
     */
    public function createLanguage()
    {
        return view('internal.general.language.create');
    }

    /**
     * This function is used to post the created language and saving it.
     *
     * @access public
     * @param CreateLanguageRequest $request
     * @return mixed
     */
    public function postCreateLanguage(CreateLanguageRequest $request)
    {

        $input = $request->except(['_token']);
        $create_lang = $this->general_repo->createLang($input);
        if($create_lang){
            return redirect()->route('internal.general.language.index')
                ->withFlashSuccess("language created");
        } else{
            return redirect()->route('internal.general.language.index')
                ->withFlashError("language not created");
        }
    }

    /**
     * This function is used to delete particular language.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function deleteLanguage(Request $request)
    {
        $id = $request->id;
        $status = Language::where('id','=',$id)
            ->delete();
        if($status){
            return redirect()->route('internal.general.language.index')
                ->withFlashSuccess("deleted");
        } else{
            return redirect()->route('internal.general.language.index')
                ->withFlashError("unable to delete");
        }
    }

    /**
     * This function is used for getting all the study details and redirecting the user to the all study type view.
     *
     * @access public.
     * @return resource study_types/index.blade.php
     */
    public function studyTypeDetails()
    {
        return view('internal.general.study_types.index');
    }

    /**
     * This function helps in query on study type models to get all the data required to show in datatable.
     * @return array
     */
    public function datatableStudyType()
    {
        return Laratables::recordsOf(StudyType::class);
    }

    /**
     * This function is used to redirect the user to edit study type.
     *
     * @access public
     * @param Request $request
     * @return resource study_types/edit.blade.php
     */
    public function editStudyType(Request $request)
    {
        $id = $request->id;
        $study_type_details = $this->general_repo->getStudyType($id);

        return view('internal.general.study_types.edit')
            ->with('study_types',$study_type_details);
    }

    /**
     * This function is used for posting the edited study type data and saving it.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function postStudyType(Request $request)
    {
        $id = $request->id;
        $input = $request->except(['_token']);
        $update_study_type = $this->general_repo->updateStudyTypes($id,$input);
        if($update_study_type){
            return Redirect::back()
                ->withFlashSuccess("study type updated");
        } else{
            return Redirect::back()
                ->withFlashError("Something Wrong");
        }
    }

    /**
     * This function is used for redirecting the user to create study type view page.
     *
     * @access public
     * @return resource study_types/create.blade.php
     */
    public function createStudyType()
    {
        return view('internal.general.study_types.create');
    }

    /**
     * This function is used for posting created study types.
     *
     * @access public
     * @param StudyCreateRequest $request
     * @return mixed
     */
    public function postCreateStudyType(StudyCreateRequest $request)
    {

        $input = $request->except(['_token']);
        $create_study_type = $this->general_repo->createStudyType($input);
        if($create_study_type){
            return redirect()->route('internal.general.study_type.index')
                ->withFlashSuccess("study type created");
        } else{
            return redirect()->route('internal.general.study_type.index')
                ->withFlashError("study type not created");
        }
    }

    /**
     * This function is used for deleting particular Study Type.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function deleteStudy(Request $request)
    {
        $id = $request->id;
        $status = StudyType::where('id','=',$id)
            ->delete();
        if($status){
            return redirect()->route('internal.general.study_type.index')
                ->withFlashSuccess("deleted");
        } else{
            return redirect()->route('internal.general.study_type.index')
                ->withFlashError("unable to delete");
        }
    }

    /**
     * This function is used for redirecting the user to all survey topic view
     *
     * @access public
     * @return resource survey_topic/index.blade.php
     */
    public function surveyTopicDetails()
    {
        return view('internal.general.survey_topics.index');
    }

    /**
     * This function helps in query on survey topic model to get all the data required to show in datatable.
     *
     * @return array
     */
    public function datatableSurveyTopic()
    {
        return Laratables::recordsOf(ProjectTopic::class);
    }

    /**
     * This function is used for redirecting to view of edit survey topic form.
     *
     * @access public
     * @param Request $request
     * @return resource survey_topic.edit.blade.php
     */
    public function editSurveyTopic(Request $request)
    {
        $id = $request->id;
        $survey_topic = $this->general_repo->getSurveyTopicDetails($id);
        return view('internal.general.survey_topics.edit')
            ->with('survey_topics',$survey_topic);
    }

    /**
     * This function is used for posting the edited survey topic data and saving it.
     *
     * @param Request $request
     * @return mixed
     */
    public function postSurveyTopic(Request $request)
    {
        $id = $request->id;
        $input =$request->except(['_token']);
        $update_topic = $this->general_repo->updateTopics($id,$input);
        if($update_topic){
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashSuccess("Topic updated");
        } else{
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashError("Topic not updated");
        }
    }

    /**
     * This function is used for redirecting the user to view of create Survey Topic.
     *
     * @access public
     * @return resource survey_topic/create.blade.php
     */
    public function createSurveyTopic()
    {
        return view('internal.general.survey_topics.create');
    }

    /**
     * This function is used for posting the created survey topic and saving it.
     *
     * @access public.
     * @param CreateSurveyTopicRequest $request
     * @return mixed
     *
     */
    public function postCreateSurveyTopic(CreateSurveyTopicRequest $request)
    {
        $input = $request->except(['_token']);
        $create_survey_topic = $this->general_repo->createSurveyTopic($input);
        if($create_survey_topic){
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashSuccess("Survey Topic created");
        } else{
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashError("Survey Topic not created");
        }
    }

    /**
     * This function is used for deleting particular survey topic.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function deleteSurveyTopic(Request $request)
    {
        $id = $request->id;
        $status = SurveyTopic::where('id','=',$id)
            ->delete();
        if($status){
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashSuccess("deleted");
        } else{
            return redirect()->route('internal.general.survey_topic.index')
                ->withFlashError("unable to delete");
        }
    }

}
