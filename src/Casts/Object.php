<?php

namespace Vesaka\Core\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
/**
 * Description of Object
 *
 * @author vesak
 */
class Object implements CastsAttributes {
    //put your code here
    public function get($model, string $key, $value, array $attributes) {
        
    }

    public function set($model, string $key, $value, array $attributes): array {
        
    }

}
