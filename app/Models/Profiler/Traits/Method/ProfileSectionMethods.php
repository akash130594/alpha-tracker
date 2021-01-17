<?php
/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 09-03-2019
 * Time: 06:41 PM
 */

namespace App\Models\Profiler\Traits\Method;


trait ProfileSectionMethods
{
    public function doTranslate()
    {
        if(!empty($this->translated)){
            return $this->translated[0];
        }
        return [];
    }
}
