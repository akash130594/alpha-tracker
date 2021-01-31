<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Models\Archive\Archive;
use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Client\ClientSecurityImpl;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Models\Source\SourceType;
use App\Models\Project\Project;
use App\Models\Project\ProjectTopic;
use App\Models\General\Country;
use App\Models\General\Language;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use App\Repositories\Internal\Source\SourceRepository;
use App\Repositories\Internal\Traffic\TrafficRepository;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Internal\Project\ProjectRepository;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Repositories\Internal\General\GeneralRepository;
use App\Repositories\Internal\Archive\ArchivesRepository;
use MongoDB\BSON\UTCDateTime;
use File;
use App\Models\UniqueFile\UniqueFileData;
use App\Http\Requests\Internal\Project\CreateProjectRequest;
use App\Http\Requests\Internal\Project\EditProjectRequest;



class ProjectController extends Controller
{


    public $datatable_query;

    /**
     * @var ProjectRepository
     * @param $project_repo
     * @param $trafficRepo
     * @param $arch_repo
     * @param $projectSurveyRepo
     * @param $sourceRepo
     * @param $generalRepo
     */
    public $project_repo, $trafficRepo,$arch_repo, $projectSurveyRepo, $sourceRepo, $generalRepo;

    /**
     * ProjectController constructor.
     * @param ProjectRepository $project_repository
     * @param GeneralRepository $generalRepo
     * @param TrafficRepository $trafficRepo
     * @param ArchivesRepository $archRepo
     * @param ProjectSurveyRepository $projectSurveyRepo
     * @param SourceRepository $sourceRepo
     */
    public function __construct(
        ProjectRepository $project_repository,
        GeneralRepository $generalRepo,
        TrafficRepository $trafficRepo,
        ArchivesRepository $archRepo,
        ProjectSurveyRepository $projectSurveyRepo,
        SourceRepository $sourceRepo
    )
    {
        $this->project_repo = $project_repository;
        $this->arch_repo = $archRepo;
        $this->generalRepo = $generalRepo;
        $this->trafficRepo = $trafficRepo;
        $this->projectSurveyRepo = $projectSurveyRepo;
        $this->sourceRepo = $sourceRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }

    /**
     * This function is used to display all the project, redirect the user to all project view.
     *
     * @access public
     * @param Request $request
     * @return resource project/index.blade.php
     */
    public function index(Request $request)
    {
        $projects = Project::with('user')
            ->take(10)->get();
        $filter_elements = $this->generalRepo->getAllFilterableData();
        $trafficStats = $this->trafficRepo->getTrafficsStats($projects);
        //dd($trafficStats);
        $projects = $projects->map(function ($value) use ($trafficStats)  {
            $value->traffic = $trafficStats->first(function($item) use ($value) {
                return $item->id == $value->id;
            });
            return $value;
        });
        $countries = $filter_elements['countries'];
        $study_types = $filter_elements['study_types'];
        $project_statuses = $filter_elements['project_statuses'];
        $project_managers = $filter_elements['project_managers'];

        return view('internal.project.index')
            ->with('projects', $projects)
            ->with('countries', $countries)
            ->with('study_type', $study_types)
            ->with('status', $project_statuses)
            ->with('project_manager', $project_managers);
    }

