<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\CheckoutService;
use App\EventLog;
use App\InventoryService;
use App\Order;
use App\OrderNotFoundException;
use App\OrderRepository;
use App\PaymentGateway;
use App\PaymentResult;
use App\Schema;
use Codeception\Test\Unit;
use PDO;

/**
 * Startpunkt Monat 7 – Integrationstest mit Codeception.
 *
 * Ziel: CheckoutService orchestriert DREI echte Komponenten (OrderRepository,
 * InventoryService, EventLog) gegen eine echte SQLite-Datenbank. Nur das
 * PaymentGateway wird an der Grenze durch ein Double ersetzt.
 *
 * Prüft am Ende den KOMBINIERTEN Zustand: Bestellung, Bestand und Event
 * müssen zusammenpassen. Siehe AUFGABE.md.
 */
final class CheckoutServiceTest extends Unit
{
    private PDO $pdo;
    private OrderRepository $orders;
    private InventoryService $inventory;
    private EventLog $events;

    protected function _before(): void
    {
        // TODO: frische SQLite-in-memory-DB, Fehlermodus auf Exception,
        //       Schema::create(...), die drei echten Komponenten bauen,
        //       Ausgangszustand anlegen:
        //         - offene Bestellung: Order(1, 'P1', 2, 49.90, 'open')
        //         - Bestand: INSERT INTO inventory (product, stock) VALUES ('P1', 10)
    }

    // PFLICHT: Happy Path
    public function testErfolgreicheZahlungAendertBestellungBestandUndEvent(): void
    {
        $this->markTestIncomplete('Gateway-Double approved. Prüfen: paid, Bestand 8, Event order_paid');
    }

    // PFLICHT: Fehlerfall
    public function testAbgelehnteZahlungLaesstBestandUnberuehrt(): void
    {
        $this->markTestIncomplete('Gateway-Double declined. Prüfen: failed, Bestand 10, Event payment_declined');
    }

    // BONUS: unbekannte Bestellung
    public function testUnbekannteBestellungAendertNichts(): void
    {
        $this->markTestIncomplete('pay(999): Exception, Gateway nie aufgerufen, nichts geändert');
    }
}
