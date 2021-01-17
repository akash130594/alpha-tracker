<?php

use Illuminate\Database\Seeder;
use App\Models\General\ScreenerGroup;

class ScreenerGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ScreenerGroup::create([
            'code' => 'GLOBSCR',
            'name' => 'Global Screener',
        ]);

        ScreenerGroup::create([
            'code' => 'DEFSCR',
            'name' => 'Defined Screener',
        ]);

        ScreenerGroup::create([
            'code' => 'CUSTSCR',
            'name' => 'Custom Screener',
        ]);
    }
}
