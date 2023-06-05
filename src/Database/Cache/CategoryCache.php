<?php

namespace Vesaka\Core\Database\Cache;

use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Core\Database\Interfaces\CategoryInterface;
use Vesaka\Core\Http\Requests\Category\StoreCategoryRequest;

/**
 * Description of CategoryCache
 *
 * @author Vesaka
 */
class CategoryCache extends BaseCache implements CategoryInterface {
    public function nested() {
        return $this->fetch('categories', 'nested');
    }

    public function store(StoreCategoryRequest $request) {
        return $this->raw();
    }
}
