<?php

namespace Vesaka\Core\Observers;

use Vesaka\Core\Models\Model;

/**
 * Description of WebsiteObserver
 *
 * @author Vesaka
 */
class WebsiteObserver extends ModelObserver {
    public function saved(Model $website) {
        app('website')->onSave($website, request());
    }

    public function created(Model $wensite) {
    }
}
