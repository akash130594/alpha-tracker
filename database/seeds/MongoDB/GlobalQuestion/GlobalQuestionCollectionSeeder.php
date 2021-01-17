<?php

use Illuminate\Database\Seeder;
use App\Models\MasterQuestion\GlobalQuestion;

class GlobalQuestionCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/global_questions.json'), true);
        foreach($jsonData as $question){
            GlobalQuestion::create($question);
        }
    }
}
