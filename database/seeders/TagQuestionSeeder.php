<?php

namespace Database\Seeders;

use App\Models\TagQuestion;
use Illuminate\Database\Seeder;

class TagQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TagQuestion::factory(200)->create();
    }
}
