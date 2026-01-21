# Laravel Zero - Docker Wrapper

<p align="center">
    <img src="https://raw.githubusercontent.com/laravel-zero/docs/master/images/logo/laravel-zero-readme.png" alt="Laravel Zero" width="450">
</p>

Un package Docker minimaliste pour Laravel Zero, inspiré de Laravel Sail mais adapté aux applications CLI.

## ✨ Fonctionnalités

- 🐳 **Docker Alpine** ultra-léger (~50MB vs ~1GB)
- 🚀 **Multi-version PHP** : Support de PHP 8.2, 8.3 et 8.4
- 🎯 **Minimal** : Uniquement les extensions PHP essentielles pour Laravel Zero
- 📦 **Prêt à l'emploi** : Installation en une commande
- 🔧 **Flexible** : Compatible avec tous les projets Laravel Zero

## 📋 Prérequis

- Docker Desktop (macOS/Windows) ou Docker Engine (Linux)
- Docker Compose
- Un projet Laravel Zero

## 🚀 Installation

```bash
composer require dimer47/zero --dev
```

Publier les fichiers Docker :

```bash
php application zero:install
```

Cela créera :
- `docker/` - Runtimes Docker pour PHP 8.2, 8.3, 8.4
- `docker-compose.yml` - Configuration Docker Compose
- `zero` - Script wrapper pour exécuter les commandes

## 📖 Utilisation

### Commandes de base

```bash
# Démarrer le conteneur
./zero up -d

# Arrêter le conteneur
./zero stop

# Exécuter une commande Artisan
./zero artisan inspire
./zero artisan app:build

# Exécuter Composer
./zero composer install
./zero composer require vendor/package

# Exécuter PHP
./zero php -v
./zero php artisan list

# Shell interactif
./zero shell

# Aide
./zero help
```

### Multi-version PHP

Vous pouvez spécifier la version PHP dans votre `.env` :

```env
PHP_VERSION=8.4
ZEROUSER=1000
ZEROGROUP=1000
```

Ou la modifier dans `docker-compose.yml`.

### Build de l'image

```bash
# Build simple
./zero build

# Rebuild sans cache
./zero build --no-cache
```

## 🎨 Configuration

### Variables d'environnement

Créez un fichier `.env` à la racine de votre projet :

```env
# Version PHP (8.2, 8.3, ou 8.4)
PHP_VERSION=8.3

# UID/GID pour les permissions de fichiers
ZEROUSER=1000
ZEROGROUP=1000
```

### Structure des fichiers

```
votre-projet/
├── docker/
│   └── runtimes/
│       ├── 8.2/
│       ├── 8.3/
│       └── 8.4/
├── docker-compose.yml
├── zero (script wrapper)
└── .env
```

## 🔨 Développement

### Extensions PHP incluses

Les images Docker incluent uniquement les extensions essentielles pour Laravel Zero :

- PDO / PDO MySQL
- Zip
- MBString
- Intl
- PCntl
- BCMath

### Personnalisation

Pour ajouter des extensions PHP, éditez les `Dockerfiles` dans `docker/runtimes/{version}/`.

## 📝 Exemples

### Build d'un PHAR

```bash
./zero composer install
./zero artisan app:build mon-app
```

### Exécuter des tests

```bash
./zero pest
./zero phpunit
```

### Formatter le code

```bash
./zero pint
```

## 🤝 Contribution

Les contributions sont les bienvenues !

## 📄 Licence

Ce package est un logiciel open-source sous licence [MIT](LICENSE.md).

## 🙏 Crédits

- Inspiré de [Laravel Sail](https://github.com/laravel/sail) par Taylor Otwell
- Adapté pour Laravel Zero par [Dimitri Iachimoe](https://github.com/dimer47)

## 🆘 Support

- [Documentation Laravel Zero](https://laravel-zero.com/)
- [GitHub Issues](https://github.com/dimer47/zero/issues)
