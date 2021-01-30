<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 05-12-2018
 * Time: 11:11 PM
 */

namespace App\Repositories\Internal\Project;

use App\Events\Internal\Project\AfterStatusChanged;
use App\Models\Archive\Archive;
use App\Models\Client\Client;
use App\Events\Internal\Project\BeforeStatusChange;
use App\Models\General\Country;
use App\Models\General\Language;
use App\Models\MasterQuestion\ProfileQuestions;
use App\Models\Project\Project;
use App\Models\Project\ProjectCustomScreener;
use App\Models\Project\ProjectDedupe;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectQuotaSpec;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectSurvey;
use App\Models\Project\ProjectVendor;
use App\Models\Sjpanel\ProfileQuestion;
use App\Models\Traffics\Traffic;
use App\Repositories\Internal\MasterQuestion\ProfileQuestionsRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Models\UniqueFile\UniqueFileData;


class ProjectRepository extends BaseRepository
{
    public $profileQuesRepo;

    public function __construct(ProfileQuestionsRepository $profileQuesRepo)
    {
        parent::__construct();
        $this->profileQuesRepo = $profileQuesRepo;
    }

    /**
     * @return string
     */
    public function model()
    {
        return Project::class;
    }



    public function createProject($details)
    {
        unset($details['created_by_name']);
        $this->addCountryCode($details);
        $this->addLanguageCode($details);
        $this->addProjectCode($details);
        $this->addStatusCode($details);
        if($details['survey_dedupe_flag']==1){
            $dedupeObj = $this->generateProjectDedupeData($details['dedupe']);
        $dedupe_id = [
            'survey_dedupe_list_id' => $dedupeObj->id,
            ];
        } else {
            $dedupe_id = [];
        }
        $insert_data = array_merge($details,$dedupe_id);
        $project = Project::create($insert_data);
        return $project;
    }

    private function addCountryCode(&$projectData)
    {
        $country_id = $projectData['country_id'];
        $country = Country::find($country_id);
        $projectData['country_code'] = $country->country_code;
    }

    private function addLanguageCode(&$projectData)
    {
        $language_id = $projectData['language_id'];
        $language = Language::find($language_id);
        $projectData['language_code'] = $language->code;
    }

    private function addProjectCode(&$projectData)
    {
        $projectData['code'] = $this->generateNewProjectCode($projectData);
    }

    private function addStatusCode(&$projectData)
    {
        $status = $this->getProjectStatus('TBD');
        $projectData['status_id'] = $status->id;
        $projectData['status_label'] = $status->name;
    }

    private function getProjectStatus($status_code = 'TBD')
    {
        return ProjectStatus::where('code', '=', $status_code)->first();
    }

    public function getProjectStatusById($status_id)
    {
        return ProjectStatus::find($status_id);
    }

    public function generateNewProjectCode($details)
    {
        $yearMonthDay = date("ymd");
        $lastProjectCode = $this->getLastProject();
        $sycodelast = 1;
        if ($lastProjectCode) {
            $syyrmo = substr( $lastProjectCode->code,0,6);
            if ( $syyrmo == $yearMonthDay ){
                $sycodelast = (int) substr($lastProjectCode->code,6,3);
                $sycodelast = $sycodelast+1;
            }
        }
        $sycodelast = sprintf('%03d', $sycodelast);
        $projectCode = $yearMonthDay . $sycodelast . $details['country_code'];
        return $projectCode;

    }

    private function getLastProject()
    {
        $project = DB::table('projects')
            ->orderBy('id', 'desc')
            ->first();
        return $project;
    }



    public function updateProject($project_id, $post_data)
    {
        $project = Project::find($project_id);
        $project->fill($post_data);
        if($project->isDirty()){
            if($project->isDirty('survey_dedupe_flag') && $post_data['survey_dedupe_flag'] == 1){
                $dedupeObj = $this->generateProjectDedupeData($post_data['dedupe']);
                if($dedupeObj){
                    $project->survey_dedupe_list_id = $dedupeObj->id;
                }
            }
            return $project->save();
        }
        return $project;
    }

