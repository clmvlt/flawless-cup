# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a CodeIgniter 4 framework project. CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure. This appears to be a standard CodeIgniter 4 installation with the framework structure intact.

## Development Commands

### Testing
- **Run all tests**: `./phpunit` (Linux/macOS) or `vendor\bin\phpunit` (Windows)
- **Run specific test directory**: `./phpunit tests/directory_name`
- **Run tests with coverage**: `./phpunit --colors --coverage-text=tests/coverage.txt --coverage-html=tests/coverage/ -d memory_limit=1024m`
- **Composer test script**: `composer test` (runs phpunit)

### Development Server
- **Start development server**: `php spark serve`
- **CLI commands**: Use `php spark` to see available commands

### Dependencies
- **Install dependencies**: `composer install`
- **Update dependencies**: `composer update`

## Project Structure

### Key Directories
- **app/**: Application code (controllers, models, views, config)
  - **Controllers/**: Application controllers (extend BaseController)
  - **Models/**: Data models
  - **Views/**: View templates
  - **Config/**: Configuration files including Routes.php
  - **Database/**: Migrations and seeds
- **public/**: Web root directory (contains index.php)
- **system/**: CodeIgniter 4 framework core files
- **tests/**: Test files using PHPUnit
- **writable/**: Writable directories (cache, logs, sessions, uploads)

### Important Files
- **spark**: CLI entry point for CodeIgniter commands
- **composer.json**: Dependency management and scripts
- **phpunit.xml.dist**: PHPUnit configuration
- **env**: Environment configuration template (copy to .env for local config)

## Architecture Notes

### Framework Structure
- Follows CodeIgniter 4 MVC pattern
- Controllers extend `App\Controllers\BaseController`
- Routes defined in `app/Config/Routes.php`
- Default route: `$routes->get('/', 'Home::index')`

### Configuration
- Environment configuration via .env file (copy from env template)
- Database configuration in app/Config/Database.php or .env
- Base URL and app settings configurable via environment variables

### Testing Architecture
- Uses PHPUnit for testing
- Test database configuration available in phpunit.xml.dist
- Tests organized in tests/ directory with subdirectories
- Supports database testing with migrations and seeds

## Development Requirements

### PHP Requirements
- **PHP**: 8.1+ required
- **Required extensions**: intl, mbstring
- **Suggested extensions**: curl, dom, exif, fileinfo, gd, imagick, libxml, memcache, memcached, mysqli, oci8, pgsql, readline, redis, simplexml, sodium, sqlite3, sqlsrv, xdebug

### Database Support
- MySQL/MariaDB (MySQLi)
- PostgreSQL
- SQLite3
- SQL Server
- Oracle (OCI8)

## Security Notes
- Web root is public/ directory (index.php moved for security)
- Framework files outside web root
- Environment variables for sensitive configuration
- Built-in CSRF protection available
- Input validation and sanitization through framework