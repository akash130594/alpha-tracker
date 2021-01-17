<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientSecurityType extends Model
{
    protected $fillable = [
        'id',
        'code',
        'name',
        'field_data',
    ];

    public $timestamps = false;

    public static function laratablesCustomAction($client_security)
    {
        return view('internal.client.client_security.includes.index_action', compact('client_security'))
            ->render();
    }
}
