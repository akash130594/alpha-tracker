<?php

namespace App\Repositories\Internal\Project;


use App\Events\Internal\Project\AfterStatusChanged;
use App\Events\Internal\Project\BeforeStatusChange;
use App\Models\Project\ProjectStatus;

class ProjectStatusRepository
{
    public function getStatusById($status_id)
    {
        $status = ProjectStatus::find($status_id);
        return $status;
    }

    public function getStatusByCode($status_code)
    {
        $status = ProjectStatus::where('code', '=', $status_code)->first();
        return $status;
    }

    public function prepareProjectForStatusChange($project, $currentStatusObj, $nextStatusObject)
    {
        event(new BeforeStatusChange($project, $nextStatusObject, $currentStatusObj));
    }

    public function changeProjectStatus($project, $nextStatusObject)
    {
        $data = [
            'status_id' => $nextStatusObject->id,
            'status_label' => $nextStatusObject->name,
        ];
        $status = $project->update($data);
        return ($status)?$project:false;
    }
    public function notifyForProjectStatusChanged($project, $previousStatusObj, $currentStatusObject)
    {
        event(new AfterStatusChanged($project, $previousStatusObj, $currentStatusObject));
    }

}
