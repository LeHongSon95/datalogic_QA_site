<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AnswerFactory extends Factory
{
	public function definition(): array
	{
		$faker = \Faker\Factory::create('ja_JP');
		
		return [
			'content' => $faker->city() . ' ' . $faker->streetAddress() . ' ' . $faker->address(),
			'created_by' => $faker->numberBetween(1,5),
			'updated_by' => $faker->numberBetween(1,5),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now(),
		];
	}
}
