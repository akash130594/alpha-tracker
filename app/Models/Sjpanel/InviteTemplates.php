<?php

namespace App\Models\Sjpanel;

use Illuminate\Database\Eloquent\Model;

class InviteTemplates extends Model
{
    protected $fillable = [
        'id',
        'name',
        'label',
        'description',
        'subject',
        'body',
        'image_url',
        'is_custom',
        'status',
        'order',
        ];
    public $timestamps = false;
}
