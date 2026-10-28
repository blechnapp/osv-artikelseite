# Änderungen

## 0.1.0 (28.10.2026)
- Zubehör aus Eigenschaft 302 als kompakte Liste unter dem Warenkorb-Knopf, je Zeile mit dem Ceres-Warenkorb-Baustein.
- Empfehlungen aus Eigenschaft 303 als Kachelreihe (Rechner 4 nebeneinander, Handy Wischzeile), `*` vor der ID zeigt „Oft zusammen gekauft“.
- Nur bestellbare Varianten mit Preis, höchstens 4 je Liste (einstellbar). Beim Wechsel der Ausführung tauschen beide Listen mit.
- Ceres-Artikellisten der Artikelseite weichen nur, wenn der Artikel eigene Werte hat.
- Namen unter den Ausführungs-Kacheln, Vorschaubilder links (Rechner), Brotkrumen folgen der Ausführung.
- GA4: `view_item_list` und `select_item` je Liste über den dataLayer.
- Route `/rest/osv-artikelseite/listen?ids=…` (zwei Suchabfragen, nur lesend, Zwischenspeicher einstellbar).
