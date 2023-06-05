<?php

namespace Vesaka\Core\Traits\Providers;

/**
 * @author vesak
 */
trait MiddlewareProviderTrait {
    public function addPackageMiddlewares(array $middlewares = []) {
        if (is_array($this->middlewares)) {
            $middlewares = array_merge($this->middlewares, $middlewares);
        }

        foreach ($middlewares as $name => $middleware) {
            app('router')->aliasMiddleware($name, $middleware);
        }
    }
}
