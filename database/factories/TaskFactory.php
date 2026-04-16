<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4), // عنوان من 4 كلمات
            'description' => fake()->paragraph(2), // وصف من فقرتين
            'status' => fake()->randomElement(['todo', 'doing', 'done']),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }
}
