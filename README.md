# Todo API Project

A RESTful API for managing todo tasks built with Laravel and OpenAPI/Swagger documentation.

### !Important
 Find Postman collection Todo API.postman_collection.json in the project directory

## Features

- **Complete CRUD operations** for tasks
- **OpenAPI/Swagger documentation** automatically generated
- **Request validation** using DTO pattern
- **Clean architecture** with separation of concerns
- **Makefile commands** for easy development workflow

## API Endpoints

| Method | Endpoint | Description | Status Codes |
|--------|----------|-------------|--------------|
| GET | `/api/tasks` | Get all tasks | 200 |
| POST | `/api/tasks` | Create a new task | 201, 422 |
| GET | `/api/tasks/{id}` | Get specific task | 200, 404 |
| PUT | `/api/tasks/{id}` | Update a task | 200, 404, 422 |
| DELETE | `/api/tasks/{id}` | Delete a task | 204, 404 |

## Prerequisites

- PHP 8.5
- Composer
- Laravel 12.0
- SQLite

## Installation

1. **Clone the repository**
```
git clone       <your-repository-url>
cd <project-directory>
```

2. **Install dependencies**
```
make install
# or
composer install
```

3. **Configure environment**
```
cp .env.example .env
php artisan key:generate
```
Edit .env file with your database credentials.

4. **Run migrations**
```
make migrate
# or
php artisan migrate
```

## Development Commands

The project includes a Makefile with convenient commands:

```
# Show all available commands
make help

# Install dependencies
make install

# Start development server (default: http://localhost:8000)
make dev
# or
make serve

# Run tests
make test
# or
make run-tests

# Clear application caches
make clear

# Generate Swagger/OpenAPI documentation
make swagger-gen

# Run database migrations
make migrate
```

## API Documentation

This project uses OpenAPI annotations for automatic API documentation generation.

Access Swagger UI:

```
http://localhost:8000/api/documentation
```

Generate documentation:
```
make swagger-gen
```

The API documentation will be available at the /api/documentation endpoint after generation.

## Project Structure
```
app/
├── Http/
│   └── Controllers/
│       └── TaskController.php  # Main API controller
├── Models/
│   └── Task.php               # Task model
└── Dto/
    └── TaskDTO.php            # Data Transfer Object for validation
```

## DTO (Data Transfer Object)

The project uses a TaskDTO class for request validation. This ensures:

    Consistent validation rules across create and update operations

    Type safety

    Separation of validation logic from controllers

Example TaskDTO::rules() method should define validation rules for task attributes.

## Testing

Run the test suite:
```
make test
# or
./vendor/bin/phpunit
```

## Development Workflow

1. Start the development server:
```
make dev
```

2. Access the API at http://localhost:8000/api

3. View API documentation at http://localhost:8000/api/documentation

4. After making changes to OpenAPI annotations, regenerate documentation:
```
make swagger-gen
```

## Example Usage

### Create a task:
```
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Buy groceries",
    "description": "Milk, eggs, bread",
    "completed": false
  }'
```

## Get all tasks:
```
curl http://localhost:8000/api/tasks
```

## Update a task::
```
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{
    "completed": true
  }'
```

## Delete a task::
```
curl -X DELETE http://localhost:8000/api/tasks/1
```

## Dependencies

Main dependencies include:

    Laravel Framework

    zircote/swagger-php for OpenAPI annotations

    darkaonline/l5-swagger for Swagger UI integration

    PHPUnit for testing

### Quick Start Summary:
```
# Clone, install, and run
git clone <repo>
cd <project>
make install
cp .env.example .env
php artisan key:generate
# Edit .env with your DB credentials
make migrate
make dev
```