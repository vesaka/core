<?php

namespace Vesaka\Core\Traits\Providers;

trait EventsProviderTrait {

    protected function registerEvents(): void {
        if (isset($this->events) && is_array($this->events)) {
            foreach ($this->events as $event => $listeners) {
                foreach ($listeners as $listener) {
                    $this->app['events']->listen($event, $listener);
                }
            }
        }
        
    }

}