<?php

declare(strict_types=1);

namespace App\Services\Database;

use PDO;
use PDOException;

class Connection
{
    public static function make($config)
    {
        try {
            return new PDO(
                $config['connection'] . ';dbname=' . $config['name'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (PDOException $e) {
            die('Could not connect: ' . $e->getMessage());
        }
    }
}