    public function filterGetProjectBulk(Request $request)
    {
        $input  = $request->except(['_token']);
        $filter_data = $request->input('status',false);
        $filter_elements = $this->generalRepo->getAllFilterableData();

        $countries = $filter_elements['countries'];
        $study_types = $filter_elements['study_types'];
        $project_statuses = $filter_elements['project_statuses'];
        $project_managers = $filter_elements['project_managers'];


        $status = []; $country = []; $study_type = []; $project_manager = [];
        $filterColumns = [
            'status' => [],
            'country' => [],
            'study_type' => [],
            'project_manager' => [],
        ];

        if(!empty($filter_data)){
            foreach($filter_data as $item){
                list($key,$val) = explode(".",$item);
                $filterColumns[$key][] = $val;
            }
        }
        extract($filterColumns);
        $projects = $this->generalRepo->getProject($status,$country,$study_type,$project_manager);
        return view('internal.project.index')
            ->with('input',$input)
            ->with('projects', $projects)
            ->with('status_filter',$status)
            ->with('country_filter',$country)
            ->with('study_filter',$study_type)
            ->with('project_manager_filter',$project_manager)
            ->with('countries',$countries)
            ->with('study_type',$study_types)
            ->with('status',$project_statuses)
            ->with('project_manager',$project_managers)
            ->with('filter_current_data',$filter_data);

    }

    public function datatable(Request $request)
    {
        return Laratables::recordsOf(Project::class, function($query) use ($request) {
            $filter = $request->input('filter', false);
            if ($filter){
                $filterColumns = GeneralRepository::parseFilterQuery($filter);

                return $query->where(function($query) use ($filterColumns)
                {
                    if(!empty($filterColumns['status']))
                        $query->whereIn('status_id', $filterColumns['status']);

                    if( !empty($filterColumns['country']) )
                        $query->whereIn('country_code', $filterColumns['country']);

                    if( !empty($filterColumns['study_type']) )
                        $query->whereIn('study_type_id', $filterColumns['study_type']);

                    if( !empty($filterColumns['project_manager']) )
                        $query->whereIn('created_by', $filterColumns['project_manager']);
                });
            }else{
                return $query;
            }
        });
    }
/*------------------------------------Tested BY AS---------------------------------------------------------------------------------*/
    public function editProject(Request $request, $project_id)
    {
        $archive_filter=null;
        $data_filtered=null;
        $archive_filter = null;
        $dedupe_status = null;
        $dedupe_filter = null;

        $project = Project::find($project_id);
        $get_unique_link = UniqueFileData::where('project_id',(int)($project_id))->pluck('url_data')->first();
        $link = [];
        if($get_unique_link){
            foreach($get_unique_link as $key => $value){
                if($value['status']==false){
                    $link[] = $value['link'];
                }
            }
            $link = reset($link);
        }
        $country = Country::find($project->country_id);
        $language = Language::find($project->language_id);
        $user = User::find($project->created_by);
        if($project->survey_dedupe_flag==1){
            $dedupe= $this->project_repo->getDedupeData($project->survey_dedupe_list_id);
            $dedupe_filter = json_decode($dedupe->dedupe_selected_filter, true);
            $dedupe_status = $dedupe->dedupe_status;
        }
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $project_topics = ProjectTopic::all()->pluck('name', 'id')->toArray();
        $clients = Client::all()->pluck('name', 'id')->toArray();

        return view('internal.project.edit.index')
            ->with('project', $project)
            ->with('country', $country)
            ->with('language', $language)
            ->with('project_user', $user)
            ->with('clients', $clients)
            ->with('project_topics', $project_topics)
            ->with('study_types', $study_types)
            ->with('link',$link)
            ->with('archive_filter',$archive_filter)
            ->with('data_filtered',$data_filtered)
            ->with('dedupe_filter',$dedupe_filter)
            ->with('dedupe_status',$dedupe_status);
    }

