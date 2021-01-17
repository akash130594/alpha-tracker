<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectVendor;

class ProjectVendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $array_1 = [
            'project_id' => 1,
            'project_code' => '18120482US',
            'vendor_id' => 1,
            'vendor_code' => '1111',
            'spec_quota_ids' => '1,2,3',
            'spec_quota_names' => 'Quota 1,Quota 2,Quota 3',
            'cpi' => 15,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_1);

        $array_2 = [
            'project_id' => 1,
            'project_code' => '18120482US',
            'vendor_id' => 2,
            'vendor_code' => '1185',
            'spec_quota_ids' => '2,3',
            'spec_quota_names' => 'Quota 2,Quota 3',
            'cpi' => 18,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_2);

        $array_3 = [
            'project_id' => 1,
            'project_code' => '18120482US',
            'vendor_id' => 3,
            'vendor_code' => '2287',
            'spec_quota_ids' => '2,3',
            'spec_quota_names' => 'Quota 2,Quota 3',
            'cpi' => 18,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_3);

        $array_4 = [
            'project_id' => 1,
            'project_code' => '18120482US',
            'vendor_id' => 4,
            'vendor_code' => '3388',
            'spec_quota_ids' => '1,3',
            'spec_quota_names' => 'Quota 1,Quota 3',
            'cpi' => 16,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_4);

        $array_5 = [
            'project_id' => 1,
            'project_code' => '18120482US',
            'vendor_id' => 6,
            'vendor_code' => 'SJPL',
            'spec_quota_ids' => '1,2,3',
            'spec_quota_names' => 'Quota 1,Quota 2,Quota 3',
            'cpi' => 16,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_5);

        //2nd Project

        $array_1 = [
            'project_id' => 2,
            'project_code' => '18120484UK',
            'vendor_id' => 1,
            'vendor_code' => '1111',
            'cpi' => 15,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_1);

        $array_2 = [
            'project_id' => 2,
            'project_code' => '18120484UK',
            'vendor_id' => 2,
            'vendor_code' => '1185',
            'cpi' => 18,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_2);

        $array_3 = [
            'project_id' => 2,
            'project_code' => '18120484UK',
            'vendor_id' => 3,
            'vendor_code' => '2287',
            'cpi' => 18,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_3);

        $array_4 = [
            'project_id' => 2,
            'project_code' => '18120484UK',
            'vendor_id' => 4,
            'vendor_code' => '3388',
            'cpi' => 16,
            'quota' => 9999,
            'sy_excl_link_flag'=>false,
            'quota_completes' => 0,
            'quota_remains' => 9999, //Quota - completes
            'vendor_screener_excl_flag' => false,
        ];
        ProjectVendor::create($array_4);
    }
}
