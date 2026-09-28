<?php

declare(strict_types=1);

namespace App;

/**
 * Orchestriert VIER Mitspieler, davon drei echt und einer an der Grenze:
 *   - OrderRepository  (echt, DB)
 *   - InventoryService (echt, DB)
 *   - EventLog         (echt, DB)
 *   - PaymentGateway   (Fremdsystem, im Test ein Double)
 *
 * Genau das Zusammenspiel dieser Komponenten ist das Ziel des Integrationstests.
 * Ein Fehler an einer der Nahtstellen (z. B. Bestand trotz abgelehnter Zahlung
 * gesenkt) faellt nur auf, wenn man den kombinierten Zustand prueft.
 */
final class CheckoutService
{
    public function __construct(
        private OrderRepository $orders,
        private InventoryService $inventory,
        private EventLog $events,
        private PaymentGateway $gateway,
    ) {
    }

    public function pay(int $orderId): PaymentResult
    {
        $order = $this->orders->findById($orderId);
        if ($order === null) {
            throw new OrderNotFoundException("Bestellung nicht gefunden: {$orderId}");
        }

        $result = $this->gateway->charge("order-{$orderId}", $order->getAmount());

        if ($result->successful) {
            $this->orders->updateStatus($orderId, 'paid');
            $this->inventory->decrement($order->getProductId(), $order->getQuantity());
            $this->events->record('order_paid', $orderId);
        } else {
            $this->orders->updateStatus($orderId, 'failed');
            $this->events->record('payment_declined', $orderId);
            // Bestand wird bewusst NICHT angefasst
        }

        return $result;
    }
}
