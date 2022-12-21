<?php

namespace Vesaka\Core\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Str;
/**
 * Description of BaseComponent
 *
 * @author vesak
 */
class BaseComponent extends Component {
    //put your code here
    public $view;
    
    public $data = [];
    
    protected $title = 'core';
    
    public function __construct(string $view = null) {
        $this->view = "$this->title::components." . ($view ?? Str::dotKebab(Str::replaceFirst(__NAMESPACE__ . '\\', '', get_class($this))));
    }
    
    public function render() {
        $this->setup();
        $this->withAttributes($this->data);
        return view($this->view, $this->data);
    }
    
    public function setup() {}
    
    public function __get($name) {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        
        return null;
    }
    
    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

}
