<?php
namespace Vesaka\Core\Providers;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
/**
 * Description of CoreServiceProvider
 *
 * @author vesak
 */
class CoreServiceProvider extends BaseServiceProvider {
    
    protected array $providers = [
        MacroStrServiceProvider::class,
        ViewsServiceProvider::class,
        RoutesServiceProvider::class,
        ComponentsServiceProvider::class,
        ConfigServiceProvider::class,
        CliServiceProvider::class,
    ];


    public function register(): void {
        $this->registerProviders();
    }
    
    public function boot() {

    }
    
}
