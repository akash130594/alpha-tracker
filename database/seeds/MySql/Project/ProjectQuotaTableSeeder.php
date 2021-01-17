<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectQuota;

class ProjectQuotaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_quotas = array(
            array('id' => '1','project_id' => '1','name' => 'Quota 1','description' => 'Quota 1','cpi' => '1.25','count' => '100','raw_quota_spec' => '[{"name":"basic[gender][]","value":"male"},{"name":"basic[gender][]","value":"female"},{"name":"basic[age][]","value":"18-24"},{"name":"basic[age][]","value":"25-34"},{"name":"basic[zipcode][values]","value":""},{"name":"detailed[STANDARD_EDUCATION][]","value":"4"},{"name":"detailed[STANDARD_EDUCATION][]","value":"5"}]','formatted_quota_spec' => '{"basic":{"gender":[["male"],["female"]],"age":[["18-24"],["25-34"]],"zipcode":[{"values":""}]},"detailed":{"STANDARD_EDUCATION":[["4"],["5"]]}}','type' => NULL,'status' => '1'),
            array('id' => '2','project_id' => '1','name' => 'Quota 2','description' => 'Quota 2','cpi' => '1.25','count' => '100','raw_quota_spec' => '[{"name":"basic[gender][]","value":"male"},{"name":"basic[gender][]","value":"female"},{"name":"basic[age][]","value":"18-24"},{"name":"basic[age][]","value":"25-34"},{"name":"basic[age][]","value":"35-44"},{"name":"basic[DIVISION][]","value":"Northeast"},{"name":"basic[DIVISION][]","value":"South"},{"name":"basic[zipcode][values]","value":""},{"name":"detailed[STANDARD_HHI_US][]","value":"97"},{"name":"detailed[STANDARD_HHI_US][]","value":"98"},{"name":"detailed[STANDARD_HHI_US][]","value":"99"}]','formatted_quota_spec' => '{"basic":{"gender":[["male"],["female"]],"age":[["18-24"],["25-34"],["35-44"]],"DIVISION":[["Northeast"],["South"]],"zipcode":[{"values":""}]},"detailed":{"STANDARD_HHI_US":[["97"],["98"],["99"]]}}','type' => NULL,'status' => '1'),
            array('id' => '3','project_id' => '1','name' => 'Quota 3','description' => 'Quota 3','cpi' => '1.25','count' => '100','raw_quota_spec' => '[{"name":"basic[gender][]","value":"male"},{"name":"basic[gender][]","value":"female"},{"name":"basic[zipcode][values]","value":""},{"name":"detailed[STANDARD_HOUSEHOLD_TYPE][]","value":"134"},{"name":"detailed[STANDARD_HOUSEHOLD_TYPE][]","value":"135"},{"name":"detailed[STANDARD_No_OF_EMPLOYEES][]","value":"656"},{"name":"detailed[STANDARD_No_OF_EMPLOYEES][]","value":"657"}]','formatted_quota_spec' => '{"basic":{"gender":[["male"],["female"]],"zipcode":[{"values":""}]},"detailed":{"STANDARD_HOUSEHOLD_TYPE":[["134"],["135"]],"STANDARD_No_OF_EMPLOYEES":[["656"],["657"]]}}','type' => NULL,'status' => '1')
        );

        ProjectQuota::insert($project_quotas);
    }
}
