<?php

use App\Models\Client\Client;
use App\Models\Client\ClientSecurityImpl;
use App\Models\Client\ClientSecurityType;
use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder
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
        $client_name_security_impl = array_column($client_security_data['client_specific_validations'],'name');

            $jsonData = json_decode(file_get_contents(__DIR__ . '/client.json'), true);
        foreach ($jsonData as $client){
            if (in_array($client['code'], $client_name_security_impl)) {
                $client_security_flag = 1;
            } else{
                $client_security_flag = 0;
            }
            \App\Models\Client\Client::create([
                'code' => (!empty($client['code']))?$client['code']:'',
                'name' => (!empty($client['name']))?$client['name']:'',
                'email' => (!empty($client['email']))?$client['email']:'',
                'phone' => (!empty($client['phone']))?$client['phone']:'',
                'website' => (!empty($client['website']))?$client['website']:'',
                'cvars' => (!empty($client['cvars']))?$client['cvars']:'',
                'security_flag' => $client_security_flag,
            ]);
        }
    }
}
