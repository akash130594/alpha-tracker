<?php
/*******************************************************************************
 * Copyright (c) 2018. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
 * Morbi non lorem porttitor neque feugiat blandit. Ut vitae ipsum eget quam lacinia accumsan.
 * Etiam sed turpis ac ipsum condimentum fringilla. Maecenas magna.
 * Proin dapibus sapien vel ante. Aliquam erat volutpat. Pellentesque sagittis ligula eget metus.
 * Vestibulum commodo. Ut rhoncus gravida arcu.
 ******************************************************************************/

namespace App\Models\Sjpanel;

use Illuminate\Database\Eloquent\Model;

class ProfileQuestion extends Model
{

    protected $connection = 'mysql_sjpanel';

    protected $fillable = [
        'profile_section_id',
        'general_name',
        'display_name',
        'meta_tag',
        'display_option_order',
        'visibility',
        'status',
        'order',
        'dependency',
        'country_id',
        'country_code',
    ];

    public $translatedAttributes = ['name','label','hint'];

    public $timestamps = false;
}
