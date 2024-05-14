<?php

declare(strict_types=1);

session_start();
require(APP_DIR . '/Helpers/functions.php');

use App\Services\Container\App;
use App\Services\Database\Connection;
use App\Services\Database\QueryBuilder;
use App\Services\Session\Session;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(ROOT_DIR . '/.env');

App::bind(
    'database',
    new QueryBuilder(
        Connection::make([
            'connection' => 'mysql:host=' . $_ENV['DB_HOST'] ?? 'mariadb' . ';charset=utf8mb4',
            'name' => $_ENV['DB_NAME'] ?? 'feature_voting',
            'username' => $_ENV['DB_USER'] ?? 'feature_voting',
            'password' => $_ENV['DB_PASS'] ?? 'secret',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ],
        ])
    )
);

App::bind(
    'session',
    new Session()
);
