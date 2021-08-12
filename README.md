# TODO APP

## Built using Laravel Lumen

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Setup

- set .env files for your local setup
- `composer install`
- `php artisan migrate:fresh`

## Auth

- The response from register/login routes returns user details with a `api_token` that needs to be sent on all Todo Notes routes

## Routes

- Register: `POST - /api/v1.0/register`
- Login: `POST - /api/v1.0/login`
- Get Todo Notes: `GET - /api/v1.0/todo-notes/{user_id?}` (if user ID not provided it will return todo list for auth'd user)
- Create Todo Notes: `POST - /api/v1.0/todo-notes` (expects string paramater `content` max 255 chars)
- Update Todo Notes: `PATCH - /api/v1.0/todo-notes/{id}` (expects boolean paramater `complete`)
- Delete Todo Notes: `DELETE - /api/v1.0/todo-notes/{id}`

## Testing

- I reccomend using POSTMAN for testing edge cases however I did some basic unit tests that can be run using `phpunit`
