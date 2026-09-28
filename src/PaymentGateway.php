<?php

declare(strict_types=1);

namespace App;

/**
 * Die einzige Grenze zu einem echten Fremdsystem. Genau dieses Interface
 * wird im Integrationstest durch ein Double ersetzt, alles andere laeuft echt.
 */
interface PaymentGateway
{
    public function charge(string $reference, float $amount): PaymentResult;
}
