<?php

use Vesaka\Core\Database\Repositories;
use Vesaka\Core\Database\Interfaces;
use Vesaka\Core\Database\Cache;
use Vesaka\Core\Models;
use Vesaka\Core\Observers;
use Vesaka\Core\Policies;
return [
    'category' => [
        'model' => Models\Category::class,
        'repository' => Repositories\CategoryRepository::class,
        'cache' => Cache\CategoryCache::class,
        'interface' => Interfaces\CategoryInterface::class,
        'observer' => Observers\CategoryObserver::class,
        'policy' => Policies\CategoryPolicy::class,
    ],
    'model' => [
        'model' => Models\Model::class,
        'repository' => Repositories\ModelRepository::class,
        'cache' => Cache\ModelCache::class,
        'interface' => Interfaces\ModelInterface::class,
        'observer' => Observers\ModelObserver::class,
        'policy' => Policies\ModelPolicy::class,
    ],
];
