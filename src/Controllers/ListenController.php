<?php

namespace OSVArtikelseite\Controllers;

use Plenty\Modules\Webshop\ItemSearch\SearchPresets\VariationList;
use Plenty\Modules\Webshop\ItemSearch\Services\ItemSearchService;
use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Controller;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\Http\Response;

/**
 * Liefert für die Varianten eines Artikels die Listen aus Eigenschaft 302 (Zubehör) und 303 (Empfehlungen)
 * und die Kacheldaten der genannten Varianten. Zwei Suchabfragen, nur lesend.
 * Antwort: {"v": {"6955": {"z": [2866, ...], "e": [[1603, 0], ...]}}, "t": {"2866": {...}}, "d": {...}}
 */
class ListenController extends Controller
{
    const ZUBEHOER = 302;
    const EMPFEHLUNGEN = 303;
    const MAX_VARIANTEN = 80;
    const MAX_KACHELN = 200;

    public function listen(Request $request, Response $response, ConfigRepository $config)
    {
        $start = microtime(true);
        $minuten = max(0, (int)$config->get('OSVArtikelseite.cacheMinuten', '15'));

        // Ab 0.1.3: Das Skript liest 302/303 aus den Ceres-Daten der Artikelseite (wie OSVCustomMeta 288/289)
        // und fragt hier nur noch die Kacheldaten der genannten Varianten ab (?t=2866,1313,...).
        $nurKacheln = $this->ids((string)$request->get('t', ''), self::MAX_KACHELN);
        if (count($nurKacheln)) {
            /** @var ItemSearchService $suche */
            $suche = pluginApp(ItemSearchService::class);
            $kacheln = [];
            $factory = VariationList::getSearchFactory(['variationIds' => $nurKacheln, 'itemsPerPage' => count($nurKacheln)]);
            $ergebnis = $suche->getResult($factory);
            foreach (($ergebnis['documents'] ?? []) as $dokument) {
                $d = $dokument['data'] ?? [];
                $id = (int)($d['variation']['id'] ?? 0);
                if ($id) {
                    $kacheln[$id] = $this->kachel($d);
                }
            }
            $body = json_encode([
                't' => (object)$kacheln,
                'd' => ['n' => count($nurKacheln), 'ms' => (int)round((microtime(true) - $start) * 1000)],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            return $response->make($body, 200, [
                'Content-Type'  => 'application/json; charset=utf-8',
                'Cache-Control' => $minuten > 0 ? 'public, max-age=' . ($minuten * 60) : 'no-store',
            ]);
        }

        $ids = $this->ids((string)$request->get('ids', ''), self::MAX_VARIANTEN);
        $listen = [];
        $ziele = [];
        $diag = ['n' => count($ids)];

        if (count($ids)) {
            /** @var ItemSearchService $suche */
            $suche = pluginApp(ItemSearchService::class);

            // 1. Eigenschaften der Varianten des Artikels
            $factory = VariationList::getSearchFactory(['variationIds' => $ids, 'itemsPerPage' => count($ids)]);
            // method_exists ist in Plenty-Plugins nicht erlaubt (Build 28.10.2026), deshalb direkt mit Fehlerabfang
            try {
                $factory->withVariationProperties();
                $diag['wvp'] = true;
            } catch (\Throwable $fehler) {
                $diag['wvp'] = false;
            }
            $ergebnis = $suche->getResult($factory);
            $diag['docs'] = count($ergebnis['documents'] ?? []);
            foreach (($ergebnis['documents'] ?? []) as $dokument) {
                $d = $dokument['data'] ?? [];
                $vid = (int)($d['variation']['id'] ?? 0);
                if (!$vid) {
                    continue;
                }
                if (!isset($diag['felder'])) {
                    $diag['felder'] = array_keys($d);
                }
                $werte = $this->eigenschaften($d);
                if (!isset($diag['pids'])) {
                    $diag['pids'] = array_keys($werte);
                }
                $z = $this->ids($werte[self::ZUBEHOER] ?? '', 6);
                $e = [];
                foreach (explode(',', $werte[self::EMPFEHLUNGEN] ?? '') as $teil) {
                    $teil = trim($teil);
                    $stern = strpos($teil, '*') === 0 ? 1 : 0;
                    $id = (int)ltrim($teil, '*');
                    if ($id > 0 && count($e) < 6) {
                        $e[] = [$id, $stern];
                    }
                }
                if (count($z) || count($e)) {
                    $listen[$vid] = ['z' => $z, 'e' => $e];
                    foreach ($z as $id) {
                        $ziele[$id] = true;
                    }
                    foreach ($e as $paar) {
                        $ziele[$paar[0]] = true;
                    }
                }
            }

            // 2. Kacheldaten der genannten Varianten
            $kacheln = [];
            $zielIds = array_slice(array_keys($ziele), 0, self::MAX_KACHELN);
            if (count($zielIds)) {
                $factory = VariationList::getSearchFactory(['variationIds' => $zielIds, 'itemsPerPage' => count($zielIds)]);
                $ergebnis = $suche->getResult($factory);
                foreach (($ergebnis['documents'] ?? []) as $dokument) {
                    $d = $dokument['data'] ?? [];
                    $id = (int)($d['variation']['id'] ?? 0);
                    if ($id) {
                        $kacheln[$id] = $this->kachel($d);
                    }
                }
            }
        }

        $diag['ms'] = (int)round((microtime(true) - $start) * 1000);
        $body = json_encode([
            'v' => (object)$listen,
            't' => (object)($kacheln ?? []),
            'd' => $diag,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return $response->make($body, 200, [
            'Content-Type'  => 'application/json; charset=utf-8',
            'Cache-Control' => $minuten > 0 ? 'public, max-age=' . ($minuten * 60) : 'no-store',
        ]);
    }

    /** Kommagetrennte Zahlen, ohne Doppelte, höchstens $max */
    private function ids(string $text, int $max): array
    {
        $out = [];
        foreach (explode(',', $text) as $teil) {
            $id = (int)trim($teil);
            if ($id > 0 && !in_array($id, $out, true) && count($out) < $max) {
                $out[] = $id;
            }
        }
        return $out;
    }

    /**
     * Werte der Variationseigenschaften als [propertyId => Text].
     * Ceres liefert sie gruppiert: variationProperties[].properties[] mit values.value.
     */
    private function eigenschaften(array $d): array
    {
        $out = [];
        foreach (($d['variationProperties'] ?? []) as $gruppe) {
            foreach (($gruppe['properties'] ?? []) as $p) {
                $pid = (int)($p['id'] ?? ($p['propertyId'] ?? ($p['names']['propertyId'] ?? 0)));
                $wert = $p['values']['value'] ?? ($p['values'][0]['value'] ?? null);
                if ($pid && is_string($wert)) {
                    $out[$pid] = $wert;
                }
            }
        }
        return $out;
    }

    /** Kacheldaten wie die Ceres-Kategoriekachel, dazu die Angaben für den Ceres-Baustein add-to-basket (wie OSVSuche) */
    private function kachel(array $d): array
    {
        $itemId = $d['item']['id'] ?? 0;
        $vid = $d['variation']['id'] ?? 0;
        $bild = $d['images']['variation'][0]['urlMiddle'] ?? ($d['images']['all'][0]['urlMiddle'] ?? '');
        $altText = $d['images']['variation'][0]['alternate'] ?? ($d['images']['all'][0]['alternate'] ?? '');
        $props = [];
        foreach (($d['properties'] ?? []) as $p) {
            if (!empty($p['property']['isOderProperty'])) {
                $props[] = $p;
            }
        }
        return [
            'n'    => $d['texts']['name1'] ?? '',
            'vn'   => $d['variation']['name'] ?? '',
            'nr'   => $d['variation']['number'] ?? '',
            'b'    => $bild,
            'alt'  => $altText,
            'u'    => '/' . ($d['texts']['urlPath'] ?? '') . '_' . $itemId . '_' . $vid . '/',
            'p'    => $d['prices']['default']['price']['formatted'] ?? '',
            'pv'   => $d['prices']['default']['price']['value'] ?? null,
            'uvp'  => $d['prices']['rrp']['price']['formatted'] ?? '',
            'uvpv' => $d['prices']['rrp']['price']['value'] ?? null,
            'av'   => $d['variation']['availability']['names']['name'] ?? '',
            'avId' => (int)($d['variation']['availabilityId'] ?? 0),
            'ok'   => !empty($d['filter']['isSalable']),
            'iq'   => $d['variation']['intervalOrderQuantity'] ?? null,
            'mn'   => $d['variation']['minimumOrderQuantity'] ?? null,
            'mx'   => (($d['variation']['maximumOrderQuantity'] ?? 0) > 0) ? $d['variation']['maximumOrderQuantity'] : null,
            'op'   => $props,
            'hop'  => !empty($d['hasOrderProperties']),
            'hrop' => !empty($d['hasRequiredOrderProperty']),
            'hp'   => (isset($d['prices']['default']['price']['value']) && is_numeric($d['prices']['default']['price']['value'])) || (($d['item']['itemType'] ?? '') === 'set'),
            'gpn'  => count($d['prices']['graduatedPrices'] ?? []),
            'it'   => $d['item']['itemType'] ?? 'default',
        ];
    }
}