    public function postEditProject(EditProjectRequest $request, $project_id)
    {
        $postData = $request->except('_token', '_method');
        $unique_file_status = $request->hasFile('unique_ids_file');
        $unique_links_file = null;
        if ( !empty( $unique_file_status) ) {
            $unique_links_file = strtotime('now').'_'.str_random(10).'.csv';
            $links_file = $request->file('unique_ids_file');
            $uploadPath = setting('unique_folder_name').DIRECTORY_SEPARATOR;
            $links_file->move( $uploadPath, $unique_links_file);
            $postData['unique_ids_file'] = $unique_links_file;
        }
        $update_status = $this->project_repo->updateProject($project_id, $postData);
        if ($update_status === true) {
            $project = Project::where('id','=',$project_id)->first();
            if ($unique_links_file){
                $this->saveUniqueLinks($project,$unique_links_file);
            }
            return redirect()->back()->withFlashSuccess('Project Updated');
        }else{
            return redirect()->back();
        }
    }
/*----------------------------------Tested BY AS------------------------------------------------------------------------------------------*/
    public function createProject()
    {
        $study_types = StudyType::all()->pluck('name', 'id')->toArray();
        $project_topics = ProjectTopic::all()->pluck('name', 'id')->toArray();
        $clients = Client::all()->pluck('name', 'id')->toArray();
        $countries = Country::all()->pluck('name', 'id')->toArray();
        $languages = Language::all()->pluck('name', 'id')->toArray();
        $current_user_id = Auth::user()->id;
        $current_user_name = Auth::user()->name;
        $projects = Project::all()->pluck('code','id')->toArray();
        $archives = Archive::all()->pluck('project_code','project_id')->toArray();
        $project_quota = setting('project_quota');
        return view('internal.project.create.index')
            ->with('clients', $clients)
            ->with('project_topics', $project_topics)
            ->with('study_types', $study_types)
            ->with('countries', $countries)
            ->with('languages', $languages)
            ->with('current_user_id', $current_user_id)
            ->with('current_user_name', $current_user_name)
            ->with('project_quota',$project_quota)
            ->with('projects',$projects)
            ->with('archives',$archives);
    }
/*--------------------------------Testing done by AS------------------------------------------------------------------------------------------*/
    public function postCreateProject(CreateProjectRequest $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $projectData = $request->except('_token');
        $unique_file_status = $request->hasFile('unique_ids_file');
        $unique_links_file = null;
        if ($unique_file_status) {
            $unique_links_file = strtotime('now').'_'.str_random(10).'.csv';
            $links_file = $request->file('unique_ids_file');
            $uploadPath = setting('unique_folder_name').DIRECTORY_SEPARATOR;
            $links_file->move( $uploadPath, $unique_links_file);
        }
        $client_id = $projectData['client_id'];
        $client_data = Client::find($client_id);

        $additionalData = [
            'unique_ids_file' => $unique_links_file,
            'label' => $projectData['name'],
            'client_name' => $client_data->name,
            'client_code' => $client_data->code,
            'start_date' => date("Y-m-d h:m:s"),
        ];

        /*Looking for UniqueParameter Check for the client*/
        if ($client_data->security_flag) {
            $securityData = ClientSecurityImpl::where('client_id', '=', $client_data->id)->first();
            if ($securityData->security_type_code == 'UNIQURL') {
                $decoded = json_decode($securityData->method_data, true);
                $globalParam = false;
                if ( $decoded && !empty($decoded['param_name'])  ) {
                    $globalParam = $decoded['param_name'];
                }
                $completeParam = ($globalParam)?$globalParam:str_random(6);
                $terminateParam = ($globalParam)?$globalParam:str_random(6);
                $quotafullParam = ($globalParam)?$globalParam:str_random(6);
                $qualityParam = ($globalParam)?$globalParam:str_random(6);
                $unique_parameterData = [
                    'complete' => [
                        $completeParam => str_random(8),
                    ],
                    'terminate' => [
                        $terminateParam => str_random(8),
                    ],
                    'quotafull' => [
                        $quotafullParam => str_random(8),
                    ],
                    'quality' => [
                        $qualityParam => str_random(8),
                    ],
                ];
                $additionalData['unique_parameters'] = json_encode($unique_parameterData);

            }
        }

        $create_data = array_merge( $projectData, $additionalData);
        $project = $this->project_repo->createProject($create_data);
        if($project){
            $this->createInternalSourceSurvey($project);
            if ($unique_links_file) {
                /*Todo: Add this Function in Queue using Events*/
                $this->saveUniqueLinks($project,$unique_links_file);
            }
        }
        return redirect()->route('internal.project.edit.show', [$project->id]);
    }

