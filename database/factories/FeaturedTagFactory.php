<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class FeaturedTagFactory extends Factory
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
		    'is_featured' => $faker->numberBetween(0, 1),
		    'order' => $this->faker->unique(true)->numberBetween(1, 50),
		    'created_at' => Carbon::now(),
		    'updated_at' => Carbon::now()
	    ];
    }
}
