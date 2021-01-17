<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectDedupe;

class ProjectDedupeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectDedupe::create([
            'dedupe_status' => 'attempted',
        ]);
        ProjectDedupe::create([
            'dedupe_status' => 'completed',
        ]);
    }
}
