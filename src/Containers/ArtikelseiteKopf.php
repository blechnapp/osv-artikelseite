<?php

namespace OSVArtikelseite\Containers;

use Plenty\Plugin\ConfigRepository;
use Plenty\Plugin\Templates\Twig;

class ArtikelseiteKopf
{
    public function call(Twig $twig, ConfigRepository $config): string
    {
        return $twig->render('OSVArtikelseite::Containers.ArtikelseiteKopf', [
            'cs'     => Schalter::an($config, 'csAktiv'),
            'namen'  => Schalter::an($config, 'namenAktiv'),
            'thumbs' => Schalter::an($config, 'thumbsLinks'),
        ]);
    }
}
