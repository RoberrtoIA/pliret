# API de evaluación de empleados

**Autor:**

robertoantoniomoreno1999@gmail.com

## Content
- [API Demo](#api-demo)
- [Demo Usage](#demo-usage)
- [Install](#install)
- [Demo Seeder](#demo-seeder)


## API Demo

You can find a [Postman](https://www.postman.com/) collection for API demo in `demo` folder.

[Import json file](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/#importing-postman-data).

## Demo Usage

Login with all demo users (see [Demo Seeder](#demo-seeder)) and fill the
respective `token` fields in the `collection variables`.

## Install

PHP 8.1+, composer and MySQL8+ are required.

Clone the project and install dependencies.

```bash
git clone https://gitlab.com/applaudo-php-tp-2022/final-project
cd final-project
composer install
```

Copy `.env.example` and fill environment variables as needed.

```bash
cp .env.example .env
```

Run following commands

```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

Seed is required for initial data set.

### Demo Seeder 
You can use demo seeder for [API Demo](#api-demo) context.

```bash
php artisan migrate:fresh \
    --seeder=Database\\Seeders\\Sample\\SampleSeeder
```
