<?php

use Illuminate\Database\Seeder;
use App\Models\Project\Project;

class ProjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $jsonData = json_decode(file_get_contents(__DIR__ . '/project.json'), true);
        /*foreach ($jsonData as $project){
            Project::create([
                'code' =>  (!empty($project['code']))?$project['code']:'',
                'name' => (!empty($project['name']))?$project['name']:'',
                'label' => (!empty($project['label']))?$project['label']:'',
                'study_type_id' => (!empty($project['study_type_id']))?$project['study_type_id']:'',
                'project_topic_id' => (!empty($project['project_topic_id']))?$project['project_topic_id']:'',
                'client_id' => (!empty($project['client_id']))?$project['client_id']:'',
                'client_code' => (!empty($project['client_code']))?$project['client_code']:'',
                'client_name' => (!empty($project['client_name']))?$project['client_name']:'',
                'client_var' => (!empty($project['client_var']))?$project['client_var']:'',
                'client_link' => (!empty($project['client_link']))?$project['client_link']:'',
                'client_project_no' => (!empty($project['client_project_no']))?$project['client_project_no']:'',
                'unique_ids_flag' => (!empty($project['unique_ids_flag']))?$project['unique_ids_flag']:false,
                'unique_ids_file' =>(!empty($project['unique_ids_file']))?$project['unique_ids_file']:'null',
                'can_links' =>(!empty($project['links']))?$project['links']:0,
                'country_id' =>(!empty($project['country_id']))?$project['country_id']:'',
                'country_code' => (!empty($project['country_code']))?$project['country_code']:'',
                'language_id' => (!empty($project['language_id']))?$project['language_id']:'',
                'language_code' => (!empty($project['language_code']))?$project['language_code']:'',
                'start_date' => (!empty($project['start_date']))?$project['start_date']:'',
                'end_date' => (!empty($project['end_date']))?$project['end_date']:'',
                'created_by' =>(!empty($project['created_by']))?$project['created_by']:'',
                //'updated_by',
                'ir' => (!empty($project['ir']))?$project['ir']:'',
                'loi' => (!empty($project['loi']))?$project['loi']:'',
                'loi_validation' => (!empty($project['loi_validation']))?$project['loi_validation']:false,
                'loi_validation_time' => (!empty($project['loi_validation_time']))?$project['loi_validation_time']:'',
                'cpi' =>(!empty($project['cpi']))?$project['cpi']:'',
                'incentive' => (!empty($project['incentive']))?$project['incentive']:'',
                'quota' => (!empty($project['quota']))?$project['quota']:'',
                'status_id' => (!empty($project['status_id']))?$project['status_id']:'',
                'status_label' => (!empty($project['status_label']))?$project['status_label']:'',


            ]);
        }*/
        /*$this->call(ProjectQuotaTableSeeder::class);
        $this->call(ProjectQuotaSpecTableSeeder::class);
        $this->call(ProjectVendorTableSeeder::class);
        $this->call(ProjectSurveyTableSeeder::class);
        $this->call(ProjectCustomScreenerTableSeeder::class);*/
    }
}
