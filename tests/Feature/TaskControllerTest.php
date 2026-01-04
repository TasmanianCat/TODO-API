<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_get_all_tasks()
    {
        // Create test data
        Task::factory()->count(3)->create();

        // Make HTTP request - this will invoke TaskController::index()
        $response = $this->getJson('/api/tasks');

        // Assertions
        $response->assertStatus(200)
                 ->assertJsonCount(3)
                 ->assertJsonStructure([
                     '*' => ['id', 'title', 'description', 'status', 'created_at', 'updated_at']
                 ]);
    }

    /** @test */
    public function test_create_task_with_valid_data()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending'
        ];

        // Make HTTP POST request - this will invoke TaskController::store()
        $response = $this->postJson('/api/tasks', $taskData);

        // Assertions
        $response->assertStatus(201)
                 ->assertJsonStructure(['id', 'title', 'description', 'status'])
                 ->assertJson([
                     'title' => $taskData['title'],
                     'description' => $taskData['description'],
                     'status' => $taskData['status']
                 ]);

        // Verify database
        $this->assertDatabaseHas('tasks', $taskData);
    }

    /** @test */
    public function test_get_existing_task()
    {
        $task = Task::factory()->create();

        // Make HTTP GET request - this will invoke TaskController::show()
        $response = $this->getJson("/api/tasks/{$task->id}");

        // Assertions
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $task->id,
                     'title' => $task->title,
                     'description' => $task->description,
                     'status' => $task->status
                 ]);
    }

    /** @test */
    public function test_update_task()
    {
        $task = Task::factory()->create();
        
        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'status' => 'completed'
        ];

        // Make HTTP PUT request - this will invoke TaskController::update()
        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        // Assertions
        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $task->id,
                     'title' => $updateData['title'],
                     'description' => $updateData['description'],
                     'status' => $updateData['status']
                 ]);

        // Verify database
        $this->assertDatabaseHas('tasks', array_merge(['id' => $task->id], $updateData));
    }

    /** @test */
    public function test_delete_task()
    {
        $task = Task::factory()->create();

        // Make HTTP DELETE request - this will invoke TaskController::destroy()
        $response = $this->deleteJson("/api/tasks/{$task->id}");

        // Assertions
        $response->assertStatus(204);

        // Verify database
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}