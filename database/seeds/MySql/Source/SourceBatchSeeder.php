<?php

use Illuminate\Database\Seeder;

class SourceBatchSeeder extends Seeder
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

        $this->call(SourceTypeTableSeeder::class);
        $this->call(SourceTableSeeder::class);

        $this->enableForeignKeys();
    }
}