    private function generateProjectDedupeData($dedupeData)
    {
        $archive = [];
        $dedupeArray = [
            'dedupe_status' => $dedupeData['survey_dedupe_status'],
        ];
        $type = $dedupeData['de_dupe_type'];
        $dedupeContent = $dedupeData['data'][$type];
        $projectCodes = [];
        if($type == 'surveys_list'){
            $codes = explode("\r\n", trim($dedupeContent));
            $projectCodes = $this->getProjectCodesDataByCodes($codes,$dedupeData);
        }elseif($type == 'date_range'){
            $start_date = $dedupeContent['from_date'];
            $to_date = $dedupeContent['to_date'];
            $projectCodes = $this->getProjectsWithRange($start_date, $to_date, $dedupeData);
        } elseif ($type == 'client_dedupe') {
            $client_id = $dedupeContent;
            $projectCodes = $this->getProjectsDataByClientID($client_id,$dedupeData);
        }elseif ($type == 'wildcard_dedupe') {
            $wildCardName = $dedupeContent;
            $projectCodes = $this->getProjectsDataByWildcard($wildCardName,$dedupeData);
        }
        $dedupeArray['dedupe_data'] = json_encode($projectCodes);

        if(array_key_exists('archive',$dedupeData['data'])){
            $archive['archive'] = 1;
        }
        $dedupe_selected_filter = [
            'type' => $type,
            'content' => $dedupeContent
        ];
        $final_selected_filter_data = array_merge($archive,$dedupe_selected_filter);
        $json_convert_selected_filter = json_encode($final_selected_filter_data);
        $dedupeArray['dedupe_selected_filter'] = $json_convert_selected_filter;
        $dedupe = ProjectDedupe::updateOrCreate($dedupeArray);
        return $dedupe;
    }

    private function getProjectsWithRange($start_date, $end_date,$dedupeData)
    {
        /*Todo: Fix these functions*/
        $new_data = [];
        $start_date = date_create($start_date);
        $from_date = date_format($start_date,"Y-m-d H:i:s");
        $end_date = date_create($end_date);
        $to_date = date_format($end_date,"Y-m-d H:i:s");
        $get_project_code_with_id = Project::whereBetween('created_at',[$from_date,$to_date])->pluck('code','id')->toArray();
        if($get_project_code_with_id){
            $new_data['live'] = $get_project_code_with_id;
        }
        if(array_key_exists('archive',$dedupeData['data'])){
            $get_archive_data = Archive::whereBetween('created_date',[$from_date,$to_date])->pluck('project_code','project_id')->toArray();
            if($get_archive_data){
                $new_data['archive'] = $get_archive_data;
            }
        }
        return $new_data;
    }
    private function getProjectCodesDataByCodes($codes,$dedupeData)
    {
        $new_data = [];
        foreach ($codes as $key => $value) {
            $get_project_codes_with_id = Project::select('code','id')->where('code', '=', $value)->first();
            if($get_project_codes_with_id){
                $new_data['live'][] = [
                    $get_project_codes_with_id->id => $get_project_codes_with_id->code,
                ];
            }
            if ($get_project_codes_with_id == null && array_key_exists('archive',$dedupeData['data'])) {
                $get_project_codes_with_id = Archive::select('project_code','project_id')->where('project_code', '=', $value)->first();
                if($get_project_codes_with_id){
                    $new_data['archive'][] = [
                        $get_project_codes_with_id->project_id => $get_project_codes_with_id->project_code,
                    ];
                }
            }
        }
        return $new_data;
    }

    public function getProjectsDataByClientID($client_id,$dedupeData)
    {
        $get_project_code_with_id = Project::select('code','id')->where('client_id', '=', $client_id)->pluck('code','id')->toArray();
        $new_data = [];
        if($get_project_code_with_id){
            $new_data['live'] = $get_project_code_with_id;
        }
        if($get_project_code_with_id == null && array_key_exists('archive',$dedupeData['data'])){
            $client_code = Client::select('code')->where('id','=',$client_id)->first();
            $get_archive_data = Archive::select('project_code','project_id')->where('client_code','=',$client_code->code)->pluck('project_code','project_id')->toArray();
            if($get_archive_data){
                $new_data['archive'] = $get_archive_data;
            }
        }
        return $new_data;
    }

    public function getProjectsDataByWildcard($wildCardName,$dedupeData)
    {
        $new_data = [];
        $get_project_code_with_id = Project::select('code','id')->where('code', 'like', "$wildCardName%")->pluck('code','id')->toArray();
        if($get_project_code_with_id){
            $new_data['live'] = $get_project_code_with_id;
        }
        if(array_key_exists('archive',$dedupeData['data'])){
            $get_archive_data = Archive::select('project_code','project_id')->where('project_code','like',"$wildCardName%")->pluck('project_code','project_id')->toArray();
            if($get_archive_data){
                $new_data['archive'] = $get_archive_data;
            }
        }
        return $new_data;
    }

