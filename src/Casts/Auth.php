<?php

namespace Vesaka\Core\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Model\User;
use Illuminate\Contracts\Encryption\DecryptException;
/**
 * Description of Auth
 *
 * @author vesak
 */
class Auth implements CastsAttributes {
    //put your code here
    public function get($model, string $key, $value, array $attributes) {
        $provider = $this->provider();
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return forward_static_call_array([$provider, 'firstOrNew'], [[
                'id' => null,
                'email' => $value]
            ]);
        }
        return forward_static_call_array([$provider, 'find'], [$value]);
    }

    public function set($model, string $key, $value, array $attributes) {
        $provider = $this->provider();
        try {
            $__value = decrypt($value);
        } catch (DecryptException $ex) {
            $__value = $value;
        }
        if ($__value instanceof $provider) {
            return $__value->id ?? $value->email;
        }
        
        return $__value;
    }
    
    private function provider() {
        $guard = config('auth.defaults.guard');
        $provider = config("auth.guards.$guard.provider");
        
        return config("auth.providers.$provider.model");
    }

}
