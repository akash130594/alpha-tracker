<?php

use Illuminate\Database\Seeder;
use App\Models\MasterQuestion\ProfileQuestions;
use App\Models\Profiler\ProfileSection;
use MongoDB\BSON\ObjectId;

class ProfileQuestionCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/Questions.json'), true);
        foreach($jsonData as $question){
            $profileCode = $question['profile_section_code'];
            $profile = ProfileSection::where('general_name', '=', $profileCode)->first();
            if( empty($profile) ){
                dd($profileCode);
            }
            $question['profile_section_id'] = new ObjectId($profile->_id);
            ProfileQuestions::create($question);
        }
    }
}
