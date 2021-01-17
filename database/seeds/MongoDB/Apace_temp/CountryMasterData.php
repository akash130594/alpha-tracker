<?php

use App\Models\Web\Internal\MasterData;
use Illuminate\Database\Seeder;

class CountryMasterData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/master_country_data.json'), true);
        foreach($jsonData as $data){
            //dd($data['country_code']);
            MasterData::create([
                'country_code' => (!empty($data['country_code'])) ? $data['country_code'] : '',
                'country_name' => (!empty($data['country_name'])) ? $data['country_name'] : '',
                'country_data' => (!empty($data['country_data'])) ? $data['country_data'] : '',
            ]);
        }
    }
}
