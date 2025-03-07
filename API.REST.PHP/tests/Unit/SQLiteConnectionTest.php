<?php

use App\SqliteConnection;

test('sqlite', function () {
    $db = SqliteConnection::getInstance();
    expect($db)->toBeInstanceOf(SqliteConnection::class);
    expect($db->getConnection())->not()->toBeNull();
});
