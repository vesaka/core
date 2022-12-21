<?php

namespace Vesaka\Core\Abstracts;

use Vesaka\Core\Contracts\ListenerContract;
use Vesaka\Core\Contracts\EventContract;
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