    public function getProjectQuota($project_id)
    {
        return ProjectQuota::where('project_id', '=', $project_id)->get();
    }

    public function createProjectQuotas($project, $formdata)
    {
        $parsedData = [];
        foreach($formdata as $key => $value){
            foreach($value as $count => $elem){
                foreach ($elem as $key4 => $value4) {
                    $parsedData[$count][$key4] = $value4;
                }

            }
        }
      /*  ProjectQuota::where('project_id', '=', $project->id)->delete();*/
       /* dd($formdata);*/
        $quota_array = [];
        foreach ($parsedData as $item) {
            $decoded = json_decode($item['quota_spec'],true);
            $get_decoded_data[] = $decoded;
            $formatted = [];
            foreach($decoded as $value){
                $string = $value['name'].'='.$value['value'];
                parse_str($string, $result);
                foreach($result as $key2 => $item2){
                    foreach($item2 as $key3 => $item3){
                        if(!empty($item3)){
                            $formatted[$key2][$key3][] = $item3;
                        }
                    }
                }
            }
            $global_zip = array_column($formatted,'GLOBAL_ZIP' );
            foreach($global_zip as $zip_data){
                $zip_data[1];
            }


            $quotaData = array(
                'name' => $item['name'],
                'description' => $item['name'],
                'count' => $item['number'],
                'cpi' => $item['cpi'],
                'quota_spec' => json_encode($formatted),
                'raw_quota_spec' => $item['quota_spec'],
                'formatted_quota_spec' => json_encode($formatted),
                'status' => 'active',
            );
           if(!is_null($item['id'])){
               $this->updateProjectQuota($project,$quotaData,$item['id']);
           } else {
               $this->saveProjectQuota($project, $quotaData);
           }
            $quota_array[] = $quotaData;
        }
        /*if(!empty($quota_array)){
            $this->saveProjectQuota($project, $quota_array);
        }*/
    }


    private function updateProjectQuota($project,$quotaData,$quota_id)
    {
        $quota['project_id'] = $project->id;
        $quotaArray = array(
            'project_id' => $project->id,
            'name'  => $quotaData['name'],
            'description'  => $quotaData['description'],
            'cpi'  => $quotaData['cpi'],
            'count' => $quotaData['count'],
            'raw_quota_spec' => $quotaData['raw_quota_spec'],
            'formatted_quota_spec' => $quotaData['formatted_quota_spec'],
        );
        $update_quota = ProjectQuota::where('id', '=', $quota_id)->update($quotaArray);
        return $update_quota;
    }
    private function saveProjectQuota($project, $quotaData)
    {
        $quota['project_id'] = $project->id;
        $quotaArray = array(
            'project_id' => $project->id,
            'name'  => $quotaData['name'],
            'description'  => $quotaData['description'],
            'cpi'  => $quotaData['cpi'],
            'count' => $quotaData['count'],
            'raw_quota_spec' => $quotaData['raw_quota_spec'],
            'formatted_quota_spec' => $quotaData['formatted_quota_spec'],
        );

        $quotaItem = ProjectQuota::create($quotaArray);

        $specsData = array(
            'project_quota_id' => $quotaItem->id,
            'quota_spec' => $quotaData['quota_spec'],
        );
        $this->createProjectQuotaSpecs($specsData, $project->country_code, $project->language_code);

    }

