<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /*TODO : Add Management Interface for Countries*/
    protected $fillable = [
        'id',
        'country_code',
        'name',
        'capital',
        'iso_3166_2',
        'iso_3166_3',
        'currency_code',
        'currency_symbol',
        'currency_decimals',
        'citizenship',
        'calling_code',
        'flag',
        'default_locale',
        'language',
        'status',
        'is_filterable'
    ];

    public $timestamps = false;

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

    public static function laratablesCustomAction($country)
    {
        return view('internal.general.country.includes.index_action', compact('country'))
            ->render();
    }




}
