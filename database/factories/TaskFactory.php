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
     * The name of the factory's corresponding model.
     *
     * @var class-string<Task>
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dueDate = fake()->boolean(75) ? fake()->dateTimeBetween('-5 days', '+30 days')->format('Y-m-d') : null;

        return [
            'title' => rtrim(fake()->sentence(rand(3, 6)), '.'),
            'description' => fake()->boolean(80) ? fake()->paragraph(rand(1, 3)) : null,
            'priority' => fake()->randomElement(Task::PRIORITIES),
            'status' => fake()->randomElement(Task::STATUSES),
            'due_date' => $dueDate,
        ];
    }
}
