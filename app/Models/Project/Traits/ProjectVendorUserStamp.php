<?php

namespace App\Models\Project\Traits;

use App\Models\Project\Project;

trait ProjectVendorUserStamp
{
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {

            $project = Project::find($model->project_id);
            $project->updated_by = auth()->user()->id;
            $project->save();
        });

        static::creating(function ($model) {

            /*$project = Project::find($model->project_id);
            $project->created_by = auth()->user()->id;
            $project->save();*/
            /*Todo: Creation Logic don't work for now, cause we are deleting records and then creating them*/
        });
        //etc

    }

}
