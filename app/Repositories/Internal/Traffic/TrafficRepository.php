<?php

/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 30-01-2019
 * Time: 04:26 PM
 */

namespace App\Repositories\Internal\Traffic;

use App\Models\Archive\Archive;
use App\Models\Traffics\Traffic;
use App\Repositories\BaseMongoRepository;
use Carbon\Carbon;
use DateTime;
use MongoDB\BSON\UTCDateTime;


class TrafficRepository extends BaseMongoRepository
{
    protected function getTrafficCollection()
    {
        return $this->getCollection('traffics');
    }

    public function getTrafficsStats($projects)
    {
        $project_ids = $projects->pluck('id')->toArray();
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
                        /*'loi' => [
                            '$ceil' => '$loi',
                        ],*/
                    ]
                ]
            ]);
        });
        /*https://gist.github.com/pankaj-sj/3de8adc4c1ad14288ff362149554157d*/
        //dd($data);
        return $data;
    }

    public static function getStatsByProjectId($project_id)
    {
        $data = Traffic::raw(function($collection) use ($project_id) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => (int)$project_id,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        $data = $data->first();
        if (empty($data)) {
            $data = new \stdClass();
            $data->starts = 0;
            $data->completes = 0;
            $data->terminates = 0;
            $data->quotafull = 0;
            $data->abandons = 0;
        }

        return $data;
    }
    public static function getStatsByProjectIdAndVendorId($project_id, $vendorId)
    {
        $data = Traffic::raw(function($collection) use ($project_id, $vendorId) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => (int)$project_id,
                        'project_vendor_id' => (int)$vendorId,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        $data = $data->first();
        if (empty($data)) {
            $data = new \stdClass();
            $data->starts = 0;
            $data->completes = 0;
            $data->terminates = 0;
            $data->quotafull = 0;
            $data->abandons = 0;
        }

        return $data;
    }

    public static function getStatsByProjectIdAndSourceId($project_id, $sourceId)
    {
        $data = Traffic::raw(function($collection) use ($project_id, $sourceId) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => (int)$project_id,
                        'source_id' => (int)$sourceId,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        $data = $data->first();
        if (empty($data)) {
            $data = new \stdClass();
            $data->starts = 0;
            $data->completes = 0;
            $data->terminates = 0;
            $data->quality_terminate = 0;
            $data->quotafull = 0;
            $data->abandons = 0;
        }

        return $data;
    }

    public static function getStartsByProjectId($project_id)
    {
        $starts = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->count();
        return $starts;
    }
    public static function getCompletesByProjectId($project_id)
    {
        $completes = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->where('status', '=', 1)
            ->count();
        return $completes;
    }
    public static function getTerminatesByProjectId($project_id)
    {
        $terminates = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->where('status', '=', 2)
            ->count();
        return $terminates;
    }
    public static function getQuotafullByProjectId($project_id)
    {
        $quotafulls = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->where('status', '=', 3)
            ->count();
        return $quotafulls;
    }
    public static function getAbandonsByProjectId($project_id)
    {
        $abandons = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->where('status', '=', 0)
            ->count();
        return $abandons;
    }

    public static function getAbandonPercentage($project_id)
    {
       $start = Traffic::where('project_id','=',$project_id)
           ->where('mode','=','1')
           ->count();
       $abandon = Traffic::where('project_id', '=', $project_id)
           ->where('mode', '=', "1")
           ->where('status', '=', 0)
           ->count();
       try{
           $abandon_percentage = ($abandon / $start) *100;
       } catch(\Exception $exception) {
           $abandon_percentage = 0;
       }
        return $abandon_percentage;
    }

    public static function getQualityTerminate($project_id)
    {
        $qualty_terminate = Traffic::where('project_id','=',$project_id)
            ->where('mode','=','1')
            ->where('status','=',4)
            ->count();
        return $qualty_terminate;
    }

    public static function getCCRPercentage($project_id)
    {
        $start = Traffic::where('project_id','=',$project_id)
            ->where('mode','=','1')
            ->count();
        $completes = Traffic::where('project_id', '=', $project_id)
            ->where('mode', '=', "1")
            ->where('status', '=', 1)
            ->count();
        try{
            $completes_percentage = ($completes / $start) *100;
        } catch(\Exception $exception) {
            $completes_percentage = 0;
        }
        return $completes_percentage;
    }

    public static function getAverageLOI($project_id)
    {
        $averageLoi = Traffic::where('project_id','=',$project_id)
            ->where('mode','=','1')
            ->avg('duration');
        if ($averageLoi) {
            return ceil($averageLoi);
        }
        return 0;
    }

    public function getTrafficsSelectedColumns($project_id)
    {
        /*$project_ids = $projects->pluck('id')->toArray();*/
        //$traffic = Traffic::all();
        $data = Traffic::raw(function($collection) use ($project_id) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => [
                          '$in' => array_map('intval',$project_id),
                        ],
                        'mode' => "1",
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
                        'abandons'=> [
                            '$sum' => [
                                '$cond' => [
                                    [ '$eq' => [ '$status', 0 ] ],1,0
                                ],
                            ]
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        /*https://gist.github.com/pankaj-sj/3de8adc4c1ad14288ff362149554157d*/
        return $data;

    }

    public function getTrafficDetails($project_id)
    {
       $data = Traffic::where('project_id',(int)$project_id)->get();
        return $data;
    }

    public function getStatsByProjectIdAndQuotaId($project_id, $quotaId)
    {
        $data = Traffic::raw(function($collection) use ($project_id, $quotaId) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => (int) $project_id,
                        'quota_id' => (int) $quotaId,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        $data = $data->first();
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
    public function getTrafficsDetails($project_id,$column_selected)
    {
        $query = "";
        $data = Traffic::where(function ($query) use($project_id,$column_selected){
            if(!empty($project_id) && !empty($column_selected))
                $query->select($column_selected)->where('project_id','=',(int)$project_id);
            else if(!empty($project_id) && empty($column_selected))
                $query->where('project_id','=',(int)$project_id);
        })->get();
        return $data;
    }


    public function getTrafficsDetailsCsv($project_ids,$column_selected)
    {
        $query = "";
        $data = Traffic::where(function ($query) use($project_ids,$column_selected){
            if(!empty($project_ids) && !empty($column_selected))
                $query->select($column_selected)->whereIn('project_id',array_map('intval',$project_ids));
            else if(!empty($project_ids) && empty($column_selected))
                $query->whereIn('project_id',array_map('intval',$project_ids));
        })->get();
        return $data;
    }

    public function getAllTraffic($id,$column_selected)
    {
        $data = Traffic::whereIn('id',$id)->get();
        return $data;
    }

    public function getTrafficData()
    {
      $present_date = new \MongoDB\BSON\UTCDateTime(new DateTime());
      $begin_date = new \MongoDB\BSON\UTCDateTime(new DateTime(date("Y-m-d", mktime(0,0,0))));
        $data = Traffic::raw(function($collection) use($present_date,$begin_date){
            return $collection->aggregate([
                [
                    '$match' => [
                        'started_at' => [
                         '$gte' =>  $begin_date,
                         '$lt' =>   $present_date,
                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => 0,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });
        return $data;
    }

    public function getVendorDailyStats($vendor_details)
    {
        $present_date = new \MongoDB\BSON\UTCDateTime(new DateTime());
        $begin_date = new \MongoDB\BSON\UTCDateTime(new DateTime(date("Y-m-d", mktime(0,0,0))));
        $data = Traffic::raw(function($collection) use($present_date,$begin_date,$vendor_details){
            return $collection->aggregate([
                [
                    '$match' => [
                        'started_at' => [
                            '$gte' =>  $begin_date,
                            '$lt' =>   $present_date,
                        ],
                        'source_id' => $vendor_details->id,
                    ]
                ],
                [
                    '$group' => [
                        '_id' => 0,
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
                        'abandons' => 1
                    ]
                ]
            ]);
        });

        return $data;
    }

    public function getHourlyStats()
    {
        $present_time = new \MongoDB\BSON\UTCDateTime(new DateTime());
        $hours_ago = new \MongoDB\BSON\UTCDateTime(strtotime('-1 hour')*1000);
        $data = Traffic::raw(function($collection) use($present_time,$hours_ago){
            return $collection->aggregate([
                [
                    '$match' => [
                        'started_at' => [
                            '$gte' =>  $hours_ago,
                            '$lt' =>   $present_time,
                        ],
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$started_at',
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
                        'started_at' => 1
                    ]
                ]
            ]);
        });
        return $data;
    }
}
