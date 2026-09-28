<?php

declare(strict_types=1);

namespace App;

final class Order
{
    public function __construct(
        private int $id,
        private string $productId,
        private int $quantity,
        private float $amount,
        private string $status = 'open',
    ) {
    }

    public function getId(): int { return $this->id; }
    public function getProductId(): string { return $this->productId; }
    public function getQuantity(): int { return $this->quantity; }
    public function getAmount(): float { return $this->amount; }
    public function getStatus(): string { return $this->status; }
}
