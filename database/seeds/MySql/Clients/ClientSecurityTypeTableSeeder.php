<?php

use Illuminate\Database\Seeder;
use App\Models\Client\ClientSecurityType;

class ClientSecurityTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ClientSecurityType::create([
            'code' => 'HASH',
            'name' => 'HMAC Hash',
            'field_data' => json_encode([
                'algo' => [
                    'name' => 'Algo',
                    'type' => 'select',
                    'options' => ['sha1' => 'SHA-1','md5' => 'MD5'],
                ],
                'secret_key' => [
                    'name' => 'Secret Key',
                    'type' => 'text',
                    'default' => '',
                ],
                'decode_url' => [
                    'name' => 'Decode URL',
                    'type' => 'select',
                    'options' => ['0' => 'NO','1' => 'YES'],
                ],
                'query_parameter' => [
                    'name' => 'Query Parameter',
                    'type' => 'text',
                    'default' => 'checksum',
                ],
                'exclude_checksum_var' => [
                    'name' => 'Exclude Hash Parameter',
                    'type' => 'select',
                    'options' => ['0' => 'NO','1' => 'YES'],
                ],
                'exclude_static_url' => [
                    'name' => 'Exclude Static URL (Static Part before ?)',
                    'type' => 'select',
                    'options' => ['0' => 'NO','1' => 'YES'],
                ],
                'static_url' => [
                    'name' => 'Static URL',
                    'type' => 'text',
                    'default' => '',
                ],
                'strip_special_chars' => [
                    'name' => 'Remove Special Chars from checksum',
                    'type' => 'select',
                    'options' => ['0' => 'NO','1' => 'YES'],
                ],
                'output_type' => [
                    'name' => 'Output Type',
                    'type' => 'select',
                    'options' => ['hex' => 'Hexadecimal','base64' => 'Base 64'],
                ],
            ]),
        ]);

        ClientSecurityType::create([
            'code' => 'PIXEL',
            'name' => 'Pixel Check',
            'field_data' => json_encode([
                'referrer_check' => [
                    'name' => 'Referrer Check',
                    'type' => 'select',
                    'options' => ['0' => 'NO','1' => 'YES'],
                ],
                'referral_keyword' => [
                    'name' => 'Referral Keyword',
                    'type' => 'text',
                    'default' => '',
                ],
            ]),
        ]);

        ClientSecurityType::create([
            'code' => 'UNIQURL',
            'name' => 'Unique URL',
            'field_data' => json_encode([
                'param_name' => [
                    'name' => 'Parameter Name',
                    'type' => 'text',
                    'default' => '',
                ],
                'param_value' => [
                    'name' => 'Parameter Value',
                    'type' => 'text',
                    'default' => '',
                ],
            ]),
        ]);

        ClientSecurityType::create([
            'code' => 'S2S',
            'name' => 'S2S Check',
            'field_data' => json_encode([
                'test' => [
                    'name' => 'Test',
                    'type' => 'text',
                    'default' => '',
                ],
            ]),
        ]);
    }
}
