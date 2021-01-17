<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectCustomScreener;

class ProjectCustomScreenerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first_json = '{"gender":{"type":"single","name":"gender","text":"What is your Gender","answer":{"1":{"text":"Male","action":"default_action","skip_to":"Choose Question"},"2":{"text":"Female","action":"default_action","skip_to":"Choose Question"},"3":{"text":"Other","action":"screen_out","skip_to":"Choose Question"}},"is_required":"true","order":"10"},"mobile_used":{"type":"single","name":"mobile_used","text":"Have you used phone yet?","answer":{"4":{"text":"Yes","action":"default_action","skip_to":"Choose Question"},"5":{"text":"No","action":"skip_action","skip_to":"honest_answer"}},"order":"10"},"brand_used":{"type":"multiple","name":"brand_used","text":"Which Mobile Phone Brand have you used?","answer":{"6":{"text":"Nokia","action":"default_action","skip_to":"Choose Question"},"7":{"text":"India","action":"screen_out","skip_to":"Choose Question"},"8":{"text":"Motorola","action":"default_action","skip_to":"Choose Question"},"9":{"text":"Apple","action":"default_action","skip_to":"Choose Question"},"10":{"text":"Others","action":"default_action","skip_to":"Choose Question"}},"order":"10"},"honest_answer":{"type":"message","name":"honest_answer","text":"Please provide your honest answer","answer":{"11":{"action":"default","skip_to":"Choose Question"}},"order":"10"}}';
        $array = [
            'project_id' => 1,
            'screener_json' => $first_json,
        ];
        ProjectCustomScreener::create($array);

    }
}
