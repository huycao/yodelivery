<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

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

        'testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ],

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => env('DB_DATABASE', storage_path('database.sqlite')),
            'prefix'   => env('DB_PREFIX', ''),
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', 'localhost'),
            'port'      => env('DB_PORT', 3306),
            'database'  => env('DB_DATABASE', 'test'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => env('DB_PREFIX', 'pt_'),
            'timezone'  => env('DB_TIMEZONE','+07:00'),
            'strict'    => false,
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'port'     => env('DB_PORT', 5432),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => env('DB_PREFIX', ''),
            'schema'   => 'public',
        ],

        'sqlsrv' => [
            'driver'   => 'sqlsrv',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'prefix'   => env('DB_PREFIX', ''),
        ],
        
        'mongodb' => array(
            'driver'   => 'mongodb',
            'host'     => '127.0.0.1',
            'port'     => '27017',
            'username' => '',
            'password' => '',
            'database' => 'yomedia',
            //'options'  =>   array('replicaSet' => 'rs0')
        ),

        'mongodb1' => array(
            'driver'   => 'mongodb',
            'host'     => '127.0.0.1',
            'port'     => '27018',
            'username' => '',
            'password' => '',
            'database' => 'yomedia',
        ),

        'mongodb2' => array(
            'driver'   => 'mongodb',
            'host'     => '127.0.0.1',
            'port'     => '27019',
            'username' => '',
            'password' => '',
            'database' => 'yomedia',
        ),

        'mongodb3' => array(
            'driver'   => 'mongodb',
            'host'     => '127.0.0.1',
            'port'     => '27020',
            'username' => '',
            'password' => '',
            'database' => 'yomedia',
        ),

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

        'cluster' => env('REDIS_CLUSTER', false),

        'default' => [
            'host'     => env('REDIS_HOST', '127.0.0.1'),
            'port'     => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DATABASE', 0),
            'password' => env('REDIS_PASSWORD', null)
        ],

    ],
    // Cau hinh cho redis sentinel
	// 'redis' => [
	//     //the name of the redis node set
 //        'nodeSetName' => env('NODE_SET_NAME', 'mymaster'),
 //        'cluster' => env('REDIS_CLUSTER', false),
 //        'masters' => [
 //            [
 //                'host' => env('REDIS_HOST_1', '127.0.0.1'),
 //                'port' => env('REDIS_PORT_1', 26379),
 //            ],
 //            [
 //                'host' => env('REDIS_HOST_2', '127.0.0.1'),
 //                'port' => env('REDIS_PORT_2', 26379),
 //            ]
 //        ],
 //        'backoff-strategy' => [
 //            'max-attempts' => env('MAX_ATTEMPTS', 10), // the maximum-number of attempt possible to find master
 //            'wait-time' => env('WAIT_TIME', 500),   // miliseconds to wait for the next attempt
 //            'increment' => env('INCREMENT', '1.5'), // multiplier used to increment the back off time on each try
 //        ]
 //    ],

];
