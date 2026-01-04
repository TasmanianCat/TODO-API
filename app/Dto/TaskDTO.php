<?php

namespace App\Dto;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Validation;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'TaskDTO',
    required: ['title', 'description', 'status'],
    properties: [
        new OA\Property(property: 'title', type: 'string', maxLength: 255),
        new OA\Property(property: 'description', type: 'string'),
        new OA\Property(
            property: 'status', 
            type: 'string', 
            enum: ['pending', 'in_progress', 'completed']
        ),
    ]
)]
class TaskDTO extends Data
{
    public function __construct(
        #[Validation\Required]
        #[Validation\StringType]
        #[Validation\Max(255)]
        public string $title,

        #[Validation\Nullable]
        #[Validation\StringType]
        public ?string $description,

        #[Validation\Required]
        #[Validation\StringType]
        #[Validation\In(['pending', 'in_progress', 'completed'])]
        public string $status = 'pending'
    ) {}

    public static function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:pending,in_progress,completed'],
        ];
    }
}