<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class CategoryQuestionFactory extends Factory
{
	public function definition(): array
	{
		return [
			'category_id' => $this->faker->numberBetween(1,11),
			'question_id' => $this->faker->unique(true)->numberBetween(1,200),
			'created_at' => Carbon::now(),
			'updated_at' => Carbon::now()
		];
	}
}
