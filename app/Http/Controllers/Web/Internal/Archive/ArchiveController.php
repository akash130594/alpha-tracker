<?php

namespace App\Http\Controllers\Web\Internal\Archive;

use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\Client\ClientSecurityImpl;
use App\Models\Client\ClientSecurityType;
use App\Models\General\Country;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Repositories\Internal\Archive\ArchivesRepository;
use App\Repositories\Internal\Project\ProjectSurveyRepository;
use App\Repositories\Internal\Source\SourceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Traffics\Traffic;
use App\Models\Project\Project;
use App\Models\Archive\Archive;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Exceptions\Handler;
use Freshbitsweb\Laratables\Laratables;


/**
 * This mail is used for handling the Archive functionality of displaying all the archive data, summary, rebuild project,
 * quick export of data of archive, view details..
 *
 * Class ArchiveController
 * @author Pankaj Jha
 * @author Akash Sharma
 * @access public
 * @package  App\Http\Controllers\Web\Internal\Archive\ArchiveController
 */

class ArchiveController extends Controller
{

    /**
     * @var ArchivesRepository
     * @param $archRepo
     * @param $projectSurveyRepo
     * @param $sourceRepo
     */
    public $archRepo,$projectSurveyRepo,$sourceRepo;

    /**
     * ArchiveController constructor.
     * @param ArchivesRepository $archRepo
     * @param SourceRepository $sourceRepo
     * @param ProjectSurveyRepository $projectSurveyRepo
     */
    public function __construct(
        ArchivesRepository $archRepo,
        SourceRepository $sourceRepo,
        ProjectSurveyRepository $projectSurveyRepo
    )
    {
        $this->archRepo = $archRepo;
        $this->sourceRepo = $sourceRepo;
        $this->projectSurveyRepo = $projectSurveyRepo;
    }

    /**This function is used to redirect the user to View of All Archive Details.
     *
     * @return resource archive/index.blade.php
     */
    public function index()
    {
        $country = Country::select('*')->where('is_filterable','=','1')->get();
        $study_type = StudyType::select('*')->where('status','=',1)->get();
        $project_manager = User::select('*')->get();
        $archive_data = $this->archRepo->getArchivesWithStats();
        return view('internal.archive.index')
            ->with('archives',$archive_data)
            ->with('countries',$country)
            ->with('study_types',$study_type)
            ->with('project_managers',$project_manager);
    }

    /**
     * This action is used for getting summary of archive and redirecting user to archive summary view page.
     *
     * @param Request $request
     * @return resource archive.summary.blade.php
     */
    public function summary(Request $request)
    {
        $archive_id = (int)$request->id;
        $archive_project = $this->archRepo->getArchiveProjectSummary($archive_id);
        return view('internal.archive.summary')
            ->with('archive',$archive_project);
    }

    /**
     * This function show all the details of the archived project as project quota, project vendor details, screener info
     * and redirect to the details view page.
     *
     * @param Request $request
     * @return resource archive/view_details.
     */
    public function viewDetails(Request $request)
    {
        $project_id = $request->id;
        $archive = Archive::select('*')->where('project_id','=',intval($project_id))->first()->toArray();
        return view('internal.archive.view_details')
            ->with('archive',$archive);
    }

