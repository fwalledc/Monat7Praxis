# Monat 7 – Praxisaufgabe: Integrationstest mit Codeception

> PHP 8.2+ / Codeception 5 / eigene Integration-Suite / ext-pdo_sqlite

## Ausgangslage

Ein `CheckoutService` orchestriert beim Bezahlen VIER Mitspieler:

- `OrderRepository`  – liest die Bestellung, schreibt ihren Status (echt, DB)
- `InventoryService` – senkt den Lagerbestand (echt, DB)
- `EventLog`         – protokolliert ein Ereignis (echt, DB)
- `PaymentGateway`   – die Grenze zum externen Zahlungsdienst (ein Interface)

## Ziel

Schreibt einen **Integrationstest** in der Suite `Integration`. Drei dieser
Komponenten laufen ECHT zusammen gegen eine echte (In-Memory-)Datenbank. Nur
das `PaymentGateway` wird an der Außengrenze durch ein Double ersetzt.

Der Kern: Ihr prüft nicht eine einzelne Wirkung, sondern den KOMBINIERTEN
Zustand über alle drei echten Komponenten hinweg.

## Schritt 1 – _before() aufbauen

Frische DB, Schema, die drei echten Komponenten, und der Ausgangszustand:

```php
$this->pdo = new PDO('sqlite::memory:');
$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
Schema::create($this->pdo);

$this->orders    = new OrderRepository($this->pdo);
$this->inventory = new InventoryService($this->pdo);
$this->events    = new EventLog($this->pdo);

$this->orders->save(new Order(1, 'P1', 2, 49.90, 'open'));
$this->pdo->exec("INSERT INTO inventory (product, stock) VALUES ('P1', 10)");
```

## Schritt 2 – Happy Path

Gateway-Double liefert `PaymentResult::approved()`. Nach `pay(1)` prüft ihr
ALLE drei Wirkungen zusammen:

- Bestellung 1 hat den Status `paid`
- Bestand von P1 ist von 10 auf **8** gesunken (2 Stück)
- zur Bestellung existiert genau das Event `order_paid`

## Schritt 3 – Fehlerfall

Gateway-Double liefert `PaymentResult::declined('insufficient_funds')`. Nach
`pay(1)`:

- Bestellung 1 ist `failed`
- Bestand von P1 ist **unverändert 10** (nichts halb gebucht)
- Event ist `payment_declined`

Genau diese Bestandsprüfung im Fehlerfall ist der Kern: Sie fängt einen Bug an
der Nahtstelle, den ein Unit-Test mit lauter Mocks nie sehen würde.

## Bonus – Unbekannte Bestellung

`pay(999)` wirft `OrderNotFoundException`, das Gateway wird nie aufgerufen, und
weder Bestand noch Events ändern sich.

## Ausführen

```bash
composer install
vendor/bin/codecept build
vendor/bin/codecept run Integration
```

## Lernziel

Der Unterschied zwischen "eine Wirkung geprüft" und "Zusammenspiel geprüft".
Ein Integrationstest zeigt, dass mehrere echte Komponenten gemeinsam den
richtigen Zustand erzeugen, und deckt Fehler an ihren Nahtstellen auf.
