<?php

namespace OSVArtikelseite\Containers;

use Plenty\Plugin\ConfigRepository;

class Schalter
{
    public static function an(ConfigRepository $config, string $schluessel): bool
    {
        $wert = $config->get('OSVArtikelseite.' . $schluessel, 'true');
        return !($wert === 'false' || $wert === false || $wert === '0');
    }

    public static function text(ConfigRepository $config, string $schluessel, string $vorgabe): string
    {
        $wert = trim((string)$config->get('OSVArtikelseite.' . $schluessel, ''));
        return $wert !== '' ? $wert : $vorgabe;
    }

    public static function zahl(ConfigRepository $config, string $schluessel, int $vorgabe): int
    {
        $wert = (int)$config->get('OSVArtikelseite.' . $schluessel, (string)$vorgabe);
        return $wert > 0 ? $wert : $vorgabe;
    }
}
