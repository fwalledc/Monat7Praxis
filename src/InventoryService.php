<?php

declare(strict_types=1);

namespace App;

use PDO;

/** Echte Komponente 2: Lagerbestand fuehren. */
final class InventoryService
{
    public function __construct(private PDO $pdo)
    {
    }

    public function stockFor(string $product): int
    {
        $stmt = $this->pdo->prepare('SELECT stock FROM inventory WHERE product = :p');
        $stmt->execute(['p' => $product]);
        $stock = $stmt->fetchColumn();

        return $stock === false ? 0 : (int) $stock;
    }

    public function decrement(string $product, int $quantity): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE inventory SET stock = stock - :q WHERE product = :p'
        );
        $stmt->execute(['q' => $quantity, 'p' => $product]);
    }
}
