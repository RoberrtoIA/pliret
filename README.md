# API de evaluación de empleados

**Proyecto de graduación**

Esta API proporciona una API RESTful para evaluar el desempeño de los empleados de una empresa. La API está construida con Laravel 9 y utiliza tokens de acceso para la autenticación y autorización.

## Objetivos

* Proporcionar una API RESTful para evaluar el desempeño de los empleados.
* Usar tokens de acceso para la autenticación y autorización.
* Incluir pruebas unitarias para garantizar la calidad del código.

## Características

* Seguridad con tokens de acceso.
* Pruebas unitarias.
* Documentación.

## Documentación

La documentación de la API se encuentra en el siguiente enlace:

**Autor:**

robertoantoniomoreno1999@gmail.com

## Contenido

* [Demostración de la API](#api-demo)
* [Uso de la demostración](#demo-usage)
* [Instalación](#install)
* [Seeder de demostración](#demo-seeder)

## Demostración de la API

Puedes encontrar una colección de Postman para la demostración de la API en la carpeta `demo`.

[Importa el archivo JSON](https://learning.postman.com/docs/getting-started/importing-and-exporting-data/#importing-postman-data).

## Uso de la demostración

Inicia sesión con todos los usuarios de demostración (ver [Seeder de demostración](#demo-seeder)) y rellena los campos `token` correspondientes en las `variables de colección`.

## Instalación

Se requieren PHP 8.1+, Composer y MySQL 8+.

Clona el proyecto e instala las dependencias.

```bash
git clone https://gitlab.com/applaudo-php-tp-2022/final-project
cd final-project
composer install
```

Copiar el archivo `.env.example` y rellenar con las variables de entorno requeridas.

```bash
cp .env.example .env
```

Ejecuta los siguientes comandos

```bash
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
```

El seeder es necesario para el conjunto de datos inicial

### Demo Seeder 
Puedes usar el seeder de demostración para el contexto de la demostracion de la [API Demo](#api-demo) .

```bash
php artisan migrate:fresh \
    --seeder=Database\\Seeders\\Sample\\SampleSeeder
```
