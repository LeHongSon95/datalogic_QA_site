<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionnaireFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('ja_JP');
        $questionIds = Question::select('id')->get();
        return [
            'question_id' => $questionIds[rand(0, count($questionIds) - 1)],
            'content' => $faker->city() . ' ' . $faker->streetAddress() . ' ' . $faker->address(),
            'status' => rand(0, 1),
        ];
    }
}
