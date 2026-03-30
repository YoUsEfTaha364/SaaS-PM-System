# Project Overview

This is a web application built with the Laravel framework. It appears to be a collaborative project management tool, with features for managing workspaces, projects, and tasks.

## Key Technologies

*   **Backend:** PHP, Laravel
*   **Frontend:** JavaScript, Vite, Tailwind CSS, Alpine.js
*   **Database:** Not explicitly defined, but likely a relational database compatible with Laravel (e.g., MySQL, PostgreSQL, SQLite).

## Architecture

The application follows the Model-View-Controller (MVC) architectural pattern, which is standard for Laravel projects.

*   **Models:** The core data entities are `User`, `Workspace`, `Project`, and `Task`. These are located in the `app/Models` directory.
    *   A `User` can own and be a member of multiple `Workspaces`.
    *   A `Workspace` can have multiple `Projects` and multiple `Users`.
    *   A `Project` belongs to a `Workspace` and can have multiple `Tasks`.
    *   A `Task` belongs to a `Project` and can be assigned to multiple `Users`.
*   **Routes:** The application's routes are defined in `routes/web.php` and `routes/api.php`. These map URLs to controller actions.
*   **Controllers:** The controllers, located in `app/Http/Controllers`, handle the application's logic.
*   **Views:** The views, located in the `resources/views` directory, are responsible for rendering the user interface. They use Blade templating.

## Building and Running

### Prerequisites

*   PHP and Composer installed.
*   Node.js and npm installed.
*   A database server (e.g., MySQL, PostgreSQL, SQLite).

### Setup and Installation

1.  **Clone the repository.**
2.  **Install PHP dependencies:**
    ```bash
    composer install
    ```
3.  **Create a `.env` file:** Copy the `.env.example` file to `.env` and configure your database and other environment variables.
    ```bash
    cp .env.example .env
    ```
4.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```
5.  **Run database migrations:**
    ```bash
    php artisan migrate
    ```
6.  **Install frontend dependencies:**
    ```bash
    npm install
    ```
7.  **Build frontend assets:**
    ```bash
    npm run build
    ```

### Development Server

The `composer.json` file includes a convenient `dev` script that starts the PHP development server, a queue listener, and the Vite development server concurrently.

```bash
composer run dev
```

Alternatively, you can run the servers separately:

*   **PHP development server:**
    ```bash
    php artisan serve
    ```
*   **Vite development server:**
    ```bash
    npm run dev
    ```

## Testing

To run the application's test suite, use the following command:

```bash
php artisan test
```

## Development Conventions

*   The project follows the standard Laravel directory structure and coding conventions.
*   Frontend assets are managed with Vite.
*   Styling is done with Tailwind CSS.
*   Alpine.js is used for client-side interactivity.
*   Commits should follow the Conventional Commits specification.
