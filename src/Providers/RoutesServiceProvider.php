<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\Facades\Route;

/**
 * Description of RoutesServiceProvider
 *
 * @author vesak
 */
class RoutesServiceProvider extends BaseServiceProvider {
        
    protected $routes = [
        'web' => [
            'middleware' => ['web'],
        ],
        'api' => [
            'middleware' => ['api']
        ],
        'channels' => [],
        'console' => []
    ];
    
    public function register(): void {
        foreach ($this->routes as $name => $route) {
            $filename = $this->__dir__ . 'routes/' . $name . '.php';
            if (file_exists($filename)) {
                Route::middleware(isset($route['middleware']) ? $route['middleware'] : [])
                        ->prefix($this->alias)
                        ->name($this->alias . '::')
                        ->namespace($this->namespace . 'Http\\Controllers')
                        ->group($filename);
            }
        }
    }
    
    public function boot() {
        
    }
}
