<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // dd(User::get()->random()->id)
        ;
        return [
            // "sub_name" => fake()->name(),

            // "sub_code" => Str::random(6),
            // "creator_id" => User::id(),

            // 'attend_code'=>random_int(100000,999999),

        ];
    }
}
