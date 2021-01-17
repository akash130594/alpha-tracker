<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->truncateMultiple([
            'cache',
            'jobs',
            'sessions',
        ]);

        $this->call(AuthTableSeeder::class);
        $this->call(ClientBatchSeeder::class);
        $this->call(GeneralBatchSeeder::class);

        //SJ Panel Profilers
        $this->call(BatchProfilerSeeder::class);


        $this->call(QuestionsBatchSeeder::class);

        $this->call(SourceBatchSeeder::class);
        $this->call(ProjectBatchSeeder::class);
        $this->call(SJPanelBatchSeeder::class);
        $this->call(ApaceTempBatchSeeder::class);

        Model::reguard();
    }
}
