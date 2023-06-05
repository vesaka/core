<?php

namespace Vesaka\Core\Database\Interfaces;

use Illuminate\Support\Collection;

/**
 * @author Vesaka
 */
interface ImageInterface extends ModelInterface {
    public function mostRecent(string $category = '', int $limit = 10): Collection;
}
