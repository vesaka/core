<?php

namespace Vesaka\Core\Providers;

/**
 * Description of ViewsServiceProvider
 *
 * @author vesak
 */
class ViewsServiceProvider extends BaseServiceProvider {
    
    
    
    public function register(): void {
        
        $this->loadViewsFrom($this->__dir__ . '/resources/views/', $this->alias);
    }
    
    public function boot() {
        
    }
}
