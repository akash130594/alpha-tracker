<?php

namespace App\Models\General;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    /*TODO : Add Management Interface for Countries*/
    protected $fillable = [
        'id',
        'code',
        'name',
        'status',
    ];

    public $timestamps = false;
    public function laratablesStatus()
    {
        return ($this->status)?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
    }

    public static function laratablesCustomAction($language)
    {
        return view('internal.general.language.includes.index_action', compact('language'))
            ->render();
    }
}
