@setup
    Dotenv\Dotenv::createImmutable(__DIR__)->load();

    $production = $production ?? env('SSH_PRODUCTION', 'root@127.0.0.1');
    $development = $development ?? env('SSH_DEVELOPMENT', 'root@127.0.0.1');
    $on = $on ? explode(',', $on) : null;
@endsetup

@servers(['local' => ['localhost'], 'production' => [$production], 'development' => [$development]])

@story('deploy')
    test
    build
    publish
@endstory

@task('test', ['on' => 'local'])
    echo "Searching code style issues..."
    vendor/bin/pint --test --format=txt || ret=$?

    echo "Runnunt tests..."
    vendor/bin/phpunit --no-progress || ret=$?

    echo "Runnunt static analysis..."
    vendor/bin/phpstan --memory-limit=512M --no-progress || ret=$?

    echo "Running eslint..."
    npm run lint || ret=$?

    return $ret || 0
@endtask

@task('build', ['on' => 'local'])
    echo "Building..."
    npm ci
    npm run build
    npm run build -- --ssr
@endtask

@task('publish', ['on' => $on, 'confirm' => !$force])
    php artisan down

    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

    php artisan optimize
    php artisan storage:link
    php artisan migrate --force

    php artisan up
@endtask
