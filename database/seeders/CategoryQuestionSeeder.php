<?php

namespace Database\Seeders;

use App\Models\CategoryQuestion;
use Illuminate\Database\Seeder;

class CategoryQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	    CategoryQuestion::factory()->count(200)->create();
    }
}
