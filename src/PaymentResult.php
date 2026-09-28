<?php

declare(strict_types=1);

namespace App;

final class PaymentResult
{
    private function __construct(
        public readonly bool $successful,
        public readonly ?string $reason = null,
    ) {
    }

    public static function approved(): self { return new self(true); }
    public static function declined(string $reason): self { return new self(false, $reason); }
}
