<?php

/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 31-01-2019
 * Time: 09:28 PM
 */

namespace App\Repositories\Internal\Archive;


use App\Models\Archive\Archive;
use App\Models\Project\Project;
use App\Models\Project\ProjectCustomScreener;
use App\Models\Project\ProjectDedupe;
use App\Models\Project\ProjectQuota;
use App\Models\Project\ProjectStatus;
use App\Models\Project\ProjectVendor;
use App\Repositories\BaseMongoRepository;
use App\Models\General\Country;
use App\Models\Project\StudyType;
use App\Models\Auth\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class ArchivesRepository extends BaseMongoRepository
{
    private $collection = 'archive_projects';
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
    public function getArchivesWithStats()
    {
        $collection = $this->getCollection($this->collection);
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
                        'created_by' => 1,
                        'study_type' => 1,
                        'country_id' => 1,
                        'country_code' => 1,

                        'starts' => ['$size' => '$traffics'],
                        'completes' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 1] ]] ]
                        ],
                        'terminates' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 2] ]] ]
                        ],
                        'quotafull' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 3] ]] ]
                        ],
                        'quality_terminate' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 4] ]] ]
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

    public function getArchiveProjectSummary($project_id)
    {

        $data = Archive::raw(function($collection) use($project_id) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'id' => $project_id
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => 1,
                        'project_code' => 1,
                        'name' => 1,
                        'survey_id' => 1,
                        'client_code' => 1,
                        'loi' => 1,
                        'cpi' => 1,
                        'created_by' => 1,
                        'study_type' => 1,
                        'country_id' => 1,
                        'country_code' => 1,
                        'starts' => ['$size' => '$traffics'],
                        'completes' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 1] ]] ]
                        ],
                        'terminates' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 2] ]] ]
                        ],
                        'quotafull' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 3] ]] ]
                        ],
                        'quality_terminate' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 4] ]] ]
                        ],
                        'abandons' => ['$size' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 0] ]] ]
                        ],
                        'traffics' =>
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$gt' => ['$$item.duration', 0] ]] ]
                    ]
                ]
            ]);

        })->first();

        return $data;
    }




    public function getArchive($country,$study,$project_manager)
    {
        $query = "";
        $country = array_filter($country);
        $study = array_filter($study);
        $project_manager = array_filter($project_manager);
        $data = Archive::all();
        if($project_manager){
            $pm_name = User::select('first_name','last_name')->where('id',$project_manager)->first();
            $pm_name = $pm_name->first_name." ".$pm_name->last_name;
        }
        $filterdata = [];

        if($country){
            $filterdata['country_id'] = [
                '$in' => array_map('intval',$country),
            ];
        }
        else if($project_manager){
            $filterdata['created_by'] = $pm_name;
        }

        else if($study){
            $filterdata['study_type'] = [
                '$in' => array_map('intval',$study),
            ];
        }
        else{
            return  $this->getArchivesWithStats();
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
                        'project_code' => 1,
                        'name' => 1,
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
                            [ '$filter' => ['input' => '$traffics', 'as' => 'item', 'cond' => [ '$eq' => ['$$item.status', 4] ]] ]
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

        //DB::query()->whereIn()
//DB::connection()->enableQueryLog();

        //dd(DB::getQueryLog());
        return $filterResult;
    }

    public function getAllFilterableData()
    {
        $countries = Country::all()
            ->where('is_filterable', '=', '1');
        $study_type = StudyType::all()
            ->where('status', '=', 1);
        $project_manager = User::all()
            ->where('deleted_at', '=', null)
            ->where('confirmed', '=', 1);

        return $filter_elements = [
            'countries' => $countries,
            'study_types' => $study_type,
            'project_managers' => $project_manager,
        ];
    }

    public function getArchiveDetails($archive_id)
    {
        $data = $this->getArchiveProjectSummary((int)($archive_id));
        return $data;
    }

    public function createArchive($archive)
    {
        $data = Archive::create($archive);
        return $data;
    }
    public function getArchiveDetail($archive_id)
    {
        $data = Archive::where('id', '=', $archive_id)->first()->toArray();
        return $data;
    }
    public function createClone($data)
    {
        $clone = Archive::insert($data);
        return $clone;
    }
    public function createProject($project_details)
    {
        if($project_details){
            $project_details['status_label'] = config('app.project_statuses.tbd.label','TBD');
            $project_status = ProjectStatus::where('code','=',config('app.project_statuses.tbd.label'))->first();
            $project_details['status_id'] = $project_status->id;
            $create_project = Project::create($project_details);
            return $create_project;
        }
    }
    public function createProjectVendors($project_vendor)
    {
        if($project_vendor){
            $create_project_vendor = ProjectVendor::create($project_vendor);
            return $create_project_vendor;
        }
    }
    public function createProjectQuota($quota)
    {
            $create_quota= ProjectQuota::create($quota);
            return $create_quota;
    }
    public function createProjectDedupe($project_dedupe)
    {
            $create_project_quota = ProjectDedupe::create($project_dedupe);
            return $create_project_quota;
    }
    public function createProjectCustomScreener($project_custom_screener)
    {
            $create_project_screener = ProjectCustomScreener::create($project_custom_screener);
            return $create_project_screener;
    }
}
