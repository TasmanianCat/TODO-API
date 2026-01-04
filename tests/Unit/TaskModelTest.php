<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function basic_task_creation(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('pending', $task->status);
        $this->assertNull($task->description);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Test Task',
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function task_with_description(): void
    {
        $task = Task::create([
            'title' => 'Test Task',
            'description' => 'This is a description',
            'status' => 'in_progress'
        ]);

        $this->assertEquals('This is a description', $task->description);
        $this->assertEquals('in_progress', $task->status);
    }

    #[Test]
    public function task_update(): void
    {
        $task = Task::create([
            'title' => 'Original Title',
            'status' => 'pending'
        ]);

        $task->update([
            'title' => 'Updated Title',
            'status' => 'completed'
        ]);

        $this->assertEquals('Updated Title', $task->title);
        $this->assertEquals('completed', $task->status);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed'
        ]);
    }

    #[Test]
    public function task_deletion(): void
    {
        $task = Task::create([
            'title' => 'Task to delete',
            'status' => 'pending'
        ]);

        $taskId = $task->id;
        $this->assertDatabaseHas('tasks', ['id' => $taskId]);

        $task->delete();
        $this->assertDatabaseMissing('tasks', ['id' => $taskId]);
    }

    #[Test]
    public function task_factory(): void
    {
        $task = Task::factory()->create();

        $this->assertNotNull($task->title);
        $this->assertNotNull($task->status);
        $this->assertContains($task->status, ['pending', 'in_progress', 'completed']);
    }

    #[Test]
    public function fillable_fields(): void
    {
        $task = new Task();
        $fillable = $task->getFillable();

        $this->assertEquals(['title', 'description', 'status'], $fillable);
    }

    #[Test]
    public function casts(): void
    {
        $task = new Task();
        $casts = $task->getCasts();

        $this->assertArrayHasKey('created_at', $casts);
        $this->assertArrayHasKey('updated_at', $casts);
        $this->assertEquals('datetime', $casts['created_at']);
        $this->assertEquals('datetime', $casts['updated_at']);
    }

    #[Test]
    public function timestamps(): void
    {
        $task = Task::create([
            'title' => 'Test Timestamps',
            'status' => 'pending'
        ]);

        $this->assertNotNull($task->created_at);
        $this->assertNotNull($task->updated_at);
        $this->assertInstanceOf(\DateTimeInterface::class, $task->created_at);
        $this->assertInstanceOf(\DateTimeInterface::class, $task->updated_at);
    }

    #[Test]
    public function multiple_tasks(): void
    {
        Task::factory()->count(3)->create();
        $this->assertDatabaseCount('tasks', 3);
    }

    #[Test]
    public function status_filtering(): void
    {
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'pending']);
        Task::factory()->create(['status' => 'completed']);
        Task::factory()->create(['status' => 'in_progress']);
        Task::factory()->create(['status' => 'in_progress']);

        $pendingCount = Task::where('status', 'pending')->count();
        $completedCount = Task::where('status', 'completed')->count();
        $inProgressCount = Task::where('status', 'in_progress')->count();

        $this->assertEquals(2, $pendingCount);
        $this->assertEquals(1, $completedCount);
        $this->assertEquals(2, $inProgressCount);
    }

    #[Test]
    public function to_array_structure(): void
    {
        $task = Task::create([
            'title' => 'Test Array',
            'description' => 'Array test',
            'status' => 'pending'
        ]);

        $array = $task->toArray();

        $expectedKeys = ['id', 'title', 'description', 'status', 'created_at', 'updated_at'];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $array);
        }

        $this->assertEquals('Test Array', $array['title']);
        $this->assertEquals('Array test', $array['description']);
        $this->assertEquals('pending', $array['status']);
    }

    #[Test]
    public function different_status_values(): void
    {
        $statuses = ['pending', 'in_progress', 'completed'];

        foreach ($statuses as $status) {
            $task = Task::create([
                'title' => "Task with {$status} status",
                'status' => $status
            ]);

            $this->assertEquals($status, $task->status);
            $this->assertDatabaseHas('tasks', [
                'id' => $task->id,
                'status' => $status
            ]);
        }
    }

    #[Test]
    public function empty_description(): void
    {
        // Test with null description
        $task1 = Task::create([
            'title' => 'Task 1',
            'status' => 'pending'
        ]);
        $this->assertNull($task1->description);

        // Test with empty string description
        $task2 = Task::create([
            'title' => 'Task 2',
            'description' => '',
            'status' => 'pending'
        ]);
        $this->assertEquals('', $task2->description);

        // Test with space-only description
        $task3 = Task::create([
            'title' => 'Task 3',
            'description' => '   ',
            'status' => 'pending'
        ]);
        $this->assertEquals('   ', $task3->description);
    }
}