<?php

use Illuminate\Database\Seeder;
use App\Models\Source\Source;

class SourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonData = json_decode(file_get_contents(__DIR__ . '/source.json'), true);
        foreach ($jsonData as $source){
              Source::create([
                'source_type_id' => (!empty($source['source_type_id']))?$source['source_type_id']:'',
                'code' =>  (!empty($source['code']))?$source['code']:'',
                'name' =>  (!empty($source['name']))?$source['name']:'',
                'email' =>  (!empty($source['email']))?$source['email']:'',
                'phone' =>  (!empty($source['phone']))?$source['phone']:'',
                'vvars' => (!empty($source['vvars']))?$source['vvars']:'',
                'complete_url' =>  (!empty($source['complete_url']))?$source['complete_url']:'',
                'terminate_url' => (!empty($source['terminate_url']))?$source['terminate_url']:'',
                'quotafull_url' => (!empty($source['quotafull_url']))?$source['quotafull_url']:'',
                'quality_term_url' => (!empty($source['quality_term_url']))?$source['quality_term_url']:'',
                //'extra_url',
                'validation_status' => (!empty($source['validation_status']))?$source['validation_status']:'',
                'algo' => (!empty($source['algo']))?$source['algo']:'',
                'secret_key' => (!empty($source['secret_key']))?$source['secret_key']:'',
                'parameter_name' => (!empty($source['parameter_name']))?$source['parameter_name']:'',
                'global_screener' => true,
                'defined_screener' => true,
                'custom_screener' => true,
                'pre_selected' => (!empty($source['pre_selected']))?$source['pre_selected']:'',
                'status' => (!empty($source['status']))?$source['status']:'',
                'is_api' => 0,
            ]);
        }
    }
}
