<?php

namespace Vesaka\Core\Traits\Providers;

/**
 * Description of CliProviderTrait
 *
 * @author vesak
 */
trait CliProviderTrait {

    protected $packageCommands = [];

    protected function registerPackageCommands() {
        if (!$this->app->runningInConsole()) {
            return;
        }

        if (method_exists($this, 'includePackageClasses')) {
            $this->includePackageClasses('src/Console/Commands', function ($classname) {
                $this->packageCommands[] = $classname;
            });
        }

        $this->commands($this->packageCommands);
    }

}
