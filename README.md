# Zero - Docker CLI Environment for Laravel Zero

<p align="center">
    <img src="https://raw.githubusercontent.com/laravel-zero/docs/master/images/logo/laravel-zero-readme.png" alt="Laravel Zero" width="450">
</p>

<p align="center">
    <a href="https://packagist.org/packages/dimer47/zero"><img src="https://img.shields.io/packagist/v/dimer47/zero" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/dimer47/zero"><img src="https://img.shields.io/packagist/l/dimer47/zero" alt="License"></a>
</p>

A minimal Docker wrapper for Laravel Zero, inspired by Laravel Sail but designed specifically for CLI applications.

## ✨ Features

- 🚀 **Ephemeral Containers** - Each command runs in a fresh container, no daemon needed
- 🎯 **Auto-detection** - Automatically detects your application binary from `composer.json`
- 🐘 **Multi PHP Version** - Support for PHP 8.2, 8.3, and 8.4
- 🪶 **Alpine-based** - Ultra-lightweight images (~50MB vs ~1GB)
- 📦 **Ready to use** - One command installation

## 📋 Requirements

- Docker Desktop (macOS/Windows) or Docker Engine (Linux)
- Docker Compose v2+
- A Laravel Zero project

## 🚀 Installation

```bash
composer require dimer47/zero --dev
```

Publish the Docker configuration:

```bash
php application zero:install
```

This will:
- Create `docker-compose.yml` - Docker Compose configuration
- Set `PHP_VERSION` in your `.env` file (created if needed)

You can specify a different PHP version:

```bash
php application zero:install --php=8.4
```

Build the Docker image:

```bash
./vendor/bin/zero build
```

### 🎯 Shell Alias (Recommended)

For a better developer experience, add this alias to your shell configuration (`~/.zshrc` or `~/.bashrc`):

```bash
alias zero='$([ -f zero ] && echo ./zero || echo ./vendor/bin/zero)'
```

Then reload your shell:

```bash
source ~/.zshrc  # or source ~/.bashrc
```

Now you can use `zero` directly instead of `./vendor/bin/zero`:

```bash
zero list
zero build
zero pest
```

## 📖 Usage

### 🎯 Application Commands

```bash
# List all available application commands
./vendor/bin/zero list

# Run any application command (binary auto-detected from composer.json)
./vendor/bin/zero server:list
./vendor/bin/zero make:command MyCommand
```

### ⚡ Laravel Zero Commands

```bash
# Run tests
./vendor/bin/zero test

# Build PHAR executable (output in builds/ directory)
./vendor/bin/zero app:build my-app

# Install optional components
./vendor/bin/zero app:install

# Rename your application
./vendor/bin/zero app:rename my-new-name

# Create a new command
./vendor/bin/zero make:command

# Create a new test
./vendor/bin/zero make:test
```

### 🐘 PHP & Composer

```bash
# Run PHP commands
./vendor/bin/zero php -v
./vendor/bin/zero php script.php

# Run Composer commands
./vendor/bin/zero composer install
./vendor/bin/zero composer require vendor/package
./vendor/bin/zero composer update
```

### 🧪 Testing & Code Style

```bash
# Run Pest tests
./vendor/bin/zero pest
./vendor/bin/zero pest --filter=MyTest
./vendor/bin/zero pest --coverage

# Run Pint code style fixer
./vendor/bin/zero pint
./vendor/bin/zero pint --test      # Check without fixing
./vendor/bin/zero pint --dirty     # Only changed files
./vendor/bin/zero pint app/        # Specific directory
```

### 🐚 Container Access

```bash
# Start an interactive shell
./vendor/bin/zero shell
./vendor/bin/zero bash
```

### 🐳 Docker Management

```bash
# Build the Docker image
./vendor/bin/zero build

# Rebuild without cache
./vendor/bin/zero build --no-cache
```

## ⚙️ Configuration

### Environment Variables

Create a `.env` file at your project root:

