<?php

namespace App\Http\Controllers\Web\Internal\Report;

use App\Models\Archive\Archive;
use App\Models\Auth\User;
use App\Models\General\Country;
use App\Models\Project\ProjectTopic;
use App\Models\Source\SourceType;
use App\Repositories\Internal\MasterQuestion\GlobalQuestionsRepository;
use App\Repositories\Internal\MasterQuestion\ProfileQuestionsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Internal\Report\ReportRepository;
use  App\Http\Requests\Internal\Report\ReportRequest;
use App\Repositories\Internal\Traffic\TrafficRepository;
use App\Repositories\Internal\Archive\ArchivesRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redirect;
use App\Models\Project\Project;
use App\Models\Traffics\Traffic;
use MongoDB\BSON\UTCDateTime;

class ReportController extends Controller
{
    public $report_repo, $traffic_repo, $arch_repo, $globalRepo, $profileRepo;
    public function __construct(ProfileQuestionsRepository $profileRepo, GlobalQuestionsRepository $globalRepo, ReportRepository $reportRepo, TrafficRepository $trafficRepo, ArchivesRepository $archRepo)
    {
        $this->report_repo = $reportRepo;
        $this->traffic_repo = $trafficRepo;
        $this->arch_repo = $archRepo;
        $this->globalRepo = $globalRepo;
        $this->profileRepo = $profileRepo;
    }
    public function index()
    {
        //$get_setting = \Setting::get('paginate');
        $filter_elements = $this->report_repo->getAllFilterableData();
        $countries = $filter_elements['countries'];
        $study_type = $filter_elements['study_types'];
        $status = $filter_elements['project_statuses'];
        $project_managers = $filter_elements['project_managers'];
        $client =   $filter_elements['client'];
        $vendor = $filter_elements['vendor'];
        $archive_filter =  false;
        $filterable = false;
        return view('internal.report.index')
            ->with('status',$status)
            ->with('countries',$countries)
            ->with('study_type',$study_type)
            ->with('clients',$client)
            ->with('vendors',$vendor)
            ->with('project_manager',$project_managers)
            ->with('archive_filter',$archive_filter)
            ->with('filterable',$filterable);
    }
    /****************************Tested By AS************************************************************************/
    public function filterGetProjectBulk(ReportRequest $request)
    {
        $input = $request->except(['_token']);
        $status = $request->input('status',false);
        $country = $request->input('country',false);
        $project_manager = $request->input('project_manager',false);
        $study_type = $request->input('study_type',false);
        $from_date = $request->input('from_date',false);
        $to_date = $request->input('to_date',false);
        $client = $request->input('client',false);
        $char_search = $request->input('char',false);
        $project_char_search = $request->input('project_char',false);
        $archive = $request->input('active_archive',false);

        /*----------------------------------------Get Filterable Data-------------------------------------------------------------------------------------------*/
        $filter_elements = $this->report_repo->getAllFilterableData();
        $countries = $filter_elements['countries'];
        $study_types = $filter_elements['study_types'];
        $statuses = $filter_elements['project_statuses'];
        $project_managers = $filter_elements['project_managers'];
        $clients =   $filter_elements['client'];
        /*-------------------------------Checking different conditions having filter archive---------------------------------------------------------------------------------------------*/
        if( count($archive) == 1 && $archive[0] == 'archive' ){
            $archive_filter =  $archive;
            $archive_data = $this->report_repo->getTrafficsStats($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search);
            $projects = collect();
        } else if( count($archive) == 1 && $archive[0] == 'active' ){
            $archive_filter =  $archive;
            $archive_data = collect();
            $projects = $this->report_repo->filterProject($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search);
            $trafficStats = $this->traffic_repo->getTrafficsStats($projects);
            $projects = $this->getProjectWithTraffic($projects,$trafficStats);
        } else if( empty($archive) || count($archive) > 1 ){
            $archive_filter =  $archive;
            $archive_data = $this->report_repo->getTrafficsStats($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search);
            $projects = $this->report_repo->filterProject($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search);
            $trafficStats = $this->traffic_repo->getTrafficsStats($projects);
            $projects = $this->getProjectWithTraffic($projects,$trafficStats);
        }
        $filterable = true;

        return view('internal.report.index')
            ->with('input',$input)
            ->with('projects', $projects)
            ->with('status_filter',$status)
            ->with('country_filter',$country)
            ->with('study_filter',$study_type)
            ->with('project_manager_filter',$project_manager)
            ->with('client_filter',$client)
            ->with('countries',$countries)
            ->with('status',$statuses)
            ->with('study_type',$study_types)
            ->with('project_manager',$project_managers)
            ->with('clients',$clients)
            ->with('char_filter',$char_search)
            ->with('archive_data',$archive_data)
            ->with('filterable',$filterable)
            ->with('archive_filter',$archive_filter);
    }
    /******************************************Tested By AS******************************************************************/
    public function bulkExportProject(Request $request)
    {
        $id = $request->input('project_id', false);
        $archive_id = $request->input('archive_id', false);
        if ($id == false && $archive_id == false) {
            return Redirect::back()
                ->withErrors(['Select Surveys First']);
        } else {
            $files = [];
            $data = Project::all()->whereIn('id', $id);
            $archive_data = $this->getArchiveData($archive_id);
            $trafficStats = $this->getTrafficsStats($data);
            $projects = $data->map(function ($value) use ($trafficStats) {
                $value->traffic = $trafficStats->first(function ($item) use ($value) {
                    return $item->id == $value->id;
                });
                return $value;
            });
            $filename = date('d-m-Y').'-'.'report.csv';
            $handle = fopen($filename, 'w+');
            fputcsv($handle, array('Survey Id', 'CI', 'PM', 'ST', 'CMP', 'TE', 'QF','QTE','AB','AB%','IR%', 'LOI', 'CPI','category'));
            foreach($archive_data as $archive){
                if ($archive->starts) {
                    $ir = (($archive->completes / $archive->starts) * 100);
                    $ab = (($archive->abandons / $archive->starts) * 100);
                } else {
                    $ir = 0;
                    $ab = 0;
                }
                if(!empty($archive->traffics)){
                    $json_encode = json_encode($archive->traffics);
                    $get_traffic = json_decode($json_encode,true);
                    $duration = array_column($get_traffic,'duration');
                    $avg_loi = (array_sum($duration)/count($duration));
                } else{
                    $avg_loi = 0;
                }
                fputcsv($handle, array($archive['code'], $archive['client_code'], $archive['created_by'], $archive->starts, $archive->completes, $archive->terminates, $archive->quotafull,  $archive->quality_terminate ,$archive->abandons, $ab ,$ir, $avg_loi, $archive['cpi'],'A'));
            }
            foreach ($projects as $row) {
                if($row->traffic){
                    if ($row->traffic['starts']) {
                        $ir = (($row->traffic['completes'] / $row->traffic['starts']) * 100);
                        $ab = (($row->traffic['abandons'] / $row->traffic['starts']) * 100);
                    } else {
                        $ir = 0;
                        $ab = 0;
                    }
                    $pm  = User::where('id',$row->created_by)->first();
                    fputcsv($handle, array($row['code'], $row['client_code'], $pm->first_name.$pm->last_name , $row->traffic['starts'], $row->traffic['completes'], $row->traffic['terminates'], $row->traffic['quotafull'], $row->traffic['quality_terminate'] , $row->traffic['abandons'],$ab ,$ir, $row->traffic->loi, $row['cpi'],'P'));
                } else{
                    fputcsv($handle, array($row['code'], $row['client_code'], $row['created_by'], 0, 0, 0, 0, 0, 0,0,0, $row['loi'], $row['cpi'],'P'));
                }
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return response()->download($filename, $filename)->deleteFileAfterSend(true);
        }
    }


    private function getProjectWithTraffic($projects,$trafficStats)
    {

        $data = $projects->map(function ($value) use ($trafficStats)  {
            $value->traffic = $trafficStats->first(function($item) use ($value) {
                return $item->id == $value->id;
            });
            return $value;
        });
        return $data;
    }

    private function getArchiveData($archive_id)
    {
        $archive_id[] = $archive_id;
        $filterResult = Archive::raw(function ($collection) use ($archive_id) {
            return $collection->aggregate([
                [
                    '$match' =>[
                        'project_id' => [
                            '$in' => array_map('intval',$archive_id),
                        ],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => 1,
                        'name' => 1,
                        'code' => 1,
                        'survey_id' => 1,
                        'client_code' => 1,
                        'loi' => 1,
                        'cpi' => 1,
                        'created_by' => 1,
                        'study_type' => 1,
                        'country_code' => 1,
                        'starts' => ['$size' => '$traffics'],
                        'completes' => ['$size' =>
                            ['$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => ['$eq' => ['$$item.status', 1]]]]
                        ],
                        'terminates' => ['$size' =>
                            ['$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => ['$eq' => ['$$item.status', 2]]]]
                        ],
                        'quotafull' => ['$size' =>
                            ['$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => ['$eq' => ['$$item.status', 3]]]]
                        ],
                        'quality_terminate' => ['$size' =>
                            ['$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => ['$eq' => ['$$item.status', 4]]]]
                        ],
                        'abandons' => ['$size' =>
                            ['$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => ['$eq' => ['$$item.status', 0]]]]
                        ],
                        'traffics' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$gt' => ['$$item.duration', 0] ]] ]
                    ]
                ],

            ]);
        });
        return $filterResult;
    }
    private function getTrafficsStats($project)
    {
        $project_ids = $project->pluck('id')->toArray();
        $data = Traffic::raw(function($collection) use ($project_ids) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => [
                            '$in' => $project_ids
                        ],
                        'mode' => "1"
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_id',
                        'starts'=> [
                            '$sum'=> 1
                        ],
                        'completes'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 1 ] ],1,0
                                ],
                            ]
                        ],
                        'terminates'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 2 ] ],1,0
                                ],
                            ]
                        ],
                        'quotafull'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 3 ] ],1,0
                                ],
                            ]
                        ],
                        'quality_terminate'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 4 ] ],1,0
                                ],
                            ]
                        ],
                        'abandons'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 0 ] ],1,0
                                ],
                            ]
                        ],
                        'loi' => [
                            '$avg' => '$duration',
                        ],
                    ]
                ],
                [
                    '$project' => [
                        '_id'=> 0,
                        'id'=> '$_id',
                        'starts' => 1,
                        'completes' => 1,
                        'terminates' => 1,
                        'quotafull' => 1,
                        'quality_terminate' => 1,
                        'abandons' => 1,
                        'loi' => 1,
                    ]
                ]
            ]);
        });
        if (empty($data)) {
            $data = new \stdClass();
            $data->starts = 0;
            $data->completes = 0;
            $data->terminates = 0;
            $data->quotafull = 0;
            $data->quality_terminate = 0;
            $data->abandons = 0;
        }
        return $data;
    }

    public function trafficExport(Request $request)
    {
        $category = $request->category;
        $id = $request->id;
        if ($category == 'P') {
            $project_code = Project::select('code')->where('id', $id)->first();
            $traffic_stats = $this->report_repo->getTrafficData($id);
            if ($traffic_stats) {
                $file_name = $project_code->code.'-'.date('d-m-Y').'.csv';
                $handle = fopen($file_name, 'w+');
                fputcsv($handle,array('RespID', 'Status','Mode', 'RespStatus', 'Project Code', 'VVar', 'Started', 'Ended','Duration(mins)', 'SourceType', 'SourceCode', 'SourceName', 'SurveyID', 'SurveyName', 'SurveyTopic', 'SurveyCountry','ClientName', 'ClientLink'));
                foreach ($traffic_stats as $traffic) {
                    $vvars = http_build_query($traffic->vvars, null, '|');
                    //  $vvars = implode(',',$traffic->vvars);
                    if ($traffic->mode == 1) {
                        $mode = "LIVE";
                    } else {
                        $mode = "Test";
                    }
                    if ($traffic->ended_at) {
                        $start_date = new UTCDateTime(strtotime($traffic->tarted_at));
                        $end_date = new UTCDateTime(strtotime($traffic->ended_at) * 1000);
                        $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                    } else {
                        $duration = 0;
                    }
                    $topic_name = ProjectTopic::select('name')->where('id', $traffic->project_topic_id)->first();
                    fputcsv($handle, array($traffic->id, $traffic->status_name, $mode,$traffic->resp_status, $traffic->project_code, $vvars, $traffic->started_at, $traffic->ended_at,$duration,$traffic->source_type_name ,$traffic->source_code,$traffic->source_name,$traffic->survey_id,$traffic->survey_name,$topic_name->name,$traffic->topic_name,$traffic->name,$traffic->client_name,));
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
            }
        } else {
            $archive_data = $this->report_repo->getArchivesWithStats($id);
            if ($archive_data) {
                $file_name = $archive_data->project_code.'-'.date('d-m-Y').'.csv';
                $handle = fopen($file_name, 'w+');
                fputcsv($handle,array('RespID', 'Status','Mode', 'RespStatus', 'Project Code', 'VVar', 'Started', 'Ended','Duration(mins)', 'SourceType', 'SourceCode', 'SourceName', 'SurveyID', 'SurveyName', 'SurveyTopic', 'SurveyCountry','ClientName', 'ClientLink'));
                foreach ($archive_data['traffics'] as $traffic) {
                    $vvars = http_build_query($traffic['vvars'], null, '|');
                    if ($traffic['mode'] == 1) {
                        $mode = "LIVE";
                    } else {
                        $mode = "Test";
                    }
                    if ($traffic['ended_at']) {
                        $start_date = new UTCDateTime(strtotime($traffic['tarted_at']));
                        $end_date = new UTCDateTime(strtotime($traffic['ended_at']) * 1000);
                        $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                    } else {
                        $duration = 0;
                    }
                    fputcsv($handle, array(
                        $traffic['id'],
                        $traffic['status_name'],
                        $mode,
                        $traffic['resp_status'],
                        $traffic['project_code'],
                        $vvars,
                        $traffic['started_at'],
                        $traffic['ended_at'],
                        $duration,
                        $traffic['source_type_name'],
                        $traffic['source_code'],
                        $traffic['source_name'],
                        $traffic['survey_id'],
                        $traffic['survey_name'],
                        $traffic['topic_name'],
                        $traffic['country_name'],
                        $traffic['client_name']));
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
            }
        }
        return response()->download($file_name, $file_name)->deleteFileAfterSend(true);
    }

    public function screenerExport(Request $request)
    {
        $id = (int)$request->id;
        $custom_screener_keys = [];
        $status = $request->category;
        if($status == 'A'){
            $archive_data = Archive::where('id',$id)->first();
            if($archive_data){
                $file_name = $archive_data->code.".csv";
                $handle = fopen($file_name,'w+');
                $global_keys = $this->globalRepo->getGlobalQuestionIds();
                $custom_screener_data = $archive_data->project_custom_screener;
                if(!empty($custom_screener_data)){
                    $custom_screener = $custom_screener_data[0];
                    $custom_screener  = json_decode($custom_screener['screener_json'], true);
                    $custom_screener_keys = array_keys($custom_screener);
                    $custom_screener_keys = array_merge($global_keys, $custom_screener_keys);
                }
                $traffic_screener_key = $this->getArchiveTrafficScreenerKey($archive_data);
                $new_traffic_screener_key = array_unique(array_merge($traffic_screener_key,$custom_screener_keys));
                array_unshift($new_traffic_screener_key , 'respid');
                fputcsv($handle, $new_traffic_screener_key);
                foreach($archive_data['traffics'] as $traffic){
                    $data_export = [];
                    $data_export['respid'] = $traffic['id'];
                    foreach($traffic['filled_screeners'] as $key => $value){
                        foreach ($value as $screener_data){
                            $get_screener_keys[] = $screener_data['general_name'];
                            $precode = implode(',',$screener_data['sel_precode']);
                            $answer = implode(',',$screener_data['sel_answer_text']);
                            $data_export[$screener_data['general_name']] = $answer;
                            $data_index = array_search($screener_data['general_name'],$new_traffic_screener_key);
                        }
                        //$final_data = '\''.implode("','",$data_export).'\'';
                    }
                    $data = [];
                    $export_keys = array_unique(array_merge($get_screener_keys, $new_traffic_screener_key));
                    foreach ($data_export as $key=>$value){
                        if(in_array($key,$export_keys)){
                            $data_index = array_search($key,$export_keys);
                            $export[$key] = $value;
                            unset($export_keys[$data_index]);
                        }
                    }
                    if($export_keys){
                        foreach ($export_keys as $index => $id){
                            $global_export[$id] = "";
                        }
                    } else {
                        $global_export = [];
                    }
                    $final_export = array_merge($export,$global_export);
                    $get_all_keys = array_keys($final_export);
                    $new_screener_keys = array_unique(array_merge($get_all_keys, $new_traffic_screener_key));
                     $final_export_data = array_combine($new_traffic_screener_key,$final_export);
                     fputcsv($handle, $final_export_data);
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
            }

        } else {
            $project_traffic = Traffic::where('project_id','=',(int)$id)->get();
            $project_code = Project::select('code')->where('id',$id)->first();
            $global_keys = $this->globalRepo->getGlobalQuestionIds();
            $get_custom_screener = $this->report_repo->getCustomScreener($id);
            if(!empty($get_custom_screener)){
                $custom_screener = $get_custom_screener->screener_json;
                $custom_screener  = json_decode($custom_screener, true);
                $custom_screener_keys = array_keys($custom_screener);
                $custom_screener_keys = array_merge($global_keys, $custom_screener_keys);
            }
            $traffic_screener_key = $this->getProjectTrafficScreenerKey($project_traffic);
            $new_traffic_screener_key = array_unique(array_merge($traffic_screener_key,$custom_screener_keys));
            array_unshift($new_traffic_screener_key , 'respid');
            if($project_traffic){
                $file_name = $project_code->code.".csv";
                $handle = fopen($file_name,'w+');
                fputcsv($handle, $new_traffic_screener_key);
                foreach($project_traffic as $traffic){
                    $data_export = [];
                    $data_export['respid'] = $traffic['id'];
                    foreach($traffic['filled_screeners'] as $key => $value){
                        foreach ($value as $screener_data){
                            $get_screener_keys[] = $screener_data['general_name'];
                            $precode = implode(',',$screener_data['sel_precode']);
                            $answer = implode(',',$screener_data['sel_answer_text']);
                            $data_export[$screener_data['general_name']] = $answer;
                            $data_index = array_search($screener_data['general_name'],$new_traffic_screener_key);
                        }
                    }
                    $data = [];
                    $export_keys = array_unique(array_merge($get_screener_keys, $new_traffic_screener_key));
                    foreach ($data_export as $key=>$value){
                        if(in_array($key,$export_keys)){
                            $data_index = array_search($key,$export_keys);
                            $export[$key] = $value;
                            unset($export_keys[$data_index]);
                        }
                    }
                    if($export_keys){
                        foreach ($export_keys as $index => $id){
                            $global_export[$id] = "";
                        }
                    } else {
                        $global_export = [];
                    }
                    $final_export = array_merge($export,$global_export);
                    $get_all_keys = array_keys($final_export);
                    $new_screener_keys = array_unique(array_merge($get_all_keys, $new_traffic_screener_key));
                    $final_export_data = array_combine($new_traffic_screener_key,$final_export);
                    fputcsv($handle, $final_export_data);
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
            }
        }
        return response()->download($file_name, $file_name)->deleteFileAfterSend(true);
    }

    private function getArchiveTrafficScreenerKey($archive_data)
    {
        $get_all_keys = [];
        foreach($archive_data['traffics'] as $traffic){
            foreach ($traffic['filled_screeners'] as $key=>$value){
               foreach($value as $screener_data){
                   $get_all_keys[] = $screener_data['general_name'];
               }
            }
        }
       return $get_all_keys;
    }

    private function getProjectTrafficScreenerKey($project_traffic)
    {
        $get_all_keys = [];
        foreach($project_traffic as $traffic){
            foreach ($traffic['filled_screeners'] as $key=>$value){
                foreach($value as $screener_data){
                    $get_all_keys[] = $screener_data['general_name'];
                }
            }
        }
        return $get_all_keys;
    }
}

