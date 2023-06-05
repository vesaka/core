<?php

namespace Vesaka\Core\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Description of MediaLibraryServiceProvider
 *
 * @author vesak
 */
class MediaLibraryServiceProvider extends ServiceProvider {
    public function boot() {
        if (! defined('FEATURED_IMAGE')) {
            define('FEATURED_IMAGE', 'featured_image');
        }
    }

    public function register() {
    }
}
