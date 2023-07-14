<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
	    DB::statement('SET FOREIGN_KEY_CHECKS=0');
	    DB::statement('TRUNCATE TABLE answers');
	    DB::statement('TRUNCATE TABLE questions');
	    DB::statement('TRUNCATE TABLE categories');
	    DB::statement('TRUNCATE TABLE tags');
	    DB::statement('TRUNCATE TABLE category_questions');
	    DB::statement('TRUNCATE TABLE tag_questions');
	    DB::statement('TRUNCATE TABLE featured_tags');
		DB::statement('TRUNCATE TABLE questionnaires');
	    DB::statement('SET FOREIGN_KEY_CHECKS=0');
		
	    // \App\Models\User::factory(10)->create();
	    $this->call([
		    AnswerSeeder::class,
			QuestionSeeder::class,
		    CategorySeeder::class,
			TagSeeder::class,
			CategoryQuestionSeeder::class,
			TagQuestionSeeder::class,
		    FeaturedTagSeeder::class,
			QuestionnaireSeeder::class
	    ]);
    }
}
