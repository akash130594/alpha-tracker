<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'salary',
        'doj',
        'position',
        'dob',
        'empid',
        'mobile_no',
        'status',
        'email'
    ];
}
