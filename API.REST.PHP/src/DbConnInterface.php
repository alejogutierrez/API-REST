<?php

namespace App;

use PDO;

interface DbConnInterface
{
    public static function getInstance();
    public function getConnection(): PDO;
    public function getInsertId(PDO $conn): int;
}
