<?php

use Illuminate\Database\Seeder;
use App\Models\Source\SourceType;

class SourceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SourceType::create([
            'code' => 'INTL',
            'name' => 'Internal',
        ]);
        SourceType::create([
            'code' => 'VNDR',
            'name' => 'Vendor',
        ]);

        SourceType::create([
            'code' => 'AFFL',
            'name' => 'Affiliate',
        ]);

        SourceType::create([
            'code' => 'PANEL',
            'name' => 'Panel',
        ]);
    }
}
