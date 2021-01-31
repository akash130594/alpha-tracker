<?php

namespace App\Http\Controllers\Web\internal\DailyStats;

use App\Models\Source\Source;
use App\Repositories\Internal\Traffic\TrafficRepository;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MongoDB\BSON\UTCDateTime;




class DailyStatsController extends Controller
{
    /**
     * @var TrafficRepository
     * @param $trafficRepo
     * @param $time_zone
     */
    public $trafficRepo;
    public  $time_zone;

    /**
     * DailyStatsController constructor.
     * @param TrafficRepository $trafficRepo
     */
    public function __construct(TrafficRepository $trafficRepo)
    {
        $this->trafficRepo = $trafficRepo;
        /*$this->time_zone = date_default_timezone_set('Asia/Kolkata');*/
    }
/*********************************Daily Stats******************************************************************************/

    /**
     * This function is used to get all the monthly stats of all the vendor and export the data in csv.
     *
     */
    public function getDailyStats()
    {
        $get_daily_stats = $this->trafficRepo->getTrafficData();
        $final_stats = [];
        if(!$get_daily_stats->isEmpty()){
            $stats = [];
            $current_date = new \MongoDB\BSON\UTCDateTime(new DateTime(date("Y-m-d")));
            $current_date = $current_date->toDateTime();
                $stats[$current_date->format('Y-m-d')] = [
                    'stats' => $this->getStats($get_daily_stats[0]),
                ];
            $check_stats_file = file_exists(resource_path()."\stats\daily_stats.json");
            if($check_stats_file){
                $stats_file = file_get_contents(resource_path()."\stats\Fulcrum.json");
                $tempArray = json_decode($stats_file,true);
                $new_data = array_merge($tempArray, $stats);
                $jsonData = json_encode($new_data);
                file_put_contents(resource_path()."\stats\daily_stats.json", $jsonData);
                dd("done");
            } else{
                $json = json_encode($stats);
                file_put_contents(resource_path()."\stats\daily_stats.json", $json, FILE_APPEND);
                dd("done");
            }
        }
    }
/***********************************Daily Stats Finish******************************************************************************/


  /*********************************Vendor's Daily Stats****************************************************************************************************/

    /**
     * Get the monthly stats of Fulcrum and PL and export the data in csv file.
     *
     * @access public
     * @param Request $request
     */
    public function vendorPerDailyStats(Request $request)
    {
        $code = $request->source_code;
        $vendor_details = $this->getVendorDetails($code);
        $daily_stats = $this->trafficRepo->getVendorDailyStats($vendor_details);
        $this->makeVendorDailyStatsFile($daily_stats,$vendor_details);
    }

    /**
     * This function is used for getting vendor details
     *
     * @access private
     * @param $code
     * @return mixed
     */
    private function getVendorDetails($code)
    {
        $vendor_details = Source::where('code', '=', $code)->first();
        return $vendor_details ;
    }

    /**
     * This function is used make and export daily stats of fulcrum and PL.
     *
     * @access private
     * @param $get_daily_stats
     * @param $vendor_details
     */
    private function makeVendorDailyStatsFile($get_daily_stats,$vendor_details)
    {
        if(!$get_daily_stats->isEmpty()){
            $stats = [];
            $current_date = new \MongoDB\BSON\UTCDateTime(new DateTime(date("Y-m-d H:m:s")));
            $current_date = $current_date->toDateTime();
            $stats[$current_date->format('Y-m-d')] = [
                'stats' => $this->getStats($get_daily_stats[0]),
            ];
            $daily_stats_data[] = $stats;
            $check_stats_file = file_exists(resource_path() . "\stats"."\\" . "$vendor_details->name.json");
            if($check_stats_file){
                $stats_file = file_get_contents(resource_path() . "\stats"."\\" . "$vendor_details->name.json");
                $tempArray = json_decode($stats_file,true);
                $new_data = array_merge($tempArray, $stats);
                $jsonData = json_encode($new_data);
                file_put_contents(resource_path() . "\stats"."\\" . "$vendor_details->name.json", $jsonData);
                dd("done");
            } else {
                $json = json_encode($stats);
                file_put_contents(resource_path() . "\stats"."\\" . "$vendor_details->name.json", $json, FILE_APPEND);
                dd("done");
            }
        }
    }
/******************************Vendor's Daily Stats Finish********************************************************************************************/


/********************************Hourly Stats******************************************************************************************/

    /**
     * This function is used to get hourly stats of the traffic of all the vendors.
     * @access public
     */
    public function hourlyOverallStats()
    {
        $get_hourly_stats = $this->trafficRepo->getHourlyStats();
        $this->makeHourlyStatsFile($get_hourly_stats);
    }

    /**
     * This function is used for making and export of the hourly stats of all the vendors.
     *
     * @access private
     * @param $get_daily_stats
     */
    private function makeHourlyStatsFile($get_daily_stats)
    {
        if(!$get_daily_stats->isEmpty()){
            $stats = [];
            $current_date = new \MongoDB\BSON\UTCDateTime(new DateTime());
            $current_date = $current_date->toDateTime();
            $stats[$current_date->format('Y-m-d')] = [
                'stats' => $this->getStats($get_daily_stats[0]),
            ];
            $daily_stats_data[] = $stats;
            $check_stats_file = file_exists(resource_path()."\stats\hourly_stats.json");
            if($check_stats_file){
                $stats_file = file_get_contents(resource_path()."\stats\hourly_stats.json");
                $tempArray = json_decode($stats_file,true);
                $new_data = array_merge($tempArray, $stats);
                $jsonData = json_encode($new_data);
                file_put_contents(resource_path()."\stats\hourly_stats.json", $jsonData);
                dd("done");
            } else{
                $json = json_encode($stats);
                file_put_contents(resource_path()."\stats\hourly_stats.json", $json, FILE_APPEND);
                dd("done");
            }
        }
    }
/*******************************Hourly Stats Finish*****************************************************************************************/


/********************************Common Function For Making Array of Stats******************************************************************************************/

    /**
     * This function is used for making array that has to be written in export files
     *
     * @access private
     * @param $get_daily_stats
     * @return array
     */
    private function getStats($get_daily_stats)
    {
        $daily_stats[] = [
            'start' => $get_daily_stats->starts,
            'completes' => $get_daily_stats->completes,
            'terminates' => $get_daily_stats->terminates,
            'quality_terminate' => $get_daily_stats->quality_terminate,
            'quotafull' => $get_daily_stats->quotafull,
            'abandons' => $get_daily_stats->abandons,
        ];
        return $daily_stats;
    }
}
