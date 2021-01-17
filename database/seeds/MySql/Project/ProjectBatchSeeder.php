<?php

use Illuminate\Database\Seeder;

class ProjectBatchSeeder extends Seeder
{
    use DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        Auth::login(\App\Models\Auth\User::find(3));

        $this->call(StudyTypeTableSeeder::class);
        $this->call(ProjectTopicTableSeeder::class);
        $this->call(ProjectStatusTableSeeder::class);
        $this->call(ProjectDedupeTableSeeder::class);
        $this->call(ProjectTableSeeder::class);

        Auth::logout();

        $this->enableForeignKeys();
    }
}
