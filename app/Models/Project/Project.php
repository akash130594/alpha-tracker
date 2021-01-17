<?php

namespace App\Models\Project;

use App\Models\Client\Client;
use App\Models\Auth\User;
use App\Models\Source\Source;
use Illuminate\Database\Eloquent\Model;
use App\Models\Project\Traits\ProjectUserStamp;
use App\Models\Traffics\Traffic;
use App\Repositories\Internal\Traffic\TrafficRepository;
class Project extends Model
{
    use ProjectUserStamp;

    /*TODO : Add Management Interface for Surveys*/
    protected $fillable = [
        'id',
        'code',
        'name',
        'label',
        'study_type_id',
        'project_topic_id',
        'collects_pii',
        'client_id',
        'client_code',
        'client_name',
        'client_var',
        'client_link',
        'client_project_no',
        'unique_ids_flag',
        'unique_ids_file',
        'can_links',
        'country_id',
        'country_code',
        'language_id',
        'language_code',
        'created_by',
        'updated_by',
        'ir',
        'loi',
        'loi_validation',
        'loi_validation_time',
        'client_screener_redirect_flag',
        'unique_parameters',
        'client_screener_redirect_data',
        'cpi',
        'incentive',
        'quota',
        'survey_dedupe_flag',
        'survey_dedupe_list_id',
        'status_id',
        'status_label',
        'end_date',
        'start_date',

    ];

    protected $dates = ['end_date','start_date'];
    public $timestamps = true;

    /**
     * Returns truncated name for the datatables.
     *
     * @return string
     */
    /*public function laratablesStatus()
    {
        return ($this->status)?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>';
    }*/

    /**
     * Returns truncated name for the datatables.
     *
     * @return string
     */
    /*public function laratablesSecurityFlag()
    {
        return ($this->security_flag)?'<i class="fas fa-check"></i>':'<i class="fas fa-times"></i>';
    }*/

    public static function laratablesCustomAction($project)
    {
        return view('internal.project.includes.index_action', compact('project'))
            ->render();
    }

    public static function laratablesCustomStarts($project)
    {
        return TrafficRepository::getStartsByProjectId($project->id);
    }
    public static function laratablesCustomCompletes($project)
    {
        return TrafficRepository::getCompletesByProjectId($project->id);
    }
    public static function laratablesCustomTerminates($project)
    {
        return TrafficRepository::getTerminatesByProjectId($project->id);
    }
    public static function laratablesCustomQuotafulls($project)
    {
        return TrafficRepository::getQuotafullByProjectId($project->id);
    }
    public static function laratablesCustomAbandons($project)
    {
        return TrafficRepository::getAbandonsByProjectId($project->id);
    }
    public static function laratablesCustomAbandonPercentage($project)
    {
        return TrafficRepository::getAbandonPercentage($project->id);
    }
    public static function laratablesCustomCcr($project)
    {
        return TrafficRepository::getCCRPercentage($project->id);
    }
    public static function laratablesCustomQualityTerminate($project)
    {
        return TrafficRepository::getQualityTerminate($project->id);
    }
    public static function laratablesCustomLoi($project)
    {
        return TrafficRepository::getAverageLOI($project->id);
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['status_id'];
    }

    public function vendorType()
    {
        return $this->belongsTo(ProjectSurvey::class, 'vendor_id', 'id');
    }
    public function traffics()
    {
        return $this->hasMany(Traffic::class, 'project_id', 'id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'code', 'client_code');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * @param $sourceCode : Source Model code attribute
     */
    public function hasSource($sourceCode)
    {
        return ProjectVendor::where('project_id', '=', $this->id)
            ->where('vendor_code', '=', $sourceCode)
            ->first();
    }

    public function getEndpageLinks($options = null)
    {
        $baseUrl = setting('router.domain');
        $action = setting('router.end_page');
        $sj_identifier = 'amrid=<%amrid%>';

        $end_params = [
            'complete' => [
                'status' => 1,
            ],
            'terminate' => [
                'status' => 2,
            ],
            'quotafull' => [
                'status' => 3,
            ],
            'quality' => [
                'status' => 4,
            ]
        ];

        if ( !empty($this->unique_parameters) && $uniqueParams = json_decode( $this->unique_parameters, true )) {
            $end_params['complete'] = array_merge($end_params['complete'], $uniqueParams['complete']);
            $end_params['terminate'] = array_merge($end_params['terminate'], $uniqueParams['terminate']);
            $end_params['quotafull'] = array_merge($end_params['quotafull'], $uniqueParams['quotafull']);
            $end_params['quality'] = array_merge($end_params['quality'], $uniqueParams['quality']);
        }

        $endpages = [
            'complete' => $baseUrl.'/'.$action.'?'.http_build_query($end_params['complete']).'&'.$sj_identifier,
            'terminate' => $baseUrl.'/'.$action.'?'.http_build_query($end_params['terminate']).'&'.$sj_identifier,
            'quotafull' => $baseUrl.'/'.$action.'?'.http_build_query($end_params['quotafull']).'&'.$sj_identifier,
            'quality' => $baseUrl.'/'.$action.'?'.http_build_query($end_params['quality']).'&'.$sj_identifier,
        ];

        return $endpages;
    }

}
