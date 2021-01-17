<?php

namespace App\Models\Project;

use App\Models\Source\Source;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProjectSurvey extends Model
{
    /*TODO : Add Management Interface for Project Surveys*/
    protected $fillable = [
        'id',
        'code',
        'project_vendor_id',
        'project_id',
        'project_code',
        'vendor_id',
        'vendor_code',
        'vendor_survey_code',
        'survey_live_url',
        'survey_test_url',
        'sy_excl_link_flag',
        'syv_complete',
        'syv_terminate',
        'syv_quotafull',
        'syv_qualityterm',
        'syv_other_url',
        'collection_dedupe',
        'collection_ids',
        'dedupe_status',
        'status_id',
        'status_label',
    ];


    public $timestamps = true;
    public function source()
    {
        return $this->hasOne(Source::class, 'id', 'vendor_id');
    }
    public function status()
    {
        return $this->hasOne(ProjectStatus::class, 'id', 'status_id');
    }

    public function generateSurveyLiveLink($options = null)
    {
        $baseUrl = setting('router.domain');
        $action = setting('router.start_page');

        $link_id = [
            $this->project_code,
            $this->code,
            $this->vendor_code
        ];
        $variables = $this->source()->where('id',$this->vendor_id)->select('vvars')->first();
        $vvars = explode(",",$variables->vvars);
        $queryString = [
            'version' => 'v2',
            'mode' => 1,
            'linkid' => implode('-', $link_id),
        ];
        foreach ($vvars as $variables){
            $vvar = $variables;
            $variable[$vvar] = '[' . '%' . "$variables" . '%' . ']';
            if( !empty($options) && !empty($options['replace_vars']) ){
                $variable[$vvar] = Str::random(20);
            }
        }
        $finalUrl = $baseUrl . '/' . $action . '?' . http_build_query($queryString) .'&'. urldecode(http_build_query($variable));
        if( !empty($options) && !empty($options['autoclose']) ){
            $finalUrl.= '&autoclose=1';
        }

        return $finalUrl;
    }

    public function generateSurveyTestLink()
    {
        $baseUrl = setting('router.domain');
        $action = setting('router.start_page');
        $variables = $this->source()->where('id',$this->vendor_id)->select('vvars')->first();
        $vvars = explode(",",$variables->vvars);
        $link_id = [
            $this->project_code,
            $this->code,
            $this->vendor_code
        ];

        $queryString = [
            'version' => 'v2',
            'mode' => 0,
            'linkid' => implode('-', $link_id),
        ];
        foreach ($vvars as $variables){
            $vvar = $variables;
            /* $new_vvar[] = "$vvar=>[$variables]";*/
            $variable[$vvar] = '[' . '%' . "$variables" . '%' . ']';
        }

        return $baseUrl . '/' . $action . '?' . http_build_query($queryString) .'&'. urldecode(http_build_query($variable));
    }

}
