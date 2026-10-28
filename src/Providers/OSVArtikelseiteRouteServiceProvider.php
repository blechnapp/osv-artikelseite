<?php

namespace OSVArtikelseite\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\ApiRouter;
use Plenty\Plugin\Routing\Router;

class OSVArtikelseiteRouteServiceProvider extends RouteServiceProvider
{
    public function map(Router $router, ApiRouter $apiRouter)
    {
        $apiRouter->version(['v1'], ['namespace' => 'OSVArtikelseite\Controllers'], function ($apiRouter) {
            // Zubehör (302) und Empfehlungen (303) aller Varianten eines Artikels samt Kacheldaten (?ids=Varianten des Artikels)
            $apiRouter->get('osv-artikelseite/listen', 'ListenController@listen');
        });
    }
}
