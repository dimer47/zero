# Release Notes

## [Unreleased](https://github.com/dimer47/zero/compare/v1.0.1...HEAD)

## v1.0.1 - 2026-01-22

### Changed

- Moved `install` and `publish` commands from PHP to bash script for standalone execution without Docker
- Commands now work before Docker is configured (`zero install` creates docker-compose.yml)
- Simplified ZeroServiceProvider (removed command registration)

### Removed

- Removed `InstallCommand.php` and `PublishCommand.php` (logic now in bin/zero)

## v1.0.0 - 2026-01-22

Initial release of dimer47/zero.

### Added

- Lightweight Docker wrapper for Laravel Zero CLI applications
- PHP 8.2, 8.3, 8.4 and 8.5 runtime support
- Simple `zero` binary for common Docker operations
- Docker Compose configuration publishing via `zero:install` command
- Inspired by Laravel Sail, optimized for Laravel Zero CLI applications
