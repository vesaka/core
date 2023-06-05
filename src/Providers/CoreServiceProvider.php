<?php

namespace Vesaka\Core\Providers;

use Vesaka\Core\Traits\Providers\ResourceProviderTrait;

/**
 * Description of CoreServiceProvider
 *
 * @author vesak
 */
class CoreServiceProvider extends BaseServiceProvider {
    use ResourceProviderTrait;

    protected array $providers = [
        ConfigServiceProvider::class,
        MacroStrServiceProvider::class,
        ViewsServiceProvider::class,
        AdminRoutesServiceProvider::class,
        ComponentsServiceProvider::class,
        CliServiceProvider::class,
        MediaLibraryServiceProvider::class,
    ];

    public function register(): void {
        //$this->registerResources();
        $this->registerProviders();
    }

    public function boot() {
    }
}
