<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class TagFactory extends Factory
{
	public function definition(): array
	{
		$faker = \Faker\Factory::create('ja_JP');
		
		return [
			'title' => $faker->lastName,
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		];
	}
}