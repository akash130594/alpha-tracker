<?php

use App\Helpers\General\Timezone;
use App\Helpers\General\HtmlHelper;

/*
 * Global helpers file with misc functions.
 */
if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}

if (! function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);
            while ($it->valid())
            {
                if (! $it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }
                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (! function_exists('home_route')) {

    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            } else {
                return 'internal.dashboard';
            }
        }

        return 'frontend.index';
    }
}

if (! function_exists('style')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (! function_exists('script')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null  $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}

if (! function_exists('form_cancel')) {

    /**
     * @param        $cancel_to
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_cancel($cancel_to, $title, $classes = 'btn btn-danger btn-sm')
    {
        return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
    }
}

if (! function_exists('form_submit')) {

    /**
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_submit($title, $classes = 'btn btn-success btn-sm pull-right')
    {
        return resolve(HtmlHelper::class)->formSubmit($title, $classes);
    }
}

if (! function_exists('camelcase_to_word')) {

    /**
     * @param $str
     *
     * @return string
     */
    function camelcase_to_word($str)
    {
        return implode(' ', preg_split('/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x', $str));
    }
}

/************************* Custom Screener Functions **************************/
if (! function_exists('cs_getQuestionTypeIcon')) {

    /**
     * @param $type
     *
     * @return string
     */
    function cs_getQuestionTypeIcon($type)
    {
        if ($type === 'multiple') {
            return '<i class="far fa-check-square"></i>';
        }else if ($type === 'single') {
            return '<i class="far fa-dot-circle"></i>';
        }else if ($type === 'message') {
            return '<i class="fas fa-quote-left"></i>';
        }else{
            return '';
        }
    }
}
if (! function_exists('cs_getQuestionTypeText')) {

    function cs_getQuestionTypeText($type)
    {
        if ($type === 'multiple') {
            return 'Multiple Selection';
        } else if ($type === 'single') {
            return 'Single Selection';
        } else if ($type === 'message') {
            return 'Message Box';
        } else {
            return '';
        }
    }
}

if (!function_exists('array_key_first')) {
    /**
     * Gets the first key of an array
     *
     * @param array $array
     * @return mixed
     */
    function array_key_first(array $array)
    {
        if (count($array)) {
            reset($array);
            return key($array);
        }

        return null;
    }
}

if (!function_exists('getGlobalAgeOptions')) {
    /**
     * Gets the first key of an array
     *
     * @param array $array
     * @return mixed
     */
    function getGlobalAgeOptions( $countryCode = null, $language_code = null )
    {
        $staticGroup = [
            [
                "id" => "13-17",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "13 - 17",
                "precode" => "13-17",
            ],
            [
                "id" => "18-24",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "18 - 24",
                "precode" => "18-24",
            ],
            [
                "id" => "25-34",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "25 - 34",
                "precode" => "25-34",
            ],
            [
                "id" => "35-44",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "35 - 44",
                "precode" => "35-44",
            ],
            [
                "id" => "45-54",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "45 - 54",
                "precode" => "45-54",
            ],
            [
                "id" => "55-64",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "55 - 64",
                "precode" => "55-64",
            ],
            [
                "id" => "65-100",
                "profile_question_id" => "GLOBAL_AGE",
                "display_name" => "65 +",
                "precode" => "65-100",
            ]
        ];
        return  $staticGroup;
    }
}

if (!function_exists('getGlobalZipcodeOptions')) {
    /**
     * Gets the first key of an array
     *
     * @param array $array
     * @return mixed
     */
    function getGlobalZipcodeOptions( $countryCode = null, $language_code = null )
    {
        $staticGroup = [

        ];
        return  $staticGroup;
    }
}

if (!function_exists('dates_month')) {
    function dates_month($month, $year)
    {
        //$num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $num = date("d");
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("Y-m-d", $mktime);
            $dates_month[] = $date;
        }
        return $dates_month;
    }

}

if (!function_exists('dates_stats')) {
    function dates_stats($dateRange)
    {
        $json_data = file_get_contents(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."daily_stats.json");
        $stats = json_decode( $json_data, true);
        $daily_stats = [];
        if (!$stats) {
            return $daily_stats;
        }
        $stats = collect($stats)->take(-abs(count($dateRange)));
        foreach($dateRange as $date){
            foreach($stats as $key=>$value){
                if($key==$date){
                    $status_details = array_get($value,'stats');
                    $daily_stats[$date] = $status_details[0];
                    break 1;
                } else{
                    $daily_stats[$date] = [
                        "start" => 0,
                          "completes" => 0,
                          "terminates" => 0,
                          "quality_terminate" => 0,
                          "quotafull" => 0,
                          "abandons" => 0,
                    ];
                }
            }
        }
        return $daily_stats;
    }
}


