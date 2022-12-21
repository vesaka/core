<?php
namespace Vesaka\Core\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Description of Json
 *
 * @author vesak
 */
class Json implements CastsAttributes {
    //put your code here
    public function get($model, string $key, $value, array $attributes) {
        return json_decode($value, true);
    }

    public function set($model, string $key, $value, array $attributes) {
        return json_encode($value);
    }

}
