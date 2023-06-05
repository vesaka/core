<?php

namespace Vesaka\Core\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @author Vesaka
 */
trait HasCollectionTrait {
    public function newCollection(array $models = []) {
        $collection = config('repos.'.($this->collection ?? Str::kebab($this->table)).'.collection');
        if ($collection && class_exists($collection) && is_subclass_of($collection, Collection::class)) {
            return new $collection($models);
        }

        return parent::newCollection($models);
    }
}
