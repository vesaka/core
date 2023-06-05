<?php

namespace Vesaka\Core\Abstracts;

use Vesaka\Core\Contracts\EventContract;
use Vesaka\Core\Contracts\ListenerContract;

/**
 * Description of BaseListener
 *
 * @author vesak
 */
class BaseListener implements ListenerContract {
    //put your code here
    protected $priority = 0;

    public static function priority(): int {
        return $this->priority;
    }

    public function handle(EventContract $event) {
    }
}
