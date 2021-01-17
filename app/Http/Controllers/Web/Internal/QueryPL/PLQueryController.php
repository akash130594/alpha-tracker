<?php

namespace App\Http\Controllers\Web\Internal\QueryPL;

use App\Models\Project\Project;
use App\Models\Project\ProjectSurvey;
use App\Models\Traffics\Traffic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PLQueryController extends Controller
{

    public $project_repo, $trafficRepo,$arch_repo, $projectSurveyRepo, $sourceRepo, $generalRepo;
   /* public function __construct(
        ProjectRepository $project_repository,
        GeneralRepository $generalRepo,
        TrafficRepository $trafficRepo,
        ArchivesRepository $archRepo,
        ProjectSurveyRepository $projectSurveyRepo,
        SourceRepository $sourceRepo
    )
    {
        $this->project_repo = $project_repository;
        $this->arch_repo = $archRepo;
        $this->generalRepo = $generalRepo;
        $this->trafficRepo = $trafficRepo;
        $this->projectSurveyRepo = $projectSurveyRepo;
        $this->sourceRepo = $sourceRepo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }*/

    public function index()
    {
        $projects = ProjectSurvey::whereRaw('SUBSTRING(project_code, 1,  4) = '.'1905')->get();
        $trafficStats = $this->getTrafficsStats($projects);
        dd($trafficStats);
        //dd($trafficStats);
        $projects = $projects->map(function ($value) use ($trafficStats)  {
            $value->traffic = $trafficStats->first(function($item) use ($value) {
                return $item->id == $value->id;
            });
            return $value;
        });
    }

    public function getTrafficsStats($projects)
    {
        $project_ids = $projects->pluck('id')->toArray();
        $data = Traffic::raw(function ($collection) use ($project_ids) {
            return $collection->aggregate([
                [
                    '$match' => [
                        'project_id' => [
                            '$in' => $project_ids
                        ],
                        'mode' => "1"
                    ]
                ],
                [
                    '$group' => [
                        '_id' => '$project_id',
                        'starts' => [
                            '$sum' => 1
                        ],
                        'completes' => [
                            '$sum' => [
                                '$cond' => [
                                    ['$eq' => ['$status', 1]], 1, 0
                                ],
                            ]
                        ],
                    ],
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => '$_id',
                        'starts' => 1,
                        'completes' => 1,
                        /*'loi' => [
                            '$ceil' => '$loi',
                        ],*/
                    ]
                ]
            ]);
        });
        dd($data);
    }
}
