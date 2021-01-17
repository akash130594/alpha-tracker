<?php

use Illuminate\Database\Seeder;
use App\Models\Project\ProjectStatus;

class ProjectStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        ProjectStatus::create([
            'code' => 'TBD',
            'name' => 'TBD',
            'next_status_flow' => 'PENDING,CANCELLED'
        ]);
        ProjectStatus::create([
            'code' => 'PENDING',
            'name' => 'Pending Launch',
            'next_status_flow' => 'LIVE,CANCELLED'
        ]);
        ProjectStatus::create([
            'code' => 'CANCELLED',
            'name' => 'Cancelled',
            'next_status_flow' => 'ARCH'
        ]);
        ProjectStatus::create([
            'code' => 'LIVE',
            'name' => 'Live',
            'next_status_flow' => 'HOLD,CLOSED'
        ]);
        ProjectStatus::create([
            'code' => 'HOLD',
            'name' => 'Hold / Pause',
            'next_status_flow' => 'LIVE,CLOSED'
        ]);
        ProjectStatus::create([
            'code' => 'CLOSED',
            'name' => 'Closed / ID\'s Awaited',
            'next_status_flow' => 'LIVE,IDRECVD,ARCH'
        ]);
        ProjectStatus::create([
            'code' => 'IDRECVD',
            'name' => 'ID\'s Received',
            'next_status_flow' => 'IP'
        ]);
        ProjectStatus::create([
            'code' => 'IP',
            'name' => 'Incentive Paid & Billed',
            'next_status_flow' => 'ARCH'
        ]);
        ProjectStatus::create([
            'code' => 'ARCH',
            'name' => 'Archived',
        ]);
    }
}
