# Änderungen

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
