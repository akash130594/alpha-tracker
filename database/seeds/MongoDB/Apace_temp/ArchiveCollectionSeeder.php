<?php

use Illuminate\Database\Seeder;
use  App\Models\Apace_temp\Archive;

class ArchiveCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/archive.json'), true);
        foreach($jsonData as $question){
            Archive::create($question);
        }
    }
}
