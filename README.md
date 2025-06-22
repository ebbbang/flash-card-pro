# FlashCardPro

[![Tests](https://github.com/ebbbang/flash-card-pro/actions/workflows/tests.yml/badge.svg)](https://github.com/ebbbang/flash-card-pro/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/ebbbang/flash-card-pro/badge.svg?branch=main)](https://coveralls.io/github/ebbbang/flash-card-pro?branch=main)

**Developer:** Ebrahim Bangdiwala  
**Email:** ebb.bang@gmail.com

A Laravel-based flashcard application for creating, managing, and studying flashcards with public API access.

## Framework Versions

- **PHP:** ^8.2
- **Laravel:** ^12.0
- **Database:** SQLite (development)

## Features

- **User Authentication:** Complete registration, login, email verification, and password reset
- **Deck Management:** Create, read, update, and delete flashcard decks with public/private visibility
- **Card Management:** Full CRUD operations for flashcards with question/answer pairs
- **Study System:** Interactive Livewire-powered study interface with progress tracking and scoring
- **Public API:** RESTful API endpoints for accessing public decks and cards (Sanctum authentication)
- **Authorization:** Policy-based access control ensuring users can only access their own data
- **Modern UI:** Built with Livewire Flux components and Tailwind CSS

## Requirements

- Docker
- Docker Compose
- PHP ^8.2 & Composer (Initial Setup)

## Installation

1. Clone the repository
2. Copy environment file:
   ```bash
   cp .env.example .env
   ```
3. Install PHP dependencies:
   ```bash
   composer install
   ```
4. Start Laravel Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
5. Install Node dependencies:
   ```bash
   ./vendor/bin/sail npm install
   ```
6. Generate application key:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```
7. Create SQLite database file
   ```bash
   touch database/database.sqlite
   ```
8. Run migrations and seed the database:
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
8. Build assets:
   ```bash
   ./vendor/bin/sail npm run build
   ```

## Default Login Credentials

After running the database seeder, you can log in with the following default credentials:

- **URL:** http://0.0.0.0/login (when using Sail)
- **Email:** test@example.com
- **Password:** password

## Development

Start the development environment:
```bash
./vendor/bin/sail up
```

For development with hot reloading:
```bash
./vendor/bin/sail npm run dev
```

### Sail Alias

You can create a shell alias for easier usage:
```bash
alias sail='./vendor/bin/sail'
```

Then use commands like:
```bash
sail up
sail artisan migrate
sail composer install
```

## Testing

Run the test suite:
```bash
./vendor/bin/sail artisan test
```

Run tests with coverage:
```bash
./vendor/bin/sail artisan test --coverage
```

Or using PHPUnit directly:
```bash
./vendor/bin/sail php ./vendor/bin/phpunit
```

## Code Style

This project uses Laravel Pint for code formatting:
```bash
./vendor/bin/sail php ./vendor/bin/pint
```

## API Endpoints

The application provides the following public API endpoints (requires Sanctum token authentication):

- `GET /api/decks` - List all public decks
- `GET /api/decks/{deck}/cards` - Get cards from a specific public deck  
- `GET /api/cards` - List all cards from public decks
- `GET /api/user` - Get authenticated user information

Generate an API token from the Settings > API Access page in the application.

## Architecture & Design Decisions

### Database Design
- **Users** table with Laravel's default authentication fields
- **Decks** table with `user_id` foreign key and `is_public` boolean for visibility control
- **Cards** table with `deck_id` foreign key, storing question/answer pairs
- **API Logs** table for tracking API usage with middleware

### Security Implementation
- **Policies:** `DeckPolicy` and `CardPolicy` for authorization using Laravel's built-in policy system
- **Input Validation:** Form Request validation for all user inputs
- **API Authentication:** Laravel Sanctum for secure API access
- **Authorization Middleware:** Protecting routes and ensuring users can only access their own data

### Key Architectural Features
- **Custom Middleware:** `LogApiUsageMiddleware` for API request logging
- **Resource Classes:** API resources for consistent JSON output formatting  
- **Livewire Components:** Modern, reactive UI without writing JavaScript
- **Service Container:** Dependency injection throughout the application
- **Eloquent Relationships:** Proper model relationships and query scopes

## AI Tool Usage Disclosure

This project was developed with assistance from AI tools to enhance development efficiency while maintaining code quality and understanding.

### Tools Used
- **Claude Code CLI (Anthropic)** - Primary AI assistant for code generation, debugging, and architectural guidance
- **ChatGPT** - Secondary AI assistant for generation of checklist, text, error messages, logo, etc.

### Usage Details

**When:** Creating checklist for tasks and subtasks to perform. Throughout the development process for various features and components

**Where:** AI assistance was used across multiple files and modules:
- Refactor GitHub CI workflows with linting, testing badge and coverage badge 
- Generate SVG Logo
- Generate Boilerplate Readme.md
- Identify if Form Request can be used with Livewire component
- Generate warning messages for API Token generate and storage
- Generate Tailwind Table template for to show API Logs in Settings > API Access page
- Analyze if all tasks in checklist are complete

**Why:** AI tools were used to:
- Accelerate boilerplate content generation (Readme.md, CI Workflows, warning messages, etc.)
- Create custom logo quickly
- Quickly generate tailwind templates for demo
- Proofread content and verify checklist completion

**How:** Effective prompting strategies included:
- Providing clear context about challenge description requirements
- Requesting specific details for Logo
- Asking to generate tailwind table that resembles Laravel Starter Kit
- Seeking code review and suggestions
- Requesting confirmation of checklist

### Code Understanding & Accountability
All AI-generated code has been thoroughly reviewed, tested, and understood. The developer can explain any component's functionality, debug issues, and maintain the codebase independently. The AI assistance enhanced productivity while ensuring all code meets Laravel standards and project requirements.
