<?php

namespace Fubbleskag\Seat\Altcheck;

use Seat\Services\AbstractSeatPlugin;

class AltcheckServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->add_routes();
        $this->add_views();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/altcheck.config.php', 'altcheck.config');
        $this->mergeConfigFrom(__DIR__ . '/Config/altcheck.locale.php', 'altcheck.locale');
        $this->mergeConfigFrom(__DIR__ . '/Config/altcheck.sidebar.php', 'package.sidebar');
        $this->registerPermissions(__DIR__ . '/Config/altcheck.permissions.php', 'altcheck');
    }

    private function add_routes()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    private function add_views()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'altcheck');
    }

    public function getName(): string
    {
        return 'Alt Check';
    }

    public function getPackageRepositoryUrl(): string
    {
        return 'https://example.com';
    }

    public function getPackagistPackageName(): string
    {
        return 'seat-altcheck';
    }

    public function getPackagistVendorName(): string
    {
        return 'fubbleskag';
    }

    public function getVersion(): string
    {
        return config('altcheck.config.version');
    }
}