    private function createInternalSourceSurvey($project)
    {
        $internalSource = $this->sourceRepo->getInternalSource();
        $internalProjectVendor = $this->projectSurveyRepo->getProjectVendorByProjectId($project->id, $internalSource->code);
        if ( empty($internalProjectVendor) ) {

            //$all_quotas = $this->project_repo->getProjectQuota($project->id)->pluck('name', 'id');
            $createData = [
                'project_id' => $project->id,
                'project_code' => $project->code,
                'vendor_id' => $internalSource->id,
                'vendor_code' => $internalSource->code,
                //'spec_quota_ids' => $all_quotas->keys()->implode(','),
                //'spec_quota_names' => $all_quotas->implode(','),
                'cpi' => $project->cpi,
                'quota' => $project->quota,
            ];
            $internalProjectVendor = ProjectVendor::create($createData);
        }
        $this->projectSurveyRepo->createSurveyForProjectVendor($project, $internalProjectVendor);
    }

    private function saveUniqueLinks($project,$name)
    {
        $link_file = fopen(public_path(setting('unique_folder_name')).'/'.$name,'r');
        $row = [];
        while (($line = fgetcsv($link_file)) !== FALSE) {
            $row[] = $line[0];
        }
        $unqiue_link = [];
        $unqiue_link['project_code'] = $project->code;
        $unqiue_link['project_id'] = $project->id;
        foreach ($row as $key=>$value){
            $unqiue_link['url_data'][] = [
                'link' => $value,
                'status' => false,
                'id' => $key,
            ];
        }
        $create_unique_data = $this->project_repo->createUniqueData($unqiue_link);
        if($create_unique_data){
            return true;
        }else{
            return false;
        }
    }
    public function fetchClientVars(Request $request)
    {
        /*TODO: add Cache here to make this request faster*/
        $client_id = $request->input('client_id', false);
        $client = Client::find($client_id);
        $client_vars = explode(',' , $client->cvars);

        return response()->json($client_vars);
    }

    public function fetchLanguagesByCountry(Request $request)
    {
        $country_id = $request->input('country_id');
        $languages = Cache::remember('country.'.$country_id.'.language', 20, function () use ($country_id) {
            return $this->generalRepo->getLanguagesByCountryId($country_id);
        });
        return response()->json($languages);
    }

    public function editProjectRespondents(Request $request, $id)
    {
        $project = Project::find($id);
        $country_code = $project->country_code;
        $language_code = $project->language_code;
        $profileQuestions = $this->getProfileQuestions($country_code, $language_code);
        $projectQuota = $this->project_repo->getProjectQuota($id);


        return view('internal.project.edit.respondents')
            ->with('project', $project)
            ->with('profileQuestions', $profileQuestions)
            ->with('quotaData', $projectQuota);
    }

