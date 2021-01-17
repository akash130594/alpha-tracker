<?php

namespace App\Models\Profiler\Traits\Scope;

/**
 * Trait ProfileSectionScope.
 */
trait ProfileSectionScope
{
    /**
     * @param $query
     * @param bool $status
     *
     * @return mixed
     */
    public function scopeActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    /**
     * @param $query
     * @param bool $type
     *
     * @return mixed
     */
    public function scopePublic($query, $type = "public")
    {
        return $query->where('type', '=', $type);
    }
}
