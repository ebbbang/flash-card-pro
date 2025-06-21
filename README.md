# Flash Card Pro

[![Tests](https://github.com/ebbbang/flash-card-pro/actions/workflows/tests.yml/badge.svg)](https://github.com/ebbbang/flash-card-pro/actions/workflows/tests.yml)
[![Coverage Status](https://coveralls.io/repos/github/ebbbang/flash-card-pro/badge.svg?branch=main)](https://coveralls.io/github/ebbbang/flash-card-pro?branch=main)

A Laravel-based flash card application built with Livewire and Laravel Sail.

## Features

- User authentication and registration
- Create and manage flash card decks
- Interactive flash card system
- Modern UI with Livewire Flux components
- Docker development environment with Laravel Sail

## Requirements

- Docker
- Docker Compose

## Installation

1. Clone the repository
2. Copy environment file:
   ```bash
   cp .env.example .env
   ```
3. Start Laravel Sail:
   ```bash
   ./vendor/bin/sail up -d
   ```
4. Install PHP dependencies:
   ```bash
   ./vendor/bin/sail composer install
   ```
5. Install Node dependencies:
   ```bash
   ./vendor/bin/sail npm install
   ```
6. Generate application key:
   ```bash
   ./vendor/bin/sail artisan key:generate
   ```
7. Run migrations:
   ```bash
   ./vendor/bin/sail artisan migrate
   ```
8. Build assets:
   ```bash
   ./vendor/bin/sail npm run build
   ```

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
./vendor/bin/sail test
```

Or using PHPUnit directly:
```bash
./vendor/bin/sail artisan test
```

## Code Style

This project uses Laravel Pint for code formatting:
```bash
./vendor/bin/sail composer pint
```

Or run Pint directly:
```bash
./vendor/bin/sail vendor/bin/pint
```

## Services

The application includes the following services via Docker:
- **Laravel Application** - Main PHP application
- **MySQL** - Database server
- **Redis** - Cache and session store
- **Mailpit** - Local mail testing
- **Selenium** - Browser testing (when needed)
