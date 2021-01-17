<?php

use Illuminate\Database\Seeder;
use  App\Models\Apace_temp\ProjectUniqueId;

class ProjectUniqueIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/project_unique_ids.json'), true);
        foreach($jsonData as $question){
            ProjectUniqueId::create($question);
        }
    }
}
