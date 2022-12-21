<?php

namespace Vesaka\Core\Providers;

/**
 * Description of MediaLibraryServiceProvider
 *
 * @author vesak
 */
class MediaLibraryServiceProvider extends BaseServiceProvider {
   
    public function boot() {
        $conversions = config('frontend.gallery.conversions');
        if (!is_countable($conversions)) {
            return;
        }
    }
    
    public function register($param) {
        
    }
}
