<?php

use Illuminate\Database\Seeder;
use App\Models\Project\StudyType;

class StudyTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = '[{"Code":"ADH","Id":"1","IsActive":true,"Name":"Adhoc","SortOrder":1},{"Code":"DIA","Id":"2","IsActive":true,"Name":"Diary","SortOrder":2},{"Code":"IHU","Id":"5","IsActive":true,"Name":"IHUT","SortOrder":5},{"Code":"REC - CLT","Id":"8","IsActive":true,"Name":"Community Build","SortOrder":8},{"Code":"REC - PAN","Id":"11","IsActive":true,"Name":"Recruit - Panel","SortOrder":11},{"Code":"TRAC","Id":"13","IsActive":true,"Name":"Tracker","SortOrder":13},{"Code":"WAV","Id":"16","IsActive":true,"Name":"Wave Study","SortOrder":16},{"Code":"IC","Id":"21","IsActive":true,"Name":"Incidence Check","SortOrder":21},{"Code":"RECON","Id":"22","IsActive":true,"Name":"Recontact","SortOrder":22},{"Code":"AER","Id":"23","IsActive":true,"Name":"Ad Effectiveness Research","SortOrder":23}]';
        $decoded = json_decode($data, true);

        foreach ($decoded as $studytype) {
            StudyType::create([
                'code' => $studytype['Code'],
                'name' => $studytype['Name'],
                'description' => $studytype['Name'],
                'order' => $studytype['SortOrder'],
            ]);
        }
    }
}