if (!function_exists('fulcrum_dates_stats')) {
    function fulcrum_dates_stats($months,$dateRange)
    {

        $file = file_exists(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."Fulcrum.json");

        if($file){
            $json_data = file_get_contents(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."Fulcrum.json");
            $stats = json_decode( $json_data, true);
            $daily_stats = [];
            if (!$stats) {
                return $daily_stats;
            }
            $stats = collect($stats)->take(-abs(count($dateRange)) );
            foreach($dateRange as $date){
                foreach($stats as $key=>$value){
                    if($key==$date){
                        $status_details = array_get($value,'stats');
                        $daily_stats[$date] = $status_details[0];
                        break 1;
                    } else{
                        $daily_stats[$date] = [
                            "start" => 0,
                            "completes" => 0,
                            "terminates" => 0,
                            "quality_terminate" => 0,
                            "quotafull" => 0,
                            "abandons" => 0,
                        ];
                    }
                }
            }
        } else{
            $daily_stats[] = [
                "start" => 0,
                "completes" => 0,
                "terminates" => 0,
                "quality_terminate" => 0,
                "quotafull" => 0,
                "abandons" => 0,
            ];
        }
        return $daily_stats;
    }

}

if (!function_exists('get_month_till_date')) {
    function get_month_till_date()
    {
        for ($m=1; $m<=date('m'); $m++) {
            $month[] = date('F', mktime(0,0,0,$m, 1));
        }
     return $month;
    }

}

if (!function_exists('month')) {
    function month($month, $year)
    {
        $num = date("d");
        $dates_month = array();

        for($j = 1;$j <= date("m"); $j++){
            $dateObj   = DateTime::createFromFormat('!m', $j);
            $monthName = $dateObj->format('F'); // March
            $month = date_parse($monthName);
            $days_per_month=cal_days_in_month(CAL_GREGORIAN,$month['month'],$year);
            if(date('m')==$month['month']){
                $days_per_month = date('d');
            }
            for ($i = 1; $i <= $days_per_month; $i++) {

                $mktime = mktime(0, 0, 0, $month['month'], $i, $year);
                $date = date("Y-m-d", $mktime);
                $dates_month[] = $date;
            }
        }

        return $dates_month;
    }

}

if(!function_exists('traffic_sum')){
    function traffic_sum($dateRange)
    {
        $total_stats = [];
        $daily_stats = [];
        $json_data = file_get_contents(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."daily_stats.json");
        $stats = json_decode( $json_data, true);
        if (!$stats) {
            return $daily_stats;
        }
        $stats = collect($stats)->take(-abs(count($dateRange)) );
        foreach($dateRange as $date){
            foreach($stats as $key=>$value){
                if($key==$date){
                    $status_details = array_get($value,'stats');
                    $daily_stats[$date] = $status_details[0];
                    break 1;
                } else{
                    $daily_stats[$date] = [
                        "start" => 0,
                        "completes" => 0,
                        "terminates" => 0,
                        "quality_terminate" => 0,
                        "quotafull" => 0,
                        "abandons" => 0,
                    ];
                }
            }
        }
        $start = array_sum(array_column($daily_stats,'start'));
        $completes = array_sum(array_column($daily_stats,'completes'));
        $terminates = array_sum(array_column($daily_stats,'terminates'));
        $quotafull = array_sum(array_column($daily_stats,'quotafull'));
        $quality_terminate = array_sum(array_column($daily_stats,'quality_terminate'));
        $abandons = array_sum(array_column($daily_stats,'abandons'));
        $total_stats['start'] = $start;
        $total_stats['completes'] = $completes;
        $total_stats['terminates'] = $terminates;
        $total_stats['quality_terminate'] = $quality_terminate;
        $total_stats['quotafull'] = $quotafull;
        $total_stats['abandons'] = $abandons;
        return $total_stats;
    }
}

if (!function_exists('pl_dates_stats')) {
    function pl_dates_stats($months,$dateRange)
    {

        $file = file_exists(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."Peanut Lab.json");
        if($file){
            $json_data = file_get_contents(resource_path().DIRECTORY_SEPARATOR."stats".DIRECTORY_SEPARATOR."Peanut Lab.json");
            $stats = json_decode( $json_data, true);
            $daily_stats = [];
            if (!$stats) {
                return $daily_stats;
            }
            $stats = collect($stats)->take(-abs(count($dateRange)) );
            foreach($dateRange as $date){
                foreach($stats as $key=>$value){
                    if($key==$date){
                        $status_details = array_get($value,'stats');
                        $daily_stats[$date] = $status_details[0];
                        break 1;
                    } else{
                        $daily_stats[$date] = [
                            "start" => 0,
                            "completes" => 0,
                            "terminates" => 0,
                            "quality_terminate" => 0,
                            "quotafull" => 0,
                            "abandons" => 0,
                        ];
                    }
                }
            }
        } else{
            $daily_stats[] = [
                "start" => 0,
                "completes" => 0,
                "terminates" => 0,
                "quality_terminate" => 0,
                "quotafull" => 0,
                "abandons" => 0,
            ];
    }
        return $daily_stats;
    }
}
if (!function_exists('getNameInitials')) {
    function getNameInitials($name) {

        preg_match_all('#(?<=\s|\b)\pL#u', $name, $res);
        $initials = implode('', $res[0]);

        if (strlen($initials) < 2) {
            $initials = strtoupper(substr($name, 0, 2));
        }

        return strtoupper($initials);
    }
}
