<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
        'title' => $this->faker->sentence,
        'content' => $this->faker->paragraph,
        'read' => rand(0, 1),
        'user_id' => rand(1, 10),
        ];
    }
}
