<?php

declare(strict_types=1);

$mysqlSslCa = env('MYSQL_ATTR_SSL_CA');
$mysqlSslCaAttribute = defined('Pdo\Mysql::ATTR_SSL_CA')
    ? constant('Pdo\Mysql::ATTR_SSL_CA')
    : constant('PDO::MYSQL_ATTR_SSL_CA');

return [
    'default' => env('DB_CONNECTION', 'mariadb'),
    'connections' => [
        'mariadb' => [
            'driver' => 'mariadb',
            'url' => env('DB_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'jobportal'),
            'username' => env('DB_USERNAME', 'jobportal'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
            'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') && filled($mysqlSslCa) ? [
                $mysqlSslCaAttribute => $mysqlSslCa,
            ] : [],
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DB_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            'busy_timeout' => null,
            'journal_mode' => null,
            'synchronous' => null,
        ],
    ],
    'migrations' => [
        'table' => 'migrations',
        'update_date_on_publish' => true,
    ],
];
