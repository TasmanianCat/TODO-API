<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Dto\TaskDTO;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Todo API',
    description: 'API for managing todo tasks'
)]
#[OA\Server(url: 'http://localhost:8080/', description: 'Local Server')]
#[OA\Tag(name: 'Tasks', description: 'Task management operations')]
class TaskController extends Controller
{
    #[OA\Get(
        path: '/api/tasks',
        summary: 'Get all tasks',
        tags: ['Tasks'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of tasks',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/Task')
                )
            ),
        ]
    )]
    public function index(): JsonResponse
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    #[OA\Post(
        path: '/api/tasks',
        summary: 'Create a new task',
        tags: ['Tasks'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskDTO')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Task created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(TaskDTO::rules());
        
        $task = Task::create($validated);
        
        return response()->json($task, 201);
    }

    #[OA\Get(
        path: '/api/tasks/{id}',
        summary: 'Get a specific task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task details',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
        ]
    )]
    public function show(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    #[OA\Put(
        path: '/api/tasks/{id}',
        summary: 'Update a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TaskDTO')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Task updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Task')
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error'
            ),
        ]
    )]
    public function update(Request $request, string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        
        $validated = $request->validate(TaskDTO::rules());
        
        $task->update($validated);
        
        return response()->json($task);
    }

    #[OA\Delete(
        path: '/api/tasks/{id}',
        summary: 'Delete a task',
        tags: ['Tasks'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: 'Task deleted successfully'
            ),
            new OA\Response(
                response: 404,
                description: 'Task not found'
            ),
        ]
    )]
    public function destroy(string $id): JsonResponse
    {
        $task = Task::findOrFail($id);
        $task->delete();
        
        return response()->json(null, 204);
    }
}