<?php

namespace App\Models\Traits;

trait RecordUserStamp
{
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($model) {

            $model->updated_by = auth()->user()->id;
        });

        static::creating(function ($model) {

            $model->created_by = auth()->user()->id;
        });
        //etc

    }

}
