# 🍕 Pizza Order System

Dieses Projekt ist eine Webanwendung zur Simulation eines Pizza-Bestellsystems.  
Es ermöglicht die Verwaltung von Bestellungen sowie die Darstellung verschiedener Benutzerrollen wie Kunde, Bäcker, Fahrer und auch die Kundenansicht.

---

## 🧠 Hintergrund

Dieses Projekt basiert auf einer vorgegebenen Grundstruktur aus dem Modul **Entwicklung Webbasierter Anwendungen**.  
Die bereitgestellte Umgebung (Docker-Setup, Datenbankstruktur und Basisarchitektur) wurde vom Dozenten zur Verfügung gestellt.

---

## ✨ Meine Beiträge

Ich habe das Projekt eigenständig erweitert und angepasst, insbesondere:

-  Implementierung und Anpassung der Logik im `src`-Verzeichnis
-  Entwicklung und Verbesserung von PHP-Seiten (z. B. Bestellung, Kunde, Fahrer)
-  Anpassung von Frontend-Komponenten (HTML, CSS, JavaScript)
-  Erweiterung der Funktionalität zur Bestellverarbeitung
- ️ Änderungen und Erweiterungen an der Datenbankstruktur und -inhalten

---

## 🛠️ Technologien

- PHP
- HTML / CSS / JavaScript
- MariaDB
- Docker & Docker Compose

---

## ▶️ Projekt starten

### Voraussetzungen
- Docker installiert

### Start

```bash
make start
```
### Andere Makefile-Kommandos

| Kommando       | Beschreibung                                |
|----------------|--------------------------------------------|
| `make console` | Shell im Apache-Container öffnen           |
| `make stop`    | Container stoppen                           |
| `make build`   | Container neu bauen inklusive MariaDB       |
| `make clean`   | Container löschen                           |
| `make cleanall`| Alle ungenutzten Docker-Images und Container löschen |

---

### 🗄️ Datenbank

- Zugriff über phpMyAdmin: [http://localhost:8085](http://localhost:8085)  
- Standard-Zugangsdaten:
  - User: `public`
  - Passwort: `public`

---

### 📁 Projektstruktur

- `src/` → Hauptanwendung (hier liegt der Fokus meiner Arbeit)  
- `mariadb/` → Datenbank-Setup  
- `php-apache/` → Webserver-Konfiguration  
- `docker-compose.yml` → Container-Anordnung und -Konfiguration
- `Makefile` → Steuerung der Docker-Container
