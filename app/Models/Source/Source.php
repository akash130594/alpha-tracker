<?php

namespace App\Models\Source;

use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    protected $fillable = [
        'id',
        'source_type_id',
        'code',
        'name',
        'email',
        'phone',
        'vvars',
        'complete_url',
        'terminate_url',
        'quotafull_url',
        'quality_term_url',
        'extra_url',
        'validation_status',
        'algo',
        'secret_key',
        'parameter_name',
        'global_screener',
        'defined_screener',
        'custom_screener',
        'pre_selected',
        'status',
        'is_api',
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
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['global_screener', 'defined_screener', 'custom_screener'];
    }

    /**
     * Returns truncated name for the datatables.
     *
     * @return string
     */
    public static function laratablesCustomScreener($client)
    {
        $result = '';
        $result .= ( !empty($client->global_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        $result.='&nbsp;&nbsp;';

        $result .= ( !empty($client->defined_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        $result.='&nbsp;&nbsp;';
        $result .= ( !empty($client->custom_screener) )?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
        return $result;
    }

    public static function laratablesCustomAction($source)
    {
        return view('internal.source.includes.index_action', compact('source'))
            ->render();
    }

    /*public static function laratablesSourceTypeRelationQuery($query)
    {
        $result =  function ($query) {
            $query->with('sourceType');
        };
        dd($result);
        return $result;
    }*/

    public function sourceType()
    {
        return $this->belongsTo(SourceType::class, 'source_type_id', 'id');
    }

}
