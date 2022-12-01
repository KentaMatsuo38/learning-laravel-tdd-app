<?php

namespace Database\Factories\Models;

use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'=>null,
            'lesson_id'=>null,
        ];
    }
}
