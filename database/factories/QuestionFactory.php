<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
	    $faker = \Faker\Factory::create('ja_JP');
	
	    return [
		    'title' => str_replace(' ', '', $faker->name),
		    'answer_id' => $faker->numberBetween(1,100),
			'view_count' => $faker->numberBetween(1,50),
		    'note' => $faker->streetAddress() . ' ' . $faker->city(),
		    'created_by' => $faker->numberBetween(1,5),
		    'updated_by' => $faker->numberBetween(1,5),
		    'created_at' => Carbon::now(),
		    'updated_at' => Carbon::now()
	    ];
    }
}
