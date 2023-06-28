<?php

namespace Vesaka\Core\Traits\Providers;

use Route;

/**
 * @author vesak
 */
trait RoutesProviderTrait {
    protected function registerRoutes() {
        foreach ($this->routes as $name => $route) {
            $filename = $this->__dir__.'routes/'.$name.'.php';
            if (file_exists($filename)) {
                Route::middleware(isset($route['middleware']) ? $route['middleware'] : [])
                    ->prefix(isset($route['prefix']) ? $route['prefix'] : $this->title)
                    ->name($this->title.'::')
                    ->namespace($this->namespace.'Http\\Controllers')
                    ->group($filename);
            }
        }
    }

    protected function registerAdminRoutes() {
        if (isset($this->adminRoutes) && is_iterable($this->adminRoutes)) {
            foreach ($this->adminRoutes as $name => $route) {
                $filename = $this->__dir__.'routes/'.$name.'.php';
                if (file_exists($filename)) {
                    Route::middleware(['web', 'auth'])
                        ->prefix('admin')
                        ->name('admin::')
                        ->namespace($this->namespace.'Http\\Controllers')
                        ->group($filename);
                }
            }
        }
    }

    protected function registerNormalRoutes(...$files) {

        foreach ($files as $file) {
            if (!is_string($file)) {
                continue;
            }

            $filename = $this->__dir__.'routes/'.$file.'.php';
            if (file_exists($filename)) {
                Route::middleware(['web'])
                    ->name($this->title.'::')
                    ->namespace($this->namespace.'Http\\Controllers')
                    ->group($filename);
            }
        }
        
    }
}
