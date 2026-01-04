<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $statuses = ['pending', 'in_progress', 'completed'];
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement($statuses),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function pending(): self
    {
        return $this->state([
            'status' => 'pending',
        ]);
    }

    public function inProgress(): self
    {
        return $this->state([
            'status' => 'in_progress',
        ]);
    }

    public function completed(): self
    {
        return $this->state([
            'status' => 'completed',
        ]);
    }

    public function withLongDescription(): self
    {
        return $this->state([
            'description' => $this->faker->paragraphs(3, true),
        ]);
    }

    public function withShortTitle(): self
    {
        return $this->state([
            'title' => $this->faker->word(),
        ]);
    }
}