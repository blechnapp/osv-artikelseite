# OSV Artikelseite

Gestaltet die Artikelseite (Einzelansicht) des Ceres-Shops. Jeder Teil ist in der Plugin-Konfiguration einzeln schaltbar.

## Einrichtung
1. Plugin im Plugin-Set installieren und bereitstellen.
2. Container verknüpfen:
   - „OSV Artikelseite Kopf“ → `Ceres::Template.Style`
   - „OSV Artikelseite“ → `Ceres::Script.AfterScriptsLoaded`
3. Eigenschaften 302 (Zubehör) und 303 (Empfehlungen) einstellen wie 288–290: Mandant, Herkunft „Mandant (Shop)“ und Anzeige „Artikeleinzelseite“. Sonst liefert Ceres die Werte nicht. Sichtbar werden sie dadurch nicht (geprüft an 288–290).

## Daten
- 302: bis zu 6 Varianten-IDs, kommagetrennt. Gezeigt werden höchstens 4 bestellbare.
- 303: bis zu 6 Varianten-IDs, `*` vor der ID = oft zusammen gekauft. Gezeigt werden höchstens 4 bestellbare.
- Gepflegt werden die Werte vom Items-Tool. 304 bis 306 („Zubehör für“) liest das Plugin nicht.

## Teile
- Zubehör und Empfehlungen
- Namen unter den Ausführungen
- Vorschaubilder links (Rechner)
- Brotkrumen folgen der gewählten Ausführung

Kein MutationObserver, kein Vue.component, kein Eingriff in den Ceres-Speicher.
