<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'id',
        'code',
        'name',
        'email',
        'phone',
        'website',
        'cvars',
        'additional_json_data',
        'setting_data',
        'security_flag',
        'redirector_flag',
        'redirector_screener_parameters',
        'redirector_survey_type_flag',
        'redirect_study_type_id',
        'status',
    ];

    public $timestamps = true;

    /**
     * Returns truncated name for the datatables.
     *
     * @return string
     */
    public function laratablesStatus()
    {
        return ($this->status)?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
    }

    /**
     * Returns truncated name for the datatables.
     *
     * @return string
     */
    public function laratablesSecurityFlag()
    {
        return ($this->security_flag)?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
    }

    public static function laratablesCustomAction($client)
    {
        return view('internal.client.includes.index_action', compact('client'))
            ->render();
    }

    public function securityImpl()
    {
        return $this->hasOne(ClientSecurityImpl::class, 'client_id', 'id');
    }
}
