<?php

use Illuminate\Database\Seeder;

class GeneralBatchSeeder extends Seeder
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

        $this->call(ScreenerGroupTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(CountryTableSeeder::class);
        $this->call(SettingsSeeder::class);

        $this->enableForeignKeys();
    }
}
