<?php

namespace Vesaka\Core\Providers;

/**
 * Description of CliServiceProvider
 *
 * @author vesak
 */
class CliServiceProvider extends BaseServiceProvider {
    
    protected array $packageCommands = [];


    public function register(): void {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $this->includePackageClasses('src/Console/Commands', function($classname) {
            $this->packageCommands[] = $classname;
        });
        
        $this->commands($this->packageCommands);
    }
    
    public function boot() {
        
    }
}
