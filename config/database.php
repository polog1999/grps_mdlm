<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for database operations. This is
    | the connection which will be utilized unless another connection
    | is explicitly specified when you execute a query / statement.
    |
    */

    'default' => env('DB_CONNECTION', 'sqlite'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Below are all of the database connections defined for your application.
    | An example configuration is provided for each database system which
    | is supported by Laravel. You're free to add / remove connections.
    |
    */

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'laravel'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            'schema' => 'itse',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'itse',
            'sslmode' => 'prefer',
        ],
        'pgsql_licencias' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL_LICENCIAS'),
            'host' => env('DB_HOST_LICENCIAS', '127.0.0.1'),
            'port' => env('DB_PORT_LICENCIAS', '5432'),
            'database' => env('DB_DATABASE_LICENCIAS', 'laravel'),
            'username' => env('DB_USERNAME_LICENCIAS', 'root'),
            'password' => env('DB_PASSWORD_LICENCIAS', ''),
            'charset' => env('DB_CHARSET_LICENCIAS', 'utf8'),
            'schema' => 'licencia',
            'prefix' => '',
            'prefix_indexes' => true,
            'search_path' => 'licencia',
            'sslmode' => 'prefer',
        ],
        'oracle' => [
            'driver' => 'oracle',
            'tns' => env('DB_TNS', ''),
            'host' => env('DB_HOST_ORACLE_DESARROLLO', ''),
            'port' => env('DB_PORT_ORACLE_DESARROLLO', '1521'),
            'database' => env('DB_DATABASE_ORACLE_DESARROLLO', ''),
            'service_name' => env('DB_SERVICE_NAME_ORACLE_DESARROLLO', ''),
            'username' => env('DB_USERNAME_ORACLE_DESARROLLO', ''),
            'password' => env('DB_PASSWORD_ORACLE_DESARROLLO', ''),
            'charset' => env('DB_CHARSET_ORACLE_DESARROLLO', 'AL32UTF8'),
            'prefix' => env('DB_PREFIX_ORACLE_DESARROLLO', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX_ORACLE_DESARROLLO', ''),
            'edition' => env('DB_EDITION_ORACLE_DESARROLLO', 'ora$base'),
            'server_version' => env('DB_SERVER_VERSION_ORACLE_DESARROLLO', '11g'),
            'load_balance' => env('DB_LOAD_BALANCE_ORACLE_DESARROLLO', 'yes'),
            'max_name_len' => env('ORA_MAX_NAME_LEN', 30),
            'dynamic' => [],
            'sessionVars' => [
                'NLS_TIME_FORMAT' => 'HH24:MI:SS',
                'NLS_DATE_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
                'NLS_TIMESTAMP_FORMAT' => 'YYYY-MM-DD HH24:MI:SS',
                'NLS_TIMESTAMP_TZ_FORMAT' => 'YYYY-MM-DD HH24:MI:SS TZH:TZM',
                'NLS_NUMERIC_CHARACTERS' => '.,',
            ],
        ],
        'pgsql_gestrad' => [
            'driver' => 'pgsql',
            'url' => env('DB_URL_GESTRAD_POSTGRES'),
            'host' => env('DB_HOST_GESTRAD_POSTGRES', '127.0.0.1'),
            'port' => env('DB_PORT_GESTRAD_POSTGRES', '5432'),
            'database' => env('DB_DATABASE_GESTRAD_POSTGRES', 'laravel'),
            'username' => env('DB_USERNAME_GESTRAD_POSTGRES', 'root'),
            'password' => env('DB_PASSWORD_GESTRAD_POSTGRES', ''),
            'charset' => env('DB_CHARSET', 'utf8'),
            //'schema' => 'itse', 
            'prefix' => '',
            'prefix_indexes' => true,
            //'search_path' => 'itse',
            'sslmode' => 'prefer',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run on the database.
    |
    */

    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as Memcached. You may define your connection settings here.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_') . '_database_'),
            'persistent' => env('REDIS_PERSISTENT', false),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'username' => env('REDIS_USERNAME'),
            'password' => env('REDIS_PASSWORD'),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
            'max_retries' => env('REDIS_MAX_RETRIES', 3),
            'backoff_algorithm' => env('REDIS_BACKOFF_ALGORITHM', 'decorrelated_jitter'),
            'backoff_base' => env('REDIS_BACKOFF_BASE', 100),
            'backoff_cap' => env('REDIS_BACKOFF_CAP', 1000),
        ],

    ],

];