    public function getProfileQuestions($country_code, $language_code)
    {

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
/*---------------------------------------------Tested By AS------------------------------------------------------------------------------*/
    public function cloneProject(Request $request)
    {
        $project_id = $request->id;
        $data = [];
        $get_project_details = $this->generalRepo->getProjectDetails($project_id);
        $collection = collect($get_project_details);
        $project_details = $collection->except(['code','status_id','status_label','id']);
        foreach ($project_details as $key => $value){
            $data[$key] = $value;
        }
        $status_data = [
            'status_id' => '1',
            'status_label' => 'TBD',
            'code' => $this->project_repo->generateNewProjectCode($project_details),
        ];
        $insert_data = array_merge($data,$status_data);
        $insert_data['name'] = $insert_data['name'].' - CLONE';
        $insert_data['label'] = $insert_data['label'].' - CLONE';
        $create_project_clone = $this->generalRepo->createCloneProject($insert_data);
        if( $create_project_clone ) {
            $vendor_data = [];
            $quota_id = [];
            $get_vendors_detail = $this->generalRepo->getVendorDetails($project_id);
            $get_project_quota = $this->generalRepo->getQuotaDetails($project_id);
            $get_project_custom_screener = $this->project_repo->getProjectCustomScreener($project_id);
            if ($get_vendors_detail) {
                $clone_vendor_details = false;
                foreach ($get_vendors_detail as $key => $value) {
                    $collection_vendor = collect($value);
                    $vendor_data[] = $collection_vendor['id'];/*(Collecting all the vendor id to be searched in project_survey table in next if())*/
                    $received_vendor =  $collection_vendor->except(['id','project_code','project_id'])->toArray();/*getting vendor details except its id,project_code,project_id*/
                    $get_new_project_code_cloned = $this->project_repo->getNewProjectCode($create_project_clone->id);
                    $project_detail = ['project_id' => $create_project_clone->id,'project_code' => $get_new_project_code_cloned];
                    $clone_vendor_data = array_merge($project_detail,$received_vendor);
                    $clone_vendor_details[] = $this->generalRepo->createCloneVendor($clone_vendor_data);
                }
                $get_clone_project_vendor = $this->project_repo->getCloneProjectVendor($clone_vendor_details);
                $internalSource = $this->sourceRepo->getInternalSource();
                foreach ($get_clone_project_vendor as $key => $value){
                    if($value['vendor_code'] == $internalSource->code){
                        $this->createInternalSourceSurvey($create_project_clone);
                    }
                }
            }
            if($get_project_custom_screener){
                foreach ($get_project_custom_screener as $custom_screener){
                    $custom_screener['project_id'] = $create_project_clone->id;
                    unset($custom_screener['id']);
                    $this->project_repo->createProjectCustomScreener($custom_screener);
                }
            }
            if ($get_project_quota) {
                foreach ($get_project_quota as $key => $value) {
                    $collection_quota = collect($value);
                    $quota_id[] = $collection_quota['id'];
                    $project_id = ['project_id' => $create_project_clone->id];
                    $received_quota = $collection_quota->except(['id','project_id'])->toArray();
                    $clone_quota_data = array_merge($project_id, $received_quota);
                    $clone_project_quota = $this->generalRepo->createCloneQuota($clone_quota_data);
                }
                if ($clone_project_quota) {
                    $get_project_quota_specs = $this->generalRepo->getQuotaSpecsDetails($quota_id);
                    if ($get_project_quota_specs) {
                        foreach ($get_project_quota_specs as $key => $value) {
                            $collection_quota_specs = collect($value);
                            $received_quota_specs = $collection_quota_specs->except(['id'])->toArray();
                            $this->generalRepo->cloneQuotaSpecs($received_quota_specs);
                        }
                    }
                }
            }
            return redirect()->route('internal.project.edit.show',[$create_project_clone->id])
                ->withFlashSuccess("Cloning Done for Project Id -> ".$request->id);
        } else {
            return Redirect::back()
                ->withErrors("Vendor Has Not Been Assigned for the Project so cloning is not possible for Project's Vendor");
        }
    }

    public function getStatusFlow(Request $request)
    {
        $project_id = $request->input('project_id',false);
        $project_status = $request->input('project_status',false);
        $get_status_flow = ProjectStatus::where('id', '=', $project_status)->first();
        if ($get_status_flow) {
            $flow_status = explode(',',$get_status_flow->next_status_flow);
            $get_status = ProjectStatus::whereIn('code',$flow_status)->pluck('name', 'id')->toArray();
            return response()->json($get_status, 200);
        }
        return response()->json([])->setStatusCode(404);
    }
/*---------------------------------------Tested by AS--------------------------------------------------------------------------------------*/
     public function trafficExport(Request $request)
     {
        $project_id = $request->id;
        $get_traffic_details = $this->trafficRepo->getTrafficDetails($project_id);
        $get_project_details = $this->project_repo->getProjectDetails($project_id);
        if($get_traffic_details){

            $fileHeaders = [
                'RespID',
                'Status',
                'RespStatus',
                'Mode',
                'ProjectCode',
                'ProjectName',
                'ClientName',
                'CPI',
                'StartDateTime',
                'EndDateTime',
                'Duration(mins)',
                'vVarParams',
                'vVarValues',
                'SourceType',
                'SourceCode',
                'SourceName',
                'SourceLink',
                'ClientLink',
                'EndPageByClient',
                'EndPageToVendor',
                'SurveyID',
                'SurveyCode',
                'SurveyTopic',
                'SurveyCountry'
            ];

            $fileName = $get_project_details->code.".csv";
            $handle = fopen($fileName,'w+');
            fputcsv($handle, $fileHeaders );

            $allCountries = Country::select('id', 'name')->get();
            $allTopics = ProjectTopic::select('id', 'name')->get();
            $allSourceTypes = SourceType::select('id', 'name')->get();

            $finalArray = [];
            foreach ($get_traffic_details as $traffic){

                $country_name = $allCountries->where( 'id', $traffic->country_id )->first()->name;
                $topic_name = $allTopics->where( 'id', $traffic->project_topic_id )->first()->name;
                $source_type_name = $allSourceTypes->where( 'id', $traffic->source_type_id )->first()->name;

                $start_date = $traffic->started_at;
                if (is_string($start_date)) {
                    $start_date = new UTCDateTime( strtotime($start_date) * 1000 );
                }
                $startedDateTime = $start_date->toDateTime()->format('Y-m-d H:i:s');

                $endedDateTime = '';
                $duration = '';
                if ($traffic->ended_at) {
                    $end_date = $traffic->ended_at;
                    if (is_string($start_date)) {
                        $end_date = new UTCDateTime(strtotime($end_date) * 1000);
                    }
                    $endedDateTime = $end_date->toDateTime()->format('Y-m-d H:i:s');
                    $duration = $traffic->duration;
                }

                $exportItem = [
                    'RespID' => $traffic->id,
                    'Status' => $traffic->status_name,
                    'RespStatus' => $traffic->resp_status_name,
                    'Mode' => ( $traffic->mode == 1 )?"LIVE":"TEST",
                    'ProjectCode' => $traffic->project_code,
                    'ProjectName' => $traffic->project_name,
                    'ClientName' => $traffic->client_name,
                    'CPI' => $traffic->cpi,
                    'StartDateTime' => $startedDateTime,
                    'EndDateTime' => $endedDateTime,
                    'Duration(mins)' => $duration,
                    'vVarParams' => implode('|', array_keys($traffic->vvars) ),
                    'vVarValues' => implode('|', array_values($traffic->vvars) ),
                    'SourceType' => $source_type_name,
                    'SourceCode' => $traffic->source_code,
                    'SourceName' => $traffic->source_name,
                    'SourceLink' => $traffic->vendorsourceurl,
                    'ClientLink' => $traffic->clientsourceurl,
                    'EndPageByClient' => $traffic->clientreplyurl,
                    'EndPageToVendor' => $traffic->vendorreplyurl,
                    'SurveyID' => $traffic->survey_id,
                    'SurveyCode' => $traffic->survey_code,
                    'SurveyTopic' => $topic_name,
                    'SurveyCountry' => $country_name,
                ];
                $finalArray[] = $exportItem;
                fputcsv($handle, $exportItem);
            }
            //fputcsv($handle, $finalArray);
            fclose($handle);
            $headers = array(
               'Content-Type' => 'text/csv',
            );
        }
        return response()->download($fileName, $fileName)->deleteFileAfterSend(true);
     }

    public function viewEndpageLinks(Request $request)
    {
        $project_id = $request->input('project_id');
        $project_endpages = $this->project_repo->getProjectEndpages($project_id);

        return view('internal.project.view_endpages')
            ->with('project_endpages',$project_endpages);
    }
}