    private function createProjectQuotaSpecs($specsData, $country_code = 'US', $language_code = 'EN')
    {
        $specsArray = array(
            'project_quota_id' => $specsData['project_quota_id'],
        );

        $quotaSpec = json_decode($specsData['quota_spec'], true);
        $globalSpec = isset($quotaSpec['global'])?$quotaSpec['global']:false;   /*Global Qualifications*/
        $detailedSpec = isset($quotaSpec['detailed'])?$quotaSpec['detailed']:false; /*Profile Qualifications*/

        foreach ($quotaSpec as $profiletype => $specValues) {
            $isGlobal = false;
            if($profiletype == 'global'){
                $isGlobal = true;
                $specsArray['is_global'] = true;
            }else{
                $isGlobal = false;
                $specsArray['is_global'] = false;
            }

            //dd($specValues);
            /*Remove Either AGE Group or Custom AGE if not Available*/
            if( isset($specValues['GLOBAL_AGE']) || isset($specValues['custom_age']) ){
                /*$staticAge = (isset($specValues['GLOBAL_AGE']))?$specValues['GLOBAL_AGE']:[];*/
                $customAge = (isset($specValues['custom_age']))?$specValues['custom_age']:[];
                if( !empty($customAge) ){
                    unset($specValues['GLOBAL_AGE']);
                }else{
                    unset($specValues['custom_age']);
                }
            }
            foreach($specValues as $question => $options){
                $question_name = $question;
                $question_id = null;
                $specsArray['question_general_name'] = $question;
                $option_values = [];
                if( $isGlobal ){
                    $option_values = $this->parseDispatchSpecGlobal($question, $options, $specsArray, $country_code, $language_code);
                }else{
                    //dd($specValues, $question, $options);
                    /*Todo: Parse Profile Questions Here !important*/

                    $profile_question = $this->profileQuesRepo->getProfileQuestionByCode($question_name, $country_code, $language_code);
                    $specsArray['question_general_name'] = $profile_question->general_name;
                    foreach($options as $answerOption){
                        if (!empty($answerOption[0])) {
                            //Todo: skipping because of Allocation, but will have to handle this later
                            $option_values[] = $answerOption[0];
                        }

                    }

                }
                $specsArray['values'] = json_encode($option_values);
                $specsArray['raw_spec'] = json_encode($option_values);

                /*Just used for Testing Purpose*/
                /*$skiparray = [ 'GLOBAL_GENDER', 'GLOBAL_AGE', 'custom_age' ];
                if( !in_array($question_name, $skiparray ) ){
                    dd($option_values, $question_name, $specsArray);
                }*/
                ProjectQuotaSpec::create($specsArray);
            }
        }

        return true;

    }

    private function parseDispatchSpecGlobal($question, $options, &$specsArray, $country_code, $language_code)
    {
        $option_values = [];
        if($question == 'GLOBAL_GENDER'){
            $option_values = $this->parseDispatchBasicGender($question, $options, $specsArray);
        }else if($question == 'GLOBAL_AGE' || $question == 'custom_age'){
            $option_values = $this->parseDispatchBasicAge($question, $options, $specsArray);
        }else if ($question == 'GLOBAL_ZIP') {
            $option_values = $this->parseDispatchBasicZipcode($question, $options, $specsArray);
        }else{
            $option_values = $this->parseDispatchBasicHiddenQuestions($question, $options, $specsArray, $country_code);
        }
        return $option_values;
    }

    private function parseDispatchBasicHiddenQuestions($question, $options, &$specsArray, $country_code)
    {
        $option_values = false;
        foreach ($options as $option) {
            $option_values[] = $option[0];
        }
        return $option_values;
    }

    private function parseDispatchBasicGender($question, $options, &$specsArray)
    {
        $option_values = false;
        $genderRule = $this->parseGenderRule($options);
        if($genderRule){
            $option_values = $genderRule;
        }
        return $option_values;
    }

    private function parseGenderRule($genderOptions)
    {
        $genderRule = false;

        foreach ($genderOptions as $genderOption) {
            if(isset($genderOption[0]) && !is_array($genderOption[0]))
                $genderRule[] = $genderOption[0];
        }
        return $genderRule;

    }

    private function parseDispatchBasicAge($question, $options, &$specsArray)
    {
        $option_values = false;

        $specsArray['question_general_name'] = 'GLOBAL_AGE';
        $age_rule = false;
        if($question == 'GLOBAL_AGE') {
            $age_rule = $this->parseStaticAge($options);
        }else{
            $age_rule = $this->parseCustomAge($options);
        }
        if($age_rule){
            $option_values = $age_rule;
        }

        return $option_values;
    }

    private function parseStaticAge($staticAgeArray)
    {
        $ageRule = false;
        foreach ($staticAgeArray as $ageGroup) {

            if(!isset($ageGroup[0])){
                continue;
            }
            $ages = explode('-', $ageGroup[0]);
            $minAge = $ages[0];
            $maxAge = $ages[1];
            // prepare dates for comparison
            $minDate = Carbon::today()->subYears($maxAge); // make sure to use Carbon\Carbon in the class
            $maxDate = Carbon::today()->subYears($minAge)->endOfDay();
            $ageRule[] = array('min_date' => $minDate,'max_date' => $maxDate);
        }
        return $ageRule;
    }

