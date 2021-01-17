<?php
/**
 * Created by PhpStorm.
 * User: Sample Junction
 * Date: 12/17/2018
 * Time: 9:44 PM
 */
namespace App\Repositories\Internal\General;

use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\General\Country;
use App\Models\General\Language;
use App\Models\Project\Project;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectQuotaSpec;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectSurvey;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Models\Project\ProjectTopic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use App\Models\Traffics\Traffic;



class GeneralRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Client::class;
    }

    public function findClient($id)
    {
        $data = DB::table('clients')
            ->where('id', '=', $id)
            ->select('*')
            ->first();

        return $data;
    }
    public function findId($id)
    {
        $data = DB::table('countries')

            ->select('name','country_code','currency_code','status','language')
            ->where([
                ['id','=',$id],
                ['status','=','1'],
            ])
            ->first();

        $data1 = collect($data)->toArray();
        return $data1;
    }
    public function getLanguage($language_code)
    {
       //DB::connection()->enableQueryLog();
        $data = DB::table('languages')
            ->select('name','code')
            ->whereIn('code',$language_code)->pluck('name','code')->toArray();

        //dd(DB::getQueryLog());
        return $data;
    }

    public function langDetails()
    {
        $data = DB::table('languages')
            ->select('name','code')->get();

        return $data;
    }

    public function updateCountry($id,$data)
    {
        $data = DB::table('countries')
            ->where('id','=',$id)
            ->update($data);

        return $data;
    }

    public function createCountry($input,$lang_code)
    {

        $data = [
            'language' => $lang_code,
        ];

        $data1 = DB::table('countries')
            ->insert(array_merge($input,$data));
        return $data1;
    }
    public function deleteCountry($id)
    {

        $data = DB::table('countries')
            ->where('id','=',$id)
            ->delete();
        return $data;
    }

    public function getLanguageDetails($id)
    {
        //DB::connection()->enableQueryLog();
        $data = Language::find($id);
        //dd(DB::getQueryLog());
        return $data;

    }

    public function updateLang($id,$input)
    {
        //DB::connection()->enableQueryLog();
        $data = Language::where('id','=',$id)
            ->update($input);
        //dd(DB::getQueryLog());
        return $data;
    }

    public function createLang($input)
    {
        $data = Language::insert($input);
        return $data;
    }
    public function getStudyType($id)
    {
        $data = \App\Models\Project\StudyType::find($id);
        return $data;
    }
    public function updateStudyTypes($id,$input)
    {
        $data = StudyType::where('id','=',$id)
            ->update($input);
        return $data;
    }

    public function createStudyType($input)
    {
        $data = StudyType::insert($input);
        return $data;
    }

    public function getSurveyTopicDetails($id)
    {
        $data = ProjectTopic::find($id);
        return $data;
    }

    public function updateTopics($id,$input)
    {
        $data = ProjectTopic::where('id','=',$id)
            ->update($input);
        return $data;
    }

    public function createSurveyTopic($input)
    {
        $data = ProjectTopic::insert($input);

        return $data;
    }

    public static function parseFilterQuery($filter_data)
    {
        $filterColumns = [
            'status' => [],
            'country' => [],
            'study_type' => [],
            'project_manager' => [],
        ];

        foreach($filter_data as $item){
            list($key,$val) = explode(".",$item);
            $filterColumns[$key][] = $val;
        }
        return $filterColumns;
    }

    public static function getProjectByFilter( $status,$country,$study,$project_manager)
    {
        $status = array_filter($status);

        $country = array_filter($country);
        $study = array_filter($study);
        $project_manager = array_filter($project_manager);
        return $filterResult = DB::table('projects')
            ->where(function($query) use ($status, $country, $study, $project_manager) {
                if(!empty($status))
                    $query->whereIn('status_id', $status);

                if( !empty($country) )
                    $query->whereIn('country_code', $country);

                if( !empty($study) )
                    $query->whereIn('study_type_id', $study);

                if( !empty($project_manager) )
                    $query->whereIn('created_by', $project_manager);

            });
    }

    public function getProject($status,$country,$study,$project_manager)
    {

        $query = "";
        $status = array_filter($status);
        $country = array_filter($country);
        $study = array_filter($study);
        $project_manager = array_filter($project_manager);

        //DB::connection()->enableQueryLog();

        //DB::query()->whereIn()

        $filterResult = DB::table('projects')
            ->where(function($query) use ($status, $country, $study, $project_manager) {
                if(!empty($status))
                $query->whereIn('status_id', $status);

                if( !empty($country) )
                $query->whereIn('country_code', $country);

                if( !empty($study) )
                $query->whereIn('study_type_id', $study);

                if( !empty($project_manager) )
                $query->whereIn('created_by', $project_manager);

            })->with('user')
        ->get();
        /*dd($filterResult);*/
        //dd(DB::getQueryLog());

        return $filterResult;
    }

    public function updateStatus($id,$data)
    {
        $data = Project::where('id',$id)->update($data);
        return $data;
    }

    public function getAllFilterableData()
    {
        $countries = Country::all()
            ->where('is_filterable', '=', '1');
        $study_type = StudyType::all()
            ->where('status', '=', 1);
        $status = ProjectStatus::all()
            ->where('status', '=', 1);
        $project_manager = User::all()
            ->where('deleted_at', '=', null)
            ->where('confirmed', '=', 1);

        return $filter_elements = [
            'countries' => $countries,
            'study_types' => $study_type,
            'project_statuses' => $status,
            'project_managers' => $project_manager,
        ];

    }
    public function getSelectedColumns($column,$id)
    {
        //DB::connection()->enableQueryLog();
        $data = DB::table('projects')
            ->select($column)
            ->where('id','=',$id)
            ->get();
        //dd(DB::getQueryLog());
        return $data;
    }

    public function getCurrentStatus($id)
    {//DB::connection()->enableQueryLog();
        $data = Project::select('status_id')->where('id',$id)->first();
        //dd(DB::getQueryLog());
        return $data;
    }

    public function getCurrentStatusCode($id)
    {
        $data = ProjectStatus::select('id','name','next_status_flow')->where('id',$id)->first();
        return $data;
    }

    public function getStatusCodeToChange($status)
    {
        $code = ProjectStatus::select('code','name')->where('id',$status)->first();
        return $code;
    }

    public function getProjectDetails($id)
    {
        //DB::connection()->enableQueryLog();
        $data = Project::find($id);
        return $data;
    }

    public function createCloneProject($data)
    {
        $clone = Project::create($data);
        return $clone;
    }

    public function getVendorDetails($id)
    {
        $data = ProjectVendor::where('project_id','=',$id)->get();

        return $data->toArray();
    }
    public function createCloneVendor($clone_vendor_data)
    {

       // DB::connection()->enableQueryLog();
        $clone = ProjectVendor::create($clone_vendor_data);
       // dd(DB::getQueryLog());
        return $clone->id;
    }
    public function getProjectSurvey($id)
    {
        $data = ProjectSurvey::all()->whereIn('project_vendor_id',$id);
        return $data->toArray();
    }

    public function createCloneSurvey($survey)
    {
        //DB::connection()->enableQueryLog();
        $clone = ProjectSurvey::insert($survey);
        //dd(DB::getQueryLog());
        return $clone;
    }

    public function getQuotaDetails($project_id)
    {
        $data = ProjectQuota::where('project_id', '=', $project_id)->get();
        return $data->toArray();
    }

    public function createCloneQuota($quota)
    {
        $clone = ProjectQuota::create($quota);
        return $clone->id;
    }

    public function getQuotaSpecsDetails($id)
    {
       $data = ProjectQuotaSpec::all()->whereIn('project_quota_id',$id);

       return $data->toArray();
    }

    public function cloneQuotaSpecs($quota_specs)
    {
        $clone = ProjectQuotaSpec::insert($quota_specs);
        return $clone;
    }

    public function getScreener($project_id,$source_id)
    {

        $data = DB::table('project_vendors')->select('global_screener','predefined_screener','custom_screener')->where([
            ['project_id','=',$project_id],
            ['vendor_id','=',$source_id],
        ])->first();

        return $data;
    }

    public function updateScreener($data,$project_id,$vendor_id)
    {
        $update = DB::table('project_vendors')->where([
            ['project_id','=',$project_id],
            ['vendor_id','=',$vendor_id],
        ])->update($data);

        return $update;
    }

    public function getLanguagesByCountryId($countryId)
    {
        $data = DB::table('countries as con')
            ->select('lang.id','lang.name')
            ->join('languages as lang', function($query) {
                $query->whereRaw("FIND_IN_SET(lang.code, con.language) > 0");
            })
            ->where('con.id', '=', $countryId)
            ->get();
        return $data;

        /*$projectVendors = Language::where('project_id', '=', $project->id)
            ->whereRaw("find_in_set($quota->id,spec_quota_ids) > 0")
            ->with('traffics', 'source')->get();*/
    }
}
