# Monat 7 – Integration Testing mit Codeception

Übung der DC AG Testing-Schulungsserie.

## Worum geht es

`CheckoutService` orchestriert drei echte Komponenten (OrderRepository,
InventoryService, EventLog) gegen eine echte Datenbank. Nur das externe
`PaymentGateway` wird an der Grenze durch ein Double ersetzt. Ihr prüft den
kombinierten Zustand über alle drei Komponenten.

Die Aufgabenstellung steht in **AUFGABE.md**.

## Struktur

```
src/                              die App-Klassen
tests/
  Integration.suite.yml           eigene Integration-Suite (kein Framework)
  Integration/CheckoutServiceTest.php   Startpunkt zum Ausfüllen
```

## Setup

```bash
composer install
vendor/bin/codecept build
vendor/bin/codecept run Integration
```

Voraussetzung: PHP 8.2+, ext-pdo_sqlite.