    private function parseCustomAge($customAgeArray)
    {
        $ageRule = false;
        //array_shift($customAgeArray); Was Removing it earlier as additional Status value was being passed in array
        $ageRange = [];
        foreach ($customAgeArray as $ageGroups) {

            foreach($ageGroups as $count => $age){
                $keyflag = isset($age['start'])?'start':'end';
                $ageRange[$count][$keyflag] = isset($age['start'])?$age['start']:$age['end'];

            }
        }
        foreach ($ageRange as $ageGroup) {
            $minAge = $ageGroup['start'];
            $maxAge = $ageGroup['end'];
            // prepare dates for comparison
            $minDate = Carbon::today()->subYears($maxAge); // make sure to use Carbon\Carbon in the class
            $maxDate = Carbon::today()->subYears($minAge)->endOfDay();
            $ageRule[] = array('min_date' => $minDate,'max_date' => $maxDate);
        }
        return $ageRule;

    }

    private function parseDispatchBasicZipcode($question, $options, &$specsArray)
    {
        $option_values = false;
        $zipcodeRule = $this->parseZipcodeRule($options);
        if($zipcodeRule){
            $option_values = $zipcodeRule;
        }
        return $option_values;
    }

    private function parseZipcodeRule($zipcodeOptions)
    {
        $zipcodeRule = false;
        if (!empty($zipcodeOptions)
            && !empty($zipcodeOptions[0]['status'])
            && !empty($zipcodeOptions[1]['values'])
        ) {
            $zipcodes = $zipcodeOptions[1]['values'];
            $zipcodeRule = explode("\n", str_replace("\r", "", $zipcodes));
        }
        return $zipcodeRule;
    }

    public function createUniqueData($unique_data)
    {
        $create = UniqueFileData::create($unique_data);
        return $create;
    }
    public function getProjectVendors($project_id)
    {
        $data = ProjectVendor::select('*')->where('project_id',$project_id)->with('source')->get();
        return $data->toArray();
    }

    public function getProjectSurveys($project_id)
    {
        $data = ProjectSurvey::select('*')->where('project_id',$project_id)->get();
        return $data->toArray();
    }

    public function getProjectTraffics($project_id)
    {
        $project_id = intval($project_id);
        $data = Traffic::select('*')->where('project_id',$project_id)->get();
        return $data->toArray();
    }
    public function getProjectDetails($project_id)
    {
        $data = Project::where('id',$project_id)->first();
        return $data;
    }

    public function getNewProjectCode($project_id)
    {
        $data = Project::select('code')->where('id','=',$project_id)->first();
        return $data->code;
    }
    public function getProjectDedupe($dedupe_list_id)
    {
        $data = ProjectDedupe::where('id','=',$dedupe_list_id)->get();
        return $data->toArray();
    }
    public function getProjectCustomScreener($project_id)
    {
        $data = ProjectCustomScreener::where('project_id','=',$project_id)->get();
        return $data->toArray();
    }
    public function getProjectQuotaDetails($project_id)
    {
        $data = ProjectQuota::where('project_id','=',$project_id)->get();
        return $data->toArray();
    }
    public function createProjectDedupe($dedupe_data)
    {
        $create_dedupe = ProjectDedupe::create($dedupe_data);
        return $create_dedupe;
    }
    public function createProjectCustomScreener($custom_screener)
    {
        $create_screener = ProjectCustomScreener::create($custom_screener);
        return $create_screener;
    }
    public function getCloneProjectVendor($clone_vendor_details)
    {
        $get_project_vemdor = ProjectVendor::whereIn('id',$clone_vendor_details)
            ->get();
        return $get_project_vemdor->toArray();
    }

    public function getNextStatusFlowDetails($next_flow_status)
    {
        $data = ProjectStatus::select('id','name','code')->whereIn('code',$next_flow_status)->get();
        return $data;
    }

    public function changeStatusQuota($quota_id)
    {
        $update = [
            'status' => 0,
        ];
        $data = ProjectQuota::where('id','=',$quota_id)->update($update);
        return $data;
    }

    public function getDedupeData($dedupe_list_id)
    {
        $data = ProjectDedupe::where('id', '=', $dedupe_list_id)->first();
        return $data;
    }

    public function getProjectEndpages($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) {
            return false;
        }
        $endpages = $project->getEndpageLinks();

        return $endpages;
    }

}
