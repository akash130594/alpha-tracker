<?php

namespace App\Console\Commands;

use App\Repositories\Internal\Traffic\TrafficRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyTrafficStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:traffic_update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Traffic Stats Update';

    public $trafficRepo;
    public  $time_zone;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TrafficRepository $trafficRepo)
    {
        parent::__construct();
        $this->trafficRepo = $trafficRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Daily Stats CRON EXECUTED');
        $get_daily_stats = $this->trafficRepo->getTrafficData();

        $dailyJsonPath = resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."daily_stats.json";

        if(!$get_daily_stats->isEmpty()){
            $stats = [];

            $current_date = new \MongoDB\BSON\UTCDateTime(new \DateTime(date("Y-m-d")));
            $current_date = $current_date->toDateTime();

            $stats[$current_date->format('Y-m-d')] = [
                'stats' => $this->getStats($get_daily_stats[0]),
            ];
            $check_stats_file = file_exists($dailyJsonPath);
            if( $check_stats_file ){
                $stats_file = file_get_contents($dailyJsonPath);
                $tempArray = json_decode($stats_file,true);
                $new_data = array_merge($tempArray, $stats);
                $jsonData = json_encode($new_data);
                file_put_contents($dailyJsonPath, $jsonData);
            } else{
                $json = json_encode($stats);
                file_put_contents($dailyJsonPath, $json, FILE_APPEND);
            }
            Log::debug('Daily Stats Generated');
            die();
        }
    }

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
