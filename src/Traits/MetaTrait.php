<?php

namespace Vesaka\Core\Traits;

/**
 *
 * @author Vesaka
 */
trait MetaTrait {
    
    protected $__meta = null;
    
    protected $metables = [];
    
    public function meta() {
        return $this->hasMany('Vesaka\Core\Models\Meta', 'model_id', 'id')->where('entity', get_class($this));
    }
    
    public function field(string $name, $onlyValue = true) {
        
        if (!isset($this->__fields)) {
            $this->__fields = $this->meta->keyBy('key');;
        }
        
        $record = $this->__fields->get($name);

        if ($onlyValue && $record && $record->value) {
            return $record->value;
        }
        
        return $record;
    }
    
    public function getFieldAttribute() {
        if (!$this->__fields) {
            $this->__fields = $this->meta->keyBy('key');
        }
        
        return $this->__fields;
    }
    
    public function option($key, $default = null) {
        $options = app('user-meta')->options($this->id);
        
        if(isset($options[$key])) {
            return $options[$key];
        }
        
        return config($key, $default);
    }
    
    public function put($key, $value) {
        $this->meta()->updateOrCreate([
            'user_id' => $this->id,
            'key' => $key,
            'value' => $value
        ]);
    }
    
    public function purge($key) {
        
    }
    
     public function getMetables() {
        return $this->metables;
    }

}
