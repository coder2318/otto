[![OTTO Story](/resources/src/assets/img/logo-row.svg)](https://ottostory.com/)

<p align="center">
<a href="https://forge.laravel.com/servers/702931/sites/2048436"><img src="https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F8fec8d98-905f-4819-a383-c331aab09963&style=flat" alt="Laravel Forge Site Deployment Status"></a>
<a href="https://github.com/westacks/otto/actions/workflows/laravel.yml"><img src="https://github.com/westacks/otto/actions/workflows/laravel.yml/badge.svg" alt="Laravel"></a>
</p>

## About

AI writing platform made with
- Laravel
- Svelte
- Inertia.js
- TailwindCSS (DaisyUI)

## Requirements

- Docker
- Composer

## Installation

```sh
# configure environment
cp .env.example .env

# install dependencies
composer install --ignore-platform-reqs

# start docker environment
vendor/bin/sail up -d

# install application
vendor/bin/sail composer install
vendor/bin/sail npm install
vendor/bin/sail npm run build
```

## Access points

- Application: http://localhost/
- Database Explorer: http://localhost:3000/
- Minio S3 Storage: http://localhost:9000/
- Mailpit Emails: http://localhost:8025/
