<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    'mongodb_primary' => env('MONGO_DB_CONNECTION', 'mongodb'),
    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'sqlite_testing' => [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
        ],

        'mysql_sjpanel' => [
            'driver' => 'mysql',
            'host' => env('SJPANEL_DB_HOST', 'sjpanel.com'),
            'port' => env('SJPANEL_DB_PORT', '3306'),
            'database' => env('SJPANEL_DB_DATABASE', 'sjpanel_live_v2'),
            'username' => env('SJPANEL_DB_USERNAME', 'sjpanel_live_v2'),
            'password' => env('SJPANEL_DB_PASSWORD', '-eOG!MuZEGP&'),
            'unix_socket' => env('SJPANEL_DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
        ],
        'mongodb' => [
            'driver' => 'mongodb',
            'host' => env('MONGO_DB_HOST', 'localhost:81'),
            'port' => env('MONGO_DB_PORT', 27017),
            'database' => env('MONGO_DB_DATABASE','apace_temp'),
            'username' => env('MONGO_DB_USERNAME','root'),
            'password' => env('MONGO_DB_PASSWORD','root'),
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ],

        // 'mongodb' => [
        //     'driver' => 'mongodb',
        //     'dsn' => env('MONGO_DB_DSN', 'mongodb+srv://root:akash%400004@cluster0.ehihi.mongodb.net/test?retryWrites=true&w=majority'),
        //     'database' => env('MONGO_DB_DATABASE', 'homestead'),
        // ],

        'mongodb_live' => [
            'driver' => 'mongodb',
            'host' => env('MONGO_DB_LIVE_HOST', 'localhost:81'),
            'port' => env('MONGO_DB_LIVE_PORT', 27017),
            'database' => env('MONGO_DB_LIVE_DATABASE','apace'),
            'username' => env('MONGO_DB_LIVE_USERNAME','apace_mongo'),
            'password' => env('MONGO_DB_LIVE_PASSWORD','&bxBREW22re'),
            'options' => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

        // 'mongodb' => [
        //     'driver' => 'mongodb',
        //     'host' => env('MONGO_DB_HOST', 'localhost'),
        //     'port' => env('MONGO_DB_PORT', 27017),
        //     'database' => env('MONGO_DB_DATABASE','apace_mongo'),
        //     'username' => env('MONGO_DB_USERNAME','root'),
        //     'password' => env('MONGO_DB_PASSWORD','root'),
        //     'options' => [
        //         'database' => 'admin' // sets the authentication database required by mongo 3
        //     ]
        // ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
