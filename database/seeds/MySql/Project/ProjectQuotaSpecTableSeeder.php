<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectQuotaSpec;

class ProjectQuotaSpecTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_quota_specs = array(
            array('id' => '33','project_quota_id' => '1','is_global' => '1','question_general_name' => 'gender','question_id' => NULL,'type' => NULL,'values' => '["male","female"]','raw_spec' => '["male","female"]'),
            array('id' => '34','project_quota_id' => '1','is_global' => '1','question_general_name' => 'age','question_id' => NULL,'type' => NULL,'values' => '[{"min_date":{"date":"1994-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"2000-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1984-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1993-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}}]','raw_spec' => '[{"min_date":{"date":"1994-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"2000-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1984-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1993-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}}]'),
            array('id' => '35','project_quota_id' => '1','is_global' => '1','question_general_name' => 'zipcode','question_id' => NULL,'type' => NULL,'values' => 'false','raw_spec' => 'false'),
            array('id' => '36','project_quota_id' => '1','is_global' => '0','question_general_name' => 'STANDARD_EDUCATION','question_id' => '1','type' => NULL,'values' => '["4","5"]','raw_spec' => '["4","5"]'),
            array('id' => '37','project_quota_id' => '2','is_global' => '1','question_general_name' => 'gender','question_id' => NULL,'type' => NULL,'values' => '["male","female"]','raw_spec' => '["male","female"]'),
            array('id' => '38','project_quota_id' => '2','is_global' => '1','question_general_name' => 'age','question_id' => NULL,'type' => NULL,'values' => '[{"min_date":{"date":"1994-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"2000-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1984-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1993-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1974-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1983-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}}]','raw_spec' => '[{"min_date":{"date":"1994-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"2000-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1984-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1993-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}},{"min_date":{"date":"1974-12-28 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"max_date":{"date":"1983-12-28 23:59:59.999999","timezone_type":3,"timezone":"UTC"}}]'),
            array('id' => '39','project_quota_id' => '2','is_global' => '1','question_general_name' => 'DIVISION','question_id' => NULL,'type' => NULL,'values' => '["Northeast","South"]','raw_spec' => '["Northeast","South"]'),
            array('id' => '40','project_quota_id' => '2','is_global' => '1','question_general_name' => 'zipcode','question_id' => NULL,'type' => NULL,'values' => 'false','raw_spec' => 'false'),
            array('id' => '41','project_quota_id' => '2','is_global' => '0','question_general_name' => 'STANDARD_HHI_US','question_id' => '9','type' => NULL,'values' => '["97","98","99"]','raw_spec' => '["97","98","99"]'),
            array('id' => '42','project_quota_id' => '3','is_global' => '1','question_general_name' => 'gender','question_id' => NULL,'type' => NULL,'values' => '["male","female"]','raw_spec' => '["male","female"]'),
            array('id' => '43','project_quota_id' => '3','is_global' => '1','question_general_name' => 'zipcode','question_id' => NULL,'type' => NULL,'values' => 'false','raw_spec' => 'false'),
            array('id' => '44','project_quota_id' => '3','is_global' => '0','question_general_name' => 'STANDARD_HOUSEHOLD_TYPE','question_id' => '13','type' => NULL,'values' => '["134","135"]','raw_spec' => '["134","135"]'),
            array('id' => '45','project_quota_id' => '3','is_global' => '0','question_general_name' => 'STANDARD_No_OF_EMPLOYEES','question_id' => '27','type' => NULL,'values' => '["656","657"]','raw_spec' => '["656","657"]')
        );

        ProjectQuotaSpec::insert($project_quota_specs);
    }
}
