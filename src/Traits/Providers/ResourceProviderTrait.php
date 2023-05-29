<?php

namespace Vesaka\Core\Traits\Providers;

/**
 *
 * @author vesak
 */
trait ResourceProviderTrait {
   
    protected function registerResources() {
        $resources = config('resources');
        if (file_exists($filename)) {
            $repos = require $filename;
            
        }

    }
}
