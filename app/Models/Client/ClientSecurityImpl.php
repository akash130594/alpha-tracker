<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;

class ClientSecurityImpl extends Model
{
    protected $fillable = [
        'id',
        'client_id',
        'security_type_id',
        'security_type_code',
        'method_data',
        'status',
    ];

    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function securityType()
    {
        return $this->belongsTo(ClientSecurityType::class, 'security_type_id', 'id');
    }


}
