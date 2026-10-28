<?php

namespace OSVArtikelseite\Providers;

use Plenty\Plugin\ServiceProvider;

class OSVArtikelseiteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->getApplication()->register(OSVArtikelseiteRouteServiceProvider::class);
    }
}
