<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class StudyType extends Model
{
    /*TODO : Add Management Interface for Study Types*/
    /*While MIgration we have to take care of New IDS*/
    protected $fillable = [
        'id',
        'code',
        'name',
        'description',
        'status',
        'order'
    ];

    public $timestamps = false;
    public function laratablesStatus()
    {
        return ($this->status)?'<span class="material-icons">done</span>':'<span class="material-icons">cancel</span>';
    }

    public static function laratablesCustomAction($study_type)
    {
        return view('internal.general.study_types.includes.index_action', compact('study_type'))
            ->render();
    }
}