```env
# PHP version (8.2, 8.3, or 8.4)
PHP_VERSION=8.3

# UID/GID for file permissions (match your local user)
ZEROUSER=1000
ZEROGROUP=1000
```

### 🏷️ Multiple Projects

By default, all Laravel Zero projects using the same PHP version share the same Docker image (`zero-8.3/app`). This is efficient for most use cases since the image only contains PHP and system extensions, while your code and dependencies are mounted via volumes.

However, if you need to customize the Docker image for a specific project (e.g., adding extra PHP extensions), you can isolate it by setting `COMPOSE_PROJECT_NAME` in your `.env`:

```env
COMPOSE_PROJECT_NAME=my-project
```

This will create a separate image named `my-project-zero-8.3/app`, preventing conflicts with other projects.

### Docker Compose

The default `docker-compose.yml`:

```yaml
services:
    laravel.zero:
        build:
            context: ./vendor/dimer47/zero/runtimes/${PHP_VERSION:-8.3}
            dockerfile: Dockerfile
            args:
                ZEROGROUP: '${ZEROGROUP:-1000}'
                ZEROUSER: '${ZEROUSER:-1000}'
        image: zero-${PHP_VERSION:-8.3}/app
        extra_hosts:
            - 'host.docker.internal:host-gateway'
        environment:
            ZEROUSER: '${ZEROUSER:-1000}'
            ZEROGROUP: '${ZEROGROUP:-1000}'
        volumes:
            - '.:/var/www/html'
        networks:
            - zero
networks:
    zero:
        driver: bridge
```

## 🔧 How It Works

Unlike Laravel Sail which runs a persistent container, Zero uses **ephemeral containers** optimized for CLI workflows:

1. 📦 Each command spawns a new container
2. ⚡ The command executes
3. 🗑️ The container is automatically removed

This approach is more efficient for CLI applications where you run occasional commands rather than maintaining a web server.

### 🎯 Binary Auto-Detection

Zero automatically reads your `composer.json` to find your application binary:

```json
{
    "bin": ["my-app"]
}
```

This means `./vendor/bin/zero list` will execute `php my-app list` inside the container.

## 🔌 PHP Extensions

The Docker images include essential extensions for Laravel Zero:

- PDO / PDO MySQL
- Zip
- MBString
- Intl
- PCntl
- BCMath

The images also include useful system tools: `git`, `curl`, `jq` (JSON parsing), `composer`.

### Adding Extensions

To add more PHP extensions, create your own Dockerfile extending the base image, or copy the runtime to your project and customize it.

## 📚 Command Reference

| Command | Description |
|---------|-------------|
| `zero` | Display help |
| `zero list` | List all application commands |
| `zero <command>` | Run application command |
| `zero build` | Build Docker image |
| `zero php ...` | Run PHP command |
| `zero composer ...` | Run Composer command |
| `zero pest ...` | Run Pest tests |
| `zero pint ...` | Run Pint code fixer |
| `zero shell` | Start interactive shell |
| `zero test` | Run application tests |
| `zero app:build` | Build PHAR executable |
| `zero app:install` | Install optional components |
| `zero app:rename` | Rename application |
| `zero make:command` | Create new command |
| `zero make:test` | Create new test |

## ⚖️ Comparison with Laravel Sail

| Feature | Laravel Sail | Zero |
|---------|--------------|------|
| Target | Web applications | CLI applications |
| Container model | Persistent (up/stop) | Ephemeral (run/exit) |
| Base image | Ubuntu (~1GB) | Alpine (~50MB) |
| Services | MySQL, Redis, etc. | PHP only |
| Binary detection | `artisan` hardcoded | Auto from `composer.json` |

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This package is open-source software licensed under the [MIT license](LICENSE.md).

## 🙏 Credits

- Inspired by [Laravel Sail](https://github.com/laravel/sail) by Taylor Otwell
- Adapted for Laravel Zero by [Dimitri Iachi](https://github.com/dimer47)

## 🆘 Support

- [Laravel Zero Documentation](https://laravel-zero.com/)
- [GitHub Issues](https://github.com/dimer47/zero/issues)
