<?php

namespace App\Repositories\Internal\Project;


use App\Models\Project\ProjectQuota;

class ProjectQuotaRepository
{
    public function getProjectQuotasbyProjectId($project_id)
    {
        $data = ProjectQuota::where('project_id', '=', $project_id)->get();

        return $data;
    }

    public function getProjectQualifications($project_id)
    {
        $projectQuotas = $this->getProjectQuotasbyProjectId($project_id);

        foreach ($projectQuotas as $quota) {
           return;
        }

        return false;
    }
}
