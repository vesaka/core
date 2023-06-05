<?php

use Vesaka\Core\Database\Cache;
use Vesaka\Core\Database\Interfaces;
use Vesaka\Core\Database\Repositories;
use Vesaka\Core\Models;
use Vesaka\Core\Observers;
use Vesaka\Core\Policies;
use Vesaka\Core\Resources;

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
    'img' => [
        'model' => Models\Image::class,
        'repository' => Repositories\ImageRepository::class,
        'cache' => Cache\ImageCache::class,
        'interface' => Interfaces\ImageInterface::class,
        'observer' => Observers\ImageObserver::class,
        'policy' => Policies\ImagePolicy::class,
        'resource' => Resources\ImageResource::class,
    ],
    'website' => [
        'model' => Models\Website::class,
        'repository' => Repositories\WebsiteRepository::class,
        'cache' => Cache\WebsiteCache::class,
        'interface' => Interfaces\WebsiteInterface::class,
        'observer' => Observers\WebsiteObserver::class,
        'policy' => Policies\WebsitePolicy::class,
    ],
];
