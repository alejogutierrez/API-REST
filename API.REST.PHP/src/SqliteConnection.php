<?php

namespace App;

use PDO;
use PDOException;

class SqliteConnection implements DbConnInterface
{
    private static $instance;
    final private function __construct() {}

    static function getInstance(): SqliteConnection
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        $dbFile = "sqlite:file:" . __DIR__ . "/app.sqlite";
        try {
            $connection = new PDO($dbFile);
            $connection->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
            return $connection;
        } catch (PDOException $e) {
            die("ERROR: " . $e->getMessage());
        }
    }

    public function getInsertId(PDO $conn): int
    {
        return $conn->lastInsertId();
    }
}
