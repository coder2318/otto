[![OTTO Story](/resources/src/assets/img/logo-row.svg)](https://ottostory.com/)

<p align="center">
<a href="https://www.figma.com/file/n9LiFWc7R2tCtpwPqgHkwU/OttoStory"><img src="https://img.shields.io/badge/Figma-blue?logo=figma" alt="Bot API Version"></a>
<a href="https://forge.laravel.com/servers/702931/sites/2048436"><img src="https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F8fec8d98-905f-4819-a383-c331aab09963&style=flat" alt="Laravel Forge Site Deployment Status"></a>
<a href="https://github.com/westacks/otto/actions/workflows/laravel.yml"><img src="https://github.com/westacks/otto/actions/workflows/laravel.yml/badge.svg" alt="Laravel"></a>
</p>

## About

AI writing platform made with

-   Laravel
-   Svelte
-   Inertia.js
-   TailwindCSS (DaisyUI)

## Requirements

-   Docker
-   Composer

## Installation

```sh
# configure environment
cp .env.example .env

# install dependencies
composer install --ignore-platform-reqs

# start docker environment
vendor/bin/sail up -d

# remove docker environment
vendor/bin/sail down --rmi all

# install application
vendor/bin/sail composer install
vendor/bin/sail bun install
vendor/bin/sail bun run build

# run the app for the first time with a clean database
# first, add stripe properties to .env file
vendor/bin/sail artisan migrate:fresh --seed

# migrate db and link storage
vendor/bin/sail artisan db:seed
vendor/bin/sail artisan storage:link
```

you can configure alias to avoid typing vendor/bin/sail every time
https://laravel.com/docs/11.x/sail#configuring-a-shell-alias
alias sail='sh $([ -f sail ] && echo sail || echo vendor/bin/sail)'

in order to configure local s3 storage (minio) login to localhost:8900 with user:sail Password:password. You need to create a key and a bucket, put them to appripriate AWS_.. variables

# run js in dev mode 
vendor/bin/sail bun run dev

# restart containers
vendor/bin/sail restart

## Access points

-   Application: http://localhost/
-   Database Explorer: http://localhost:3000/
-   Minio S3 Storage: http://localhost:8900/
  - MINIO_ROOT_USER: sail
  - MINIO_ROOT_PASSWORD: password

-   Mailpit Emails: http://localhost:8025/
