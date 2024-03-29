<?php

namespace Database\Factories;

use App\Models\OptionType;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => fake()->text(70),
            'quiz_id' => Quiz::get('quiz_id')->random()->quiz_id,
            'option_type_id' => OptionType::get('option_type_id')->random()->option_type_id,
        ];
    }
}
