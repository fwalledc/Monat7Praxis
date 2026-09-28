<?php

declare(strict_types=1);

namespace App;

use PDO;

/** Echte Komponente 1: Bestellungen lesen und ihren Status schreiben. */
final class OrderRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    public function save(Order $order): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO orders (id, product, quantity, amount, status)
             VALUES (:id, :product, :qty, :amount, :status)'
        );
        $stmt->execute([
            'id' => $order->getId(),
            'product' => $order->getProductId(),
            'qty' => $order->getQuantity(),
            'amount' => $order->getAmount(),
            'status' => $order->getStatus(),
        ]);
    }

    public function findById(int $id): ?Order
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, product, quantity, amount, status FROM orders WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return new Order(
            (int) $row['id'],
            $row['product'],
            (int) $row['quantity'],
            (float) $row['amount'],
            $row['status'],
        );
    }

    public function updateStatus(int $id, string $status): void
    {
        $stmt = $this->pdo->prepare('UPDATE orders SET status = :status WHERE id = :id');
        $stmt->execute(['status' => $status, 'id' => $id]);
    }
}
