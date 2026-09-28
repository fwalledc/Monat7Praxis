<?php

declare(strict_types=1);

namespace App;

use PDO;

final class Schema
{
    public static function create(PDO $pdo): void
    {
        $pdo->exec('CREATE TABLE orders (
            id       INTEGER PRIMARY KEY,
            product  TEXT NOT NULL,
            quantity INTEGER NOT NULL,
            amount   REAL NOT NULL,
            status   TEXT NOT NULL DEFAULT "open"
        )');
        $pdo->exec('CREATE TABLE inventory (
            product TEXT PRIMARY KEY,
            stock   INTEGER NOT NULL
        )');
        $pdo->exec('CREATE TABLE events (
            id       INTEGER PRIMARY KEY AUTOINCREMENT,
            type     TEXT NOT NULL,
            order_id INTEGER NOT NULL
        )');
    }
}
