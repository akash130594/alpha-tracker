<?php

use Illuminate\Database\Seeder;
use  App\Models\Traffics\Traffic;
class TrafficCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/traffics.json'), true);
        foreach($jsonData as $question){
            Traffic::create($question);
        }
    }
}
