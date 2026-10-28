<?php

namespace OSVArtikelseite\Containers;

use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Templates\Twig;

class ArtikelseiteScript
{
    public function call(Twig $twig, ConfigRepository $config): string
    {
        $cs = Schalter::an($config, 'csAktiv');
        $namen = Schalter::an($config, 'namenAktiv');
        $brot = Schalter::an($config, 'brotkrumen');
        $aufbau = Schalter::an($config, 'aufbauAktiv');
        if (!$cs && !$namen && !$brot && !$aufbau) {
            return '';
        }
        return $twig->render('OSVArtikelseite::Containers.ArtikelseiteScript', [
            'einstellungJson' => json_encode([
                'cs'          => $cs,
                'namen'       => $namen,
                'brot'        => $brot,
                'aufbau'      => $aufbau,
                'ceresAus'    => Schalter::an($config, 'ceresListenAus'),
                'titelZ'      => Schalter::text($config, 'titelZubehoer', 'Passendes Zubehör'),
                'titelE'      => Schalter::text($config, 'titelEmpf', 'Unsere Empfehlungen'),
                'marke'       => Schalter::text($config, 'textMarke', 'Oft zusammen gekauft'),
                'maxZ'        => Schalter::zahl($config, 'maxZubehoer', 4),
                'maxE'        => Schalter::zahl($config, 'maxEmpf', 4),
            ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT),
        ]);
    }
}
