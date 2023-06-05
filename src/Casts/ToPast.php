<?php

namespace Vesaka\Core\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Str;

/**
 * Description of ToPast
 *
 * @author vesak
 */
class ToPast implements CastsAttributes {
    private static $irregulars = [
        'send' => 'sent',
    ];

    public function get($model, string $key, $value, array $attributes) {
        if (empty($value) && ($verb = get_class($model).'::DEFAULT_'.Str::upper($key))) {
            return constant($verb);
        }

        return $value;
    }

    public function set($model, string $key, $value, array $attributes) {
        if (! is_string($value)) {
            return '';
        }

        if (defined($verb = get_class($model).'::'.Str::upper($value))) {
            return constant($verb);
        }

        if (isset(self::$irregulars[$value])) {
            return self::$irregulars[$value];
        }

        if (Str::endsWith($value, 'ed')) {
            return $value;
        }

        if (Str::endsWith($value, 'e')) {
            return $value.'d';
        }

        return $value.'ed';
    }
}
