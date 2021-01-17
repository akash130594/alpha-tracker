<?php

namespace App\Http\Controllers\Web\Internal\Project;

use App\Repositories\Internal\MasterQuestion\GlobalQuestionsRepository;
use App\Repositories\Internal\MasterQuestion\ProfileQuestionsRepository;
use App\Repositories\Internal\Project\ProjectQuotaRepository;
use App\Repositories\Internal\Project\ProjectStatusRepository;
use App\Repositories\Internal\Project\ProjectVendorRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Project\ProjectStatus;
use App\Models\Source\SourceType;
use App\Models\Project\Project;
use App\Models\Project\ProjectTopic;
use App\Models\General\Country;
use App\Repositories\Internal\Traffic\TrafficRepository;
use App\Repositories\Internal\Project\ProjectRepository;
use Illuminate\Support\Facades\Redirect;
use File;
use App\Repositories\Internal\General\GeneralRepository;
use App\Repositories\Internal\Archive\ArchivesRepository;
use MongoDB\BSON\UTCDateTime;


class ProjectStatusController extends Controller
{
    public $project_repo, $survey_repo, $trafficRepo,$arch_repo, $project_status_repo;
    public function __construct(
        ProjectRepository $project_repository,
        GeneralRepository $surveyRepo,
        TrafficRepository $trafficRepo,
        ArchivesRepository $archRepo,
        ProjectStatusRepository $projectStatusRepo
    )
    {
        $this->project_repo = $project_repository;
        $this->survey_repo = $surveyRepo;
        $this->arch_repo = $archRepo;
        $this->trafficRepo = $trafficRepo;
        $this->project_status_repo = $projectStatusRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
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
    public function incentivePaidBilled($project_id)
    {
        return redirect()->route('internal.project.upload.file',[$project_id]);
    }
    public function getFile(Request $request)
    {
        $id = $request->id;
        return view('internal.project.edit.ipbuild')
            ->with('project_id',$id);
    }
    public function postFile(Request $request)
    {
        $project_id = $request->id;
        $file = $request->file('file');
        /* $file_name = $file->getClientOriginalName();*/
        $destination_path = public_path() . '/uploads/CSV/';
        $done =  $this->uploadFile($file);
        if ($done==true) {
            $data = [
                'status_id' => '7',
                'status_label' => 'Incentive Paid & Billed',
            ];
            $update_status = Project::where('id', '=', $project_id)->update($data);
            if($update_status){
                return redirect()->route('internal.project.index')
                    ->withFlashSuccess("Status Has been Updated");
            }else{
                return redirect()->route('internal.project.index')
                    ->withErrors("Status Has been Updated");
            }
        }
    }
    public function uploadFile($file)
    {
        return true;
    }

    public function changeStatus(Request $request)
    {
        $new_status_id = $request->input('project_status');
        $project_id = $request->input('project_id');

        $project = Project::find($project_id);
        $nextStatusObject = $this->project_status_repo->getStatusById($new_status_id);
        $currentStatusObject = $this->project_repo->getProjectStatusById($project->status_id);
        if( $nextStatusObject->code == config('app.project_statuses.incentivepaid.code', 'IP') ){
            /*Todo: If File is Uploaded Already, then Do Not return*/
            return $this->incentivePaidBilled($project_id);
        }

        $this->project_status_repo->prepareProjectForStatusChange($project, $currentStatusObject, $nextStatusObject);
        $project = $this->project_status_repo->changeProjectStatus($project, $nextStatusObject);
        if ( !$project ) {
            return Redirect::back()
                ->withErrors(['Error Occured']);
        }
        $this->project_status_repo->notifyForProjectStatusChanged($project, $currentStatusObject, $nextStatusObject);
        return Redirect::back()
            ->withFlashSuccess('Project Status Updated');
    }
/*-----------------------------------Testing done by AS---------------------------------------------------------------------------------------*/
/******************************************Bulk Export Start****************************************************************************/
    private function exportAllColZip($id)
    {
        if ($id == false) {
            return Redirect::back()
                ->withErrors(['Select Surveys First']);
        } else {
            $files = [];
            $count = count($id);
            for($i=0;$i<$count;$i++){
                $column_selected = null;
                $traffics = $this->trafficRepo->getTrafficsDetails($id[$i],$column_selected);
                $project_code = Project::where('id',$id[$i])->first();
                    $filename = $project_code->code.'-'.date('d-m-Y').'.csv';
                    $handle = fopen($filename, 'w+');
                    fputcsv($handle,array('RespID', 'Status','Mode', 'RespStatus', 'Project Code', 'VVar', 'Started', 'Ended','Duration(mins)', 'SourceType', 'SourceCode', 'SourceName', 'SurveyID', 'SurveyName', 'SurveyTopic', 'SurveyCountry','ClientName', 'ClientLink'));
                    foreach ($traffics as $traffic){
                        $vvars= http_build_query($traffic->vvars,null, '|');
                        //  $vvars = implode(',',$traffic->vvars);
                        if($traffic->mode==1){
                            $mode = "LIVE";
                        }else{
                            $mode = "Test";
                        }
                        if($traffic->ended_at){
                            $start_date = new UTCDateTime(strtotime($traffic->started_at));
                            $end_date = new UTCDateTime(strtotime($traffic->ended_at) * 1000);
                            $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                        }else{
                            $duration = 0;
                        }
                        $country_name = Country::select('name')->where('id',$traffic->country_id)->first();
                        $topic_name = ProjectTopic::select('name')->where('id',$traffic->project_topic_id)->first();
                        $source_type_name = SourceType::select('name')->where('id',$traffic->source_type_id)->first();
                        fputcsv($handle, array($traffic->id, $traffic->status_name, $mode,$traffic->resp_status, $traffic->project_code, $vvars, $traffic->started_at, $traffic->ended_at,$duration,$traffic->source_type_name ,$traffic->source_code,$traffic->source_name,$traffic->survey_id,$traffic->survey_name,$topic_name->name,$traffic->country_name,$traffic->client_name,$traffic->client_link));
                    }
                    fclose($handle);
                    $headers = array(
                        'Content-Type' => 'text/csv',
                    );
                /* $files = glob(public_path('js/*'));*/
                $files[]= $filename;
            }
            \Zipper::make(public_path(date('d-m-Y').'-'.'Project'.'.zip'))->add($files)->close();
            if(File::exists($filename)){
                /*  dd($filename);*/
                File::delete($files);
            }
            return response()->download(public_path(date('d-m-Y').'-'.'Project'.'.zip'))->deleteFileAfterSend(true);
        }
    }

    private function exportSelectColumnZip($id,$column_selected)
    {
        if ($id == false) {
            return Redirect::back()
                ->withErrors(['Error Message', 'Select Surveys First']);
        } else {
            $files = [];
            $count = count($id);
            for($i=0;$i<$count;$i++){
                $traffics = $this->trafficRepo->getTrafficsDetails($id[$i],$column_selected);
                $project_code = Project::where('id',$id[$i])->first();
                $filename = $project_code->code.'-'.date('d-m-Y').'.csv';
                $handle = fopen($filename, 'w+');
                fputcsv($handle, $column_selected);
                foreach ($traffics as $row) {
                    //dd($row->code);
                    $value = [];
                    foreach ($column_selected as $column){
                        if($column == 'mode') {
                            if ($row->mode == 1) {
                                $mode = "LIVE";
                            } else {
                                $mode = "Test";
                            }
                            $value[] = $mode;
                        } else if($column == 'vvars'){
                            $vvars= http_build_query($row->vvars,null, '|');
                            $value[] = $vvars;
                        } else if($column == 'duration'){
                            if($row->ended_at){
                                $start_date = new UTCDateTime(strtotime($row->started_at));
                                $end_date = new UTCDateTime(strtotime($row->ended_at) * 1000);
                                $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                            }else{
                                $duration = 0;
                            }
                            $value[] = $duration;
                        } else{
                            $value[] = $row->$column;
                        }
                    }
                    fputcsv($handle, $value);
                }
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
                $files[]= $filename;
            }
            /* $files = glob(public_path('js/*'));*/

            \Zipper::make(public_path(date('d-m-Y').'-'.'Project'.'.zip'))->add($files)->close();
            if(File::exists($filename)){
                /*  dd($filename);*/
                File::delete($files);
            }
            return response()->download(public_path(date('d-m-Y').'-'.'Project'.'.zip'))->deleteFileAfterSend(true);
        }
    }

    private function exportAllCol($id)
    {
        if ($id == false) {
            return Redirect::back()
                ->withErrors(['Select Surveys First']);
        } else {
            $files = [];
            $column_selected = null;
            $filename = date('d-m-Y').'-'.'project.csv';
            $handle = fopen($filename, 'w+');
            $count = count($id);
            fputcsv($handle,array('RespID', 'Status','Mode', 'RespStatus', 'Project Code', 'VVar', 'Started', 'Ended','Duration(mins)', 'SourceType', 'SourceCode', 'SourceName', 'SurveyID', 'SurveyName', 'SurveyTopic', 'SurveyCountry','ClientName', 'ClientLink'));
                $traffics = $this->trafficRepo->getTrafficsDetailsCsv($id,$column_selected);
                foreach ($traffics as $traffic){
                    $vvars= http_build_query($traffic->vvars,null, '|');
                    //  $vvars = implode(',',$traffic->vvars);
                    if($traffic->mode==1){
                        $mode = "LIVE";
                    }else{
                        $mode = "Test";
                    }
                    if($traffic->ended_at){
                        $start_date = new UTCDateTime(strtotime($traffic->started_at));
                        $end_date = new UTCDateTime(strtotime($traffic->ended_at) * 1000);
                        $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                    }else{
                        $duration = 0;
                    }
                    $country_name = Country::select('name')->where('id',$traffic->country_id)->first();
                    $topic_name = ProjectTopic::select('name')->where('id',$traffic->project_topic_id)->first();
                    $source_type_name = SourceType::select('name')->where('id',$traffic->source_type_id)->first();
                    fputcsv($handle, array($traffic->id, $traffic->status_name, $mode,$traffic->resp_status, $traffic->project_code, $vvars, $traffic->started_at, $traffic->ended_at,$duration,$traffic->source_type_name ,$traffic->source_code,$traffic->source_name,$traffic->survey_id,$traffic->survey_name,$topic_name->name,$traffic->country_name,$traffic->client_name,$traffic->client_link));
                }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return response()->download($filename, $filename)->deleteFileAfterSend(true);
        }
    }

    private function exportCustomColumn($id,$column_selected)
    {
        if ($id == false) {
            return Redirect::back()
                ->withErrors(['Error Message', 'Select Surveys First']);
        } else {
            $files = [];
            $filename = date('d-m-Y').'-'.'project.csv';
            $handle = fopen($filename, 'w+');
            fputcsv($handle, $column_selected);
            $count = count($id);
                $traffics = $this->trafficRepo->getTrafficsDetailsCsv($id,$column_selected);
                foreach ($traffics as $row) {
                    //dd($row->code);
                    $value = [];
                    foreach ($column_selected as $column){
                        if($column == 'mode') {
                            if ($row->mode == 1) {
                                $mode = "LIVE";

                            } else {
                                $mode = "Test";
                            }
                            $value[] = $mode;
                        } else if($column == 'vvars'){
                            $vvars= http_build_query($row->vvars,null, '|');
                            $value[] = $vvars;
                        } else if($column == 'duration'){
                            if($row->ended_at){
                                $start_date = new UTCDateTime(strtotime($row->started_at));
                                $end_date = new UTCDateTime(strtotime($row->ended_at) * 1000);
                                $duration = date_diff($start_date->toDateTime(),$end_date->toDateTime());
                            }else{
                                $duration = 0;
                            }
                            $value[] = $duration;
                        } else{
                            $value[] = $row->$column;
                        }
                    }
                    fputcsv($handle, $value);
                }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return response()->download($filename, $filename)->deleteFileAfterSend(true);
        }
    }
/****************************************Bulk Export Ends************************************************************************************************/

/****************************************Bulk Change Status********************************************************************************************/
    private function bulkChangeStatus($id,$status)
    {
        $update_status = false;
        if ($id == false) {
            return Redirect::back()
                ->withFlashSuccess("Please select any Survey First");
        }
        $id_not_updated = [];
        $id_updated = [];
        for ($i = 0; $i < count($id); $i++) {
            $current_status_id = $this->survey_repo->getCurrentStatus($id[$i]);
            $project = $this->project_repo->getProjectDetails($id[$i]);
            $get_current_code = $this->survey_repo->getCurrentStatusCode($current_status_id->status_id);
            $next_flow_status = explode(',', $get_current_code->next_status_flow);
            $next_flow_status_details = $this->project_repo->getNextStatusFlowDetails($next_flow_status);
            $get_status_code_to_change = $this->survey_repo->getStatusCodeToChange($status);

            $this->project_status_repo->prepareProjectForStatusChange($project, $get_current_code, $get_status_code_to_change);
            foreach ($next_flow_status_details as $key => $value) {
                if ($value->code == $get_status_code_to_change->code) {
                    $project = $this->project_status_repo->changeProjectStatus($project, $value);
                    $update_status = true;
                    $this->project_status_repo->notifyForProjectStatusChanged($project, $get_current_code, $get_status_code_to_change);
                }
            }
            if( $update_status == true ){
                $id_updated[] = $id[$i];
            } else {
                $id_not_updated[] = $id[$i];
            }
        }
        if (count($id_updated) && count($id_not_updated) != 0) {
           // dd($id_not_updated,$id_updated);
            return Redirect::back()
                ->withFlashSuccess(count($id_updated) . " Projects Has Been updated & " . count($id_not_updated) . " Projects Cannot be Updated");
        } elseif (count($id_updated) && count($id_not_updated) == 0) {
           // dd($id_not_updated,$id_updated);
            return Redirect::back()
                ->withFlashSuccess(count($id_updated) . " Projects Has Been updated");
        } else {
            return Redirect::back()
                ->withErrors(count($id_not_updated) . " Projects cannot be Updated. Some Error Occurred");
        }
    }

 /******************************************Bulk Change Status Ends***************************************************************************************/
    public function updateAllSelected(Request $request)
    {
        if ($request->input('select_column') == true) {
            if ($request->input('zip') == true) {
                if ($request->input('select_column') == 'all_column') {
                    $id = $request->input('id', false);
                        return $this->exportAllColZip($id);
                } else {
                    $column_selected = $request->input('column', false);
                    $id = $request->input('id', false);
                    return $this->exportSelectColumnZip($id, $column_selected);
                }
            } else {
                if ($request->input('select_column') == 'all_column') {
                    $id = $request->input('id', false);
                    return $this->exportAllCol($id);
                } else if ($request->input('select_column') == "custom_column") {
                    $column_selected = $request->input('column', false);
                    $id = $request->input('id', false);
                    return $this->exportCustomColumn($id, $column_selected);
                }
                }
            } else {
                $id = $request->input('id', false);
                $status = $request->input('status', false);
                 return $this->bulkChangeStatus($id,$status);
                 }
   }
}
