<?php

namespace App\Library\Services\SourceAPI;


interface SourceAPIServiceInterface
{
    public function doSomethingUseful();

    public function createSurvey($project);

    public function updateSurvey();
    //public function createProject();
}
