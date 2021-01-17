<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 05-12-2018
 * Time: 11:11 PM
 */

namespace App\Repositories\Internal\Report;

use App\Models\Apace_temp\Archive;
use App\Models\Auth\User;
use App\Models\Client\Client;
use App\Models\General\Country;
use App\Models\Project\Project;
use App\Models\Project\ProjectCustomScreener;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectQuotaSpec;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectSurvey;
use App\Models\Project\ProjectVendor;
use App\Models\Project\StudyType;
use App\Models\Sjpanel\ProfileQuestion;
use App\Models\Source\Source;
use App\Models\Traffics\Traffic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;



class ReportRepository extends BaseRepository
{
    public function model()
    {
        return Project::class;
    }

    public function getProject()
    {
        $data = Project::paginate(setting('reports_per_page'));
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
        $vendor =  $data = Source::all();
        $client = Client::all();

        return $filter_elements = [
            'countries' => $countries,
            'study_types' => $study_type,
            'project_statuses' => $status,
            'project_managers' => $project_manager,
            'vendor' => $vendor,
            'client' => $client,
        ];

    }
    public function filterProject($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search)
    {
        $from_date = date($from_date);
        $to_date = date($to_date);
        $query = "";
        $char_search = strtoupper($char_search);
        //DB::connection()->enableQueryLog();
        $filterResult= Project::
            where(function($query) use ($status, $country, $study_type, $project_manager,$from_date,$to_date,$client,$char_search,$project_char_search) {
                if(!empty($status))
                    $query->whereIn('status_id', $status);

                if( !empty($country) )
                    $query->whereIn('country_id', $country);

                if( !empty($study) )
                    $query->whereIn('study_type_id', $study_type);

                if( !empty($project_manager) )
                    $query->whereIn('created_by', $project_manager);
                if( !empty($from_date) )
                    $query->whereBetween('start_date', array($from_date,$to_date));
                if( !empty($client) )
                    $query->whereIn('client_id', $client);
               /* if( !empty($vendor) )
                    $query->whereIn('id',function($query_temp)use($vendor){
                        $query_temp->select('project_id')
                            ->from(with(new ProjectVendor)->getTable())
                            ->whereIn('vendor_id',$vendor);
                    });*/
                if( !empty($char_search) )
                    $query->where('name', 'LIKE',"{$char_search}%");

                if( !empty($project_char_search))
                    $query->where('code', 'LIKE',"{$project_char_search}%");
                })
                ->whereNotIn('status_id',[9])->with('user')->get();
        //dd(DB::getQueryLog());
        return $filterResult;
    }

    public function getArchiveData()
    {
        $data = Archive::all();
        return $data;
    }

    public function getTrafficsStats($status,$country,$project_manager,$study_type,$from_date,$to_date,$client,$char_search,$project_char_search)
    {
        $query = "";
        if($status){
            $status_name = ProjectStatus::whereIn('id',$status)->pluck('name')->toArray();
        }
        $filterdata = [];
        if(!empty($status) || !empty($country) || !empty($project_manager) || !empty($study_type) || !empty($from_date) || !empty($to_date) || !empty($client) || !empty($char_search) || !empty($project_char_search)){
            if($country){
                $filterdata['country_id'] = [
                    '$in' =>  array_map('intval', $country),
                ];
            }
            if($project_manager){
                $filterdata['created_by'] = [
                    '$in' => array_map('intval', $project_manager),
                ];
            }
            if($study_type){
                $filterdata['study_type'] = [
                    '$in' => array_map('intval', $study_type),
                ];
            }
            if($from_date){
                $filterdata['created_date'] = [
                    '$gte' => $from_date,
                    '$lte' => $to_date,
                ];
            }
            if($client){
                $filterdata['client_id'] = [
                    '$in' => array_map('intval', $client),
                ];
            }
            if($char_search){
                $filterdata['name'] = [
                    '$regex' => "^$char_search",
                    '$options' => "i"
                ];
            }
            if($project_char_search){
                $filterdata['project_code'] = [
                    '$regex' => "^$project_char_search",
                    '$options' => "i"
                ];
            }
            if($status){
                $filterdata['status'] = [
                    '$in' =>  [
                        $status_name[0],
                    ],
                ];
            }
        }
        else{
            return  $this->getArchives();
        }
        $filterResult = Archive::raw(function ($collection) use ($filterdata) {
            return $collection->aggregate([
                [
                    '$match' =>
                        $filterdata
                ],

                [
                    '$project' => [
                        '_id' => 0,
                        'id' => 1,
                        'project_id' => 1,
                        'code' => 1,
                        'name' => 1,
                        'survey_id' => 1,
                        'client_code' => 1,
                        'cpi' => 1,
                        'ir' => 1,
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

    public function getArchives()
    {
        $data = Archive::raw(function($collection){
            return $collection->aggregate([


                [
                    '$project' => [
                        '_id' => 0,
                        'id' => 1,
                        'project_id' => 1,
                        'name' => 1,
                        'code' => 1,
                        'client_code' => 1,
                        'cpi' => 1,
                        'ir' => 1,
                        'loi' => 1,
                        'created_by' => 1,
                        'study_type' => 1,
                        'country_id' => 1,
                        'country_code' => 1,
                        'starts' => ['$size' => '$traffics'],
                        'completes' => [
                            '$size' => [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 1] ]] ]
                        ],
                        'terminates' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 2] ]] ]
                        ],
                        'quotafull' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 3] ]] ]
                        ],
                        'abandons' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 0] ]] ]
                        ],
                        'traffics' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$gt' => ['$$item.duration', 0] ]] ]
                    ],
                ],
            ]);
        });
        $current = $data;

        return $data;
    }

    public function getTrafficData($id)
    {
        $data = Traffic::where('project_id',(int)$id)->get();
        return $data;
    }
    public function getArchive($project_id)
    {
        $data = Archive::where('project_id',$project_id)->first();
        return $data;
    }

    public function getArchivesWithStats($project_id)
    {
        $data = Archive::where('project_id',(int)$project_id)->first();
        return $data;
    }
    public function getCustomScreener($id)
    {
        $screener_data = ProjectCustomScreener::where('project_id', '=', $id)
            ->first();
        return $screener_data;
    }
}
