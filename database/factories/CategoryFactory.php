<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CategoryFactory extends Factory
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
            'title' => $faker->firstName,
	        'created_at' => Carbon::now(),
	        'updated_at' => Carbon::now()
        ];
    }
}
