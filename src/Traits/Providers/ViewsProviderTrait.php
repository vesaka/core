<?php

namespace Vesaka\Core\Traits\Providers;

/**
 * @author vesak
 */
trait ViewsProviderTrait {
    public function registerViews() {
        $this->loadViewsFrom($this->__dir__.'/resources/views/', $this->alias);
    }
}
