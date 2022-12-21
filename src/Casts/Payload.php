<?php

namespace Vesaka\Core\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use JsonException;
use Illuminate\Database\Eloquent\Model;
use Vesaka\Core\Models\CoreModel;
/**
 * Description of Payload
 *
 * @author vesak
 */
class Payload implements CastsAttributes {
    //put your code here
    public function get($model, string $key, $value, array $attributes) {
        
        $classname = $this->getClassName($value);
        
        try {
            $payload = json_decode($value, true);
        } catch (JsonException $ex) {
            $payload = [];
        }
        
        return new $classname($payload);
    }

    public function set($model, string $key, $value, array $attributes) {   

        $classname = $this->getClassName($value);
        $model->model = $classname;

        if (is_subclass_of($value, $classname)) {
            return json_encode($value->toArray());
        }
        return json_encode($value);
    }
    
    private function getClassName($model) {
        
        if (is_subclass_of($model, Model::class)) {
            return $model;
        }
        
        return CoreModel::class;
    }

}
