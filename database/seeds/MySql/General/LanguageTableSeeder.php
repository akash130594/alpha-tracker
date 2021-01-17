<?php

use Illuminate\Database\Seeder;
use App\Models\General\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/languages.json'), true);
        foreach($jsonData as $language){
            Language::create([
                'code'=> (!empty($language['code']))?$language['code']:'',
                'name' => (!empty($language['name']))?$language['name']:'',
            ]);
        }
    }
}