    /**
     * This function helps to rebuild the project from archive to TBD project.
     *
     * @access public
     * @param Request $request
     * @return mixed
     */
    public function reBuildProject(Request $request)
    {
        $archive_id = (int)$request->id;
        $archive = $this->archRepo->getArchiveDetail($archive_id);
        $project_vendors = $archive['project_vendors'];
        $project_quotas = $archive['project_quota'];
        $project_dedupe = $archive['project_dedupe'];
        $project_custom_screener = $archive['project_custom_screener'];
        $unset_keys = array('_id','id','project_id','project_surveys','traffics','project_vendors','project_quota','project_dedupe','project_custom_screener');
        foreach($unset_keys as $key){
            unset($archive[$key]);
        }
        $project_details = $archive;
        $rebuild_project = $this->archRepo->createProject($project_details);
        foreach ($project_vendors as $project_vendor){
           unset($project_vendor['id']);
            $project_vendor['project_id'] = $rebuild_project->id;
            $rebuild_project_vendors = $this->archRepo->createProjectVendors($project_vendor);
            $create_internal_survey = $this->createInternalSourceSurvey($rebuild_project);
        }
        if($project_quotas){
            foreach ($project_quotas as $quota){
                unset($quota['id']);
                $quota['project_id'] = $rebuild_project->id;
                $rebuild_project_quota = $this->archRepo->createProjectQuota($quota);
            }
        }
        if($project_dedupe){
                $rebuild_dedupe = $this->archRepo->createProjectDedupe($project_dedupe);
        }
        if($project_custom_screener){
            $project_custom_screener['project_id'] = $rebuild_project->id;
            $rebuild_project_custom_screener = $this->archRepo->createProjectCustomScreener($project_custom_screener);
        }
            return redirect()->route('internal.archive.user.index')
                ->withFlashSuccess("Project Rebuild");
    }

    /**
     * This action is used for assigning the project that has been rebuild to Internal Test Source during rebuilding the project.
     *
     * @access private
     * @param $project
     */
    private function createInternalSourceSurvey($project)
    {
        $internalSource = $this->sourceRepo->getInternalSource();
        $internalProjectVendor = $this->projectSurveyRepo->getProjectVendorByProjectId($project->id, $internalSource->code);
        if ( empty($internalProjectVendor) ) {
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


    /**
     * This function is used for handling  the filter applied on the archive data and showing the user the filtered
     * archive data and redirecting the user to view of all archive page.
     *
     *
     * @access public
     * @param Request $request
     * @return resource archive/index.blade.php
     */
    public function filterGetArchive(Request $request)
    {
        $input = $request->all();
        $status = $request->input('status',false);
        $filter_data = $request->input('status',false);
        $filter_elements = $this->archRepo->getAllFilterableData();
        $countries = $filter_elements['countries'];
        $study_types = $filter_elements['study_types'];
        $project_managers = $filter_elements['project_managers'];
         $country = []; $study_type = []; $project_manager = [];
        $filterColumns = [
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
        $archive = $this->archRepo->getArchive($country,$study_type,$project_manager);
        return view('internal.archive.index')
            ->with('input',$input)
            ->with('archives', $archive)
            ->with('status_filter',$status)
            ->with('country_filter',$country)
            ->with('study_filter',$study_type)
            ->with('project_manager_filter',$project_manager)
            ->with('countries',$countries)
            ->with('study_types',$study_types)
            ->with('project_managers',$project_managers)
            ->with('filter_current_data',$filter_data)
            ->with('archive',$archive);
    }

    /**
     * This function is used for quick export of the important stats of the archived project.
     *
     * @access public
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
        public function quickExport(Request $request)
        {
            $archive_id = $request->id;
            if ($archive_id == false) {
                return Redirect::back()
                    ->withErrors(['Select Surveys First']);
            } else {
                $row = $this->archRepo->getArchiveDetails($archive_id);
                $filename = $row['project_code'].date('d-m-Y').'-'.".csv";
                $handle = fopen($filename, 'w+');
                fputcsv($handle, array('Archive Id', 'CI', 'PM', 'ST', 'CMP', 'TE', 'QF','QTE', 'AB','AB%','IR%', 'LOI', 'CPI'));
                    try{
                        $ir = (($row['completes']/$row['starts']) * 100);
                        $ab = (($row['abandons']/$row['starts']) * 100);
                                }catch(\Exception $exception) {
                        $ir = 0;
                        $ab = 0;
                    }
                    fputcsv($handle, array($row['id'], $row['client_code'], $row['created_by'], $row['starts'], $row['completes'], $row['terminates'], $row['quotafull'], $row['quality_terminate'], $row['abandons'], $ab,$ir, $row['loi'], $row['cpi']));
                fclose($handle);
                $headers = array(
                    'Content-Type' => 'text/csv',
                );
                return response()->download($filename, $filename)->deleteFileAfterSend(true);
            }
        }
}
