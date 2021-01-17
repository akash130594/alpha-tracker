<?php

use App\Models\Client\Client;
use App\Models\Client\ClientSecurityType;
use Illuminate\Database\Seeder;
use App\Models\Client\ClientSecurityImpl;


class ClientSecurityImplTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $client_security_data = [
            'client_specific_validations' => [
                3	=> array(
                    'name' => 'BRND',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'7B97DBEE',
                    'query_parameter'=>'checksum',
                    'exclude_checksum_var'=> false,
                    'exclude_static_url'=> true,
                    'output_type'=> 'hex',
                    'base_url' => 'http://samppoint.com'.'/end/',
                ),
                11	=> array(
                    'name' => 'RFGC',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'md5',
                    'secret_key'=>'kptu43jja6af1e0p2lp9qib65s',
                    'query_parameter'=>'checksum',
                    'exclude_checksum_var'=> true,
                    'exclude_static_url'=> true,
                    'output_type'=> 'hex',
                ),
                31	=> array(
                    'name' => 'MCUB',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'898117F9-623D-4B1E-9047-37A3545FEF74',
                    'query_parameter'=>'checksum',
                    'exclude_checksum_var'=> false,
                    'exclude_static_url'=> true,
                    'output_type'=> 'base64',
                    'base_url' => 'http://samppoint.com'.'/end/',
                ),
                17	=> array(
                    'name' => 'CMIX',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'L6OITZHG64O5LO5IL9EROL4Z7O7TTUYR',
                    'query_parameter'=>'hash',
                    'exclude_checksum_var'=> true,
                    'exclude_static_url'=> true,
                    'output_type'=> 'hex',
                ),
                29	=> array(
                    'name' => 'APSI',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'4ST8KPEY77',
                    'query_parameter'=>'checksum',
                    'exclude_checksum_var'=> true,
                    'exclude_static_url'=> true,
                    'output_type'=> 'hex',
                    'base_url' => env('APP_URL','https://samppoint.com').'/end/',
                ),
                42	=> array(
                    'name' => 'RRPL',
                    'status' => true,
                    'method'=>'pixel',
                    'referer_check'=> true,
                    'referer_keyword'=> 'robas',
                ),
                21	=> array(
                    'name' => 'TOLU',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                16	=> array(
                    'name' => 'IRES',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                30	=> array(
                    'name' => 'GLBS',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                37	=> array(
                    'name' => 'BLDI',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                43	=> array(
                    'name' => 'OP4G',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                46	=> array(
                    'name' => 'PURE',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                47	=> array(
                    'name' => 'TACT',
                    'status' => true,
                    'method'=>'s2s',
                ),
                48	=> array(
                    'name' => 'ERSP',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                41	=> array(
                    'name' => 'EPIT',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                32	=> array(
                    'name' => 'WOML',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'EKrhPL2qHg',
                    'query_parameter'=>'hash',
                    'exclude_checksum_var'=> false,
                    'exclude_static_url'=> true,
                    'output_type'=> 'hex',
                    'base_url' => 'http://samppoint.com'.'/end/',
                ),
                20	=> array(
                    'name' => 'PNLD',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                52	=> array(
                    'name' => 'QMDS',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                23	=> array(
                    'name' => 'SPID',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                51	=> array(
                    'name' => 'PGRS',
                    'status' => true,
                    'method'=>'hash',
                    'algo'=>'sha1',
                    'secret_key'=>'XdX7wnaxTC',
                    'decode_url' => true,
                    'query_parameter'=>'checksum',
                    'exclude_checksum_var'=> false,
                    'exclude_static_url'=> true,
                    'output_type'=> 'base64',
                    'strip_special_chars'=> false,
                    'base_url' => env('APP_URL','https://samppoint.com').'/end/',
                ),
                14	=> array(
                    'name' => 'PRDM',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                40	=> array(
                    'name' => 'CYBR',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                38	=> array(
                    'name' => 'AKLC',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                57	=> array(
                    'name' => 'RMRR',
                    'status' => true,
                    'method'=>'unique_url',
                ),
                53	=> array(
                    'name' => 'SNRS',
                    'status' => true,
                    'method'=>'unique_url',
                ),
            ],
        ];

        foreach ($client_security_data['client_specific_validations'] as $key => $value){
            $client_code = $value['name'];
            $client_details = Client::where('code','=', $client_code)->first();
            if($value['method']=="hash"){
                $get_security_type = ClientSecurityType::where('code','=','HASH')->first();
                $field_data = json_decode($get_security_type->field_data,true);
                $security_data = [
                    "algo" => (!empty($value['algo']))?$value['algo']:'',
                    "decode_url" => (!empty($value['algo']))?$value['algo']:'',
                    "secret_key" => (!empty($value['secret_key']))?$value['secret_key']:'',
                    "query_parameter" => (!empty($value['query_parameter']))?$value['query_parameter']:'',
                    "exclude_checksum_var" => (!empty($value['exclude_checksum_var']))?$value['exclude_checksum_var']:'',
                    "exclude_static_url" => (!empty($value['exclude_static_url']))?$value['exclude_static_url']:'',
                    "static_url" => (!empty($value['base_url']))?$value['base_url']:'',
                    "output_type" => (!empty($value['output_type']))?$value['output_type']:'',
                    "strip_special_chars" => (!empty($value['strip_special_chars']))?$value['strip_special_chars']:'',
                ];
                $insert_data_json = json_encode($security_data);
                $insert_data = [
                    'client_id' => $client_details->id,
                    'security_type_id' => $get_security_type->id,
                    'security_type_code' => $get_security_type->code,
                    'method_data' => $insert_data_json,
                    'status' => 1
                ];
                ClientSecurityImpl::create($insert_data);
            } elseif($value['method']=="pixel"){
                $get_security_type = ClientSecurityType::where('code','=','PIXEL')->first();
                $field_data = json_decode($get_security_type->field_data,true);
                $security_data = [
                    "referrer_check" => (!empty($value['referer_check']))?$value['referer_check']:'',
                    "referral_keyword" => (!empty($value['referer_keyword']))?$value['referer_keyword']:'',
                ];
                $security_json = json_encode($security_data);
                $insert_data = [
                    'client_id' => $client_details->id,
                    'security_type_id' => $get_security_type->id,
                    'security_type_code' => $get_security_type->code,
                    'method_data' => $security_json,
                    'status' => 1
                ];
                ClientSecurityImpl::create($insert_data);
            } elseif($value['method']=="unique_url"){
                $get_security_type = ClientSecurityType::where('code','=','UNIQURL')->first();
                $field_data = json_decode($get_security_type->field_data,true);
                $security_data = [
                    "param_name" => (!empty($value['param_name']))?$value['param_name']:'',
                    "param_value" => (!empty($value['param_value']))?$value['param_value']:'',
                ];
                $security_json = json_encode($security_data);
                $insert_data = [
                    'client_id' => $client_details->id,
                    'security_type_id' => $get_security_type->id,
                    'security_type_code' => $get_security_type->code,
                    'method_data' => $security_json,
                    'status' => 1
                ];
                ClientSecurityImpl::create($insert_data);
            }
        }
        $get_client_security = ClientSecurityImpl::all();
        foreach($get_client_security as $security){
            $data[] = [
                'client_id' => $security->client_id,
                'security_type_id' => $security->security_type_id,
                'security_type_code' => $security->security_type_code,
                'method_data' => $security->method_data,
            ];
        }
        $json_data = json_encode($data);
        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR. "client_security.json", $json_data);
        $jsonData = json_decode(file_get_contents(__DIR__ . '/client_security.json'), true);
        foreach ($jsonData as $client_security_data){
            ClientSecurityImpl::create([
                'client_id' => (!empty($client_security_data['client_id']))?$client_security_data['client_id']:'',
                'security_type_id' => (!empty($client_security_data['security_type_id']))?$client_security_data['security_type_id']:'',
                'security_type_code' => (!empty($client_security_data['security_type_code']))?$client_security_data['security_type_code']:'',
                'method_data' => (!empty($client_security_data['method_data']))?$client_security_data['method_data']:'',
            ]);
        }
    }
}
