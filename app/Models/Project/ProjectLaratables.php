<?php


/**
 * Created by PhpStorm.
 * User: SampleJunction
 * Date: 29-12-2018
 * Time: 05:33 PM
 */

namespace App\Models\Project;


use App\Repositories\Internal\General\GeneralRepository;

class ProjectLaratables
{

    public $general_repo;
    public function __construct(GeneralRepository $general_repo)
    {
        $this->general_repo = $general_repo;
        //$this->detailedProfileRepo = $detailedProfileRepo;
    }
}
