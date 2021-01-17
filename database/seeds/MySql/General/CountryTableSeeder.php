<?php

use Illuminate\Database\Seeder;
use App\Models\General\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/country.json'), true);
        $filterable_country = ['US','UK','DE','FR','CA','IT','ES', 'CN', 'GB', 'JP', 'AU', 'NZ'];
        foreach($jsonData as $country){
            Country::create([
                'country_code'=> (!empty($country['iso_3166_2']))?$country['iso_3166_2']:'',
                'name' => (!empty($country['name']))?$country['name']:'',
                'capital' => (!empty($country['capital']))?$country['capital']:'',
                'iso_3166_2'=> (!empty($country['iso_3166_2']))?$country['iso_3166_2']:'',
                'iso_3166_3'=> (!empty($country['iso_3166_3']))?$country['iso_3166_3']:'',
                'currency_code'=> (!empty($country['currency_code']))?$country['currency_code']:'',
                'currency_symbol'=> (!empty($country['currency_symbol']))?$country['currency_symbol']:'',
                'currency_decimals'=> (!empty($country['currency_decimals']))?$country['currency_decimals']:0,
                'citizenship' => (!empty($country['citizenship']))?$country['citizenship']:'',
                'calling_code'=> (!empty($country['calling_code']))?$country['calling_code']:'',
                'flag'=> (!empty($country['flag']))?$country['flag']:'',
                'language'=> (!empty($country['language']))?$country['language']:'',
                'is_filterable' => (in_array($country['iso_3166_2'], $filterable_country))?true:false,
            ]);
        }
    }
}
