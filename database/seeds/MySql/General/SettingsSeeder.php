<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $default_unique_ids_dir_name = 'unique_ids_files';
        $dir_path = public_path($default_unique_ids_dir_name);

        if(!File::exists($dir_path)) {
            File::makeDirectory($dir_path, $mode = 0777, true, true);
        }

        Setting::forgetAll();
        $data = [
            'reports_per_page' => 10,
            'unique_folder_name' => 'unique_id_files',
            'project_quota' => 9999,
            'router' => [
                'domain' => env('SITE_FRONT_URL', 'https://samppoint.com'),
                'start_page' => 'start',
                'end_page' => 'end',
            ],
        ];
        setting($data)->save();
    }
}
