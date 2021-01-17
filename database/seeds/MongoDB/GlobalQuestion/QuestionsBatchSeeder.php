<?php

use Illuminate\Database\Seeder;

class QuestionsBatchSeeder extends Seeder
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

        $this->call(GlobalQuestionCollectionSeeder::class);
        $this->call(ProfileQuestionCollectionSeeder::class);

        $this->enableForeignKeys();
    }
}
