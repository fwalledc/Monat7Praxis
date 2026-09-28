<?php

declare(strict_types=1);

namespace App;

use PDO;

/** Echte Komponente 3: fachliche Ereignisse protokollieren. */
final class EventLog
{
    public function __construct(private PDO $pdo)
    {
    }

    public function record(string $type, int $orderId): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO events (type, order_id) VALUES (:type, :order_id)'
        );
        $stmt->execute(['type' => $type, 'order_id' => $orderId]);
    }

    /** @return list<string> die Event-Typen zu einer Bestellung, in Reihenfolge */
    public function typesFor(int $orderId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT type FROM events WHERE order_id = :id ORDER BY id'
        );
        $stmt->execute(['id' => $orderId]);

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
