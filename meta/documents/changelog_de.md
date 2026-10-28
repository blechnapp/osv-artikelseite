# Änderungen

## 0.2.4 (28.10.2026)
- Rechner wie im Entwurf: Inhalt auf 1.320 px mit Rand, warmer Seitengrund (#faf7f2), Abstand zwischen Bild und Kaufbox, Bild mit leichtem Schatten, Abschnitte mit Schatten. Bildpfeile bleiben wie bei Ceres.

## 0.2.3 (28.10.2026)
- Klappkarten werden 20 s lang gehalten: baut Ceres den Reiter-Baustein neu auf, stellt das Skript sie wieder her (ohne doppelte Überschriften). Geprüft an a-966, a-1067, a-205, Rechner und Handy.

## 0.2.2 (28.10.2026)
- Klappkarten auch bei Artikeln, deren Reiter Ceres erst nach über 5 s aufbaut (a-205, a-1067): Nachfassen alle 0,4 s bis fertig, höchstens 16 s.

## 0.2.1 (28.10.2026)
- Klappkarten auch, wenn Ceres die Reiter erst nach dem Skript aufbaut (nachfassen wie bei den Namen).

## 0.2.0 (28.10.2026)
- Aufbau wie im Entwurf (`artikelseite_20261028/vorschau.html`), schaltbar über „Aufbau wie im Entwurf“:
  - Kaufbox als Karte; Marke, Titel mit Ausführung, Bewertung, Preis mit UVP, Lieferbarkeit, Ausführungen, Warenkorb, Zubehör, Versandhinweis, Artikelnummer in dieser Reihenfolge (nur CSS-Reihenfolge, kein Umbau im DOM).
  - Ausführungen erst eine Reihe (Rechner 5, Handy 4), gewählte immer in der ersten Reihe, Knopf „Alle n Ausführungen zeigen“.
  - Reiter werden Klappkarten über die volle Breite; leere Bereiche entfallen; Rechner offen außer Pflichtangaben, Handy nur Beschreibung offen.
  - Handy: Hauptbild 300 px statt 70vh (Shop-CSS), Vorschaubilder als Wischzeile. Rechner: Bild läuft beim Scrollen mit.
  - Warenkorb-Knopf dunkelblau, leere Ceres-Bausteine ohne Abstand.

## 0.1.3 (28.10.2026)
- 302/303 kommen jetzt aus den Ceres-Daten der Artikelseite (variationProperties, wie OSVCustomMeta 288/289), beim Wechsel aus dem Ereignis onVariationChanged. Die Listen-Suche der Route lieferte die Eigenschaften nicht.
- Route `?t=` liefert nur noch Kacheldaten, nur für fehlende Varianten.
- Ceres-Listen werden je Ausführung aus- bzw. wieder eingeblendet.

## 0.1.2 (28.10.2026)
- Namen unter den Ausführungen auch beim ersten Laden (Ceres baut die Kacheln erst nach dem Skript auf, kurzes Nachfassen).
- Anleitung: 302/303 brauchen Mandant, Herkunft „Mandant (Shop)“ und Anzeige „Artikeleinzelseite“ wie 288–290, sonst liefert Ceres sie nicht.

## 0.1.1 (28.10.2026)
- Bereitstellen schlug fehl: `method_exists` ist in Plenty-Plugins nicht erlaubt. Ersetzt durch direkten Aufruf mit Fehlerabfang.

## 0.1.0 (28.10.2026)
- Zubehör aus Eigenschaft 302 als kompakte Liste unter dem Warenkorb-Knopf, je Zeile mit dem Ceres-Warenkorb-Baustein.
- Empfehlungen aus Eigenschaft 303 als Kachelreihe (Rechner 4 nebeneinander, Handy Wischzeile), `*` vor der ID zeigt „Oft zusammen gekauft“.
- Nur bestellbare Varianten mit Preis, höchstens 4 je Liste (einstellbar). Beim Wechsel der Ausführung tauschen beide Listen mit.
- Ceres-Artikellisten der Artikelseite weichen nur, wenn der Artikel eigene Werte hat.
- Namen unter den Ausführungs-Kacheln, Vorschaubilder links (Rechner), Brotkrumen folgen der Ausführung.
- GA4: `view_item_list` und `select_item` je Liste über den dataLayer.
- Route `/rest/osv-artikelseite/listen?ids=…` (zwei Suchabfragen, nur lesend, Zwischenspeicher einstellbar).
