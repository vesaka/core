<?php

namespace Vesaka\Core\Builders\Filters;
/**
 * Description of BaseFilter
 *
 * @author User
 */
class BaseFilter {
    
    protected $request;
    
    protected $model;
    
    protected $builder;
    
    protected $slug;
    
    protected $columns;




    public function __construct($request, $model = null) {
        $this->request = $request;
        
        $this->model = $model;
        $this->builder = forward_static_call([$this->model, 'query']);
        $this->setUp();
    }

    public function setUp() {}
    
    /**
     * 
     * @return QueryBuilder
     */
    public function build() {
        $inputs = array_filter(array_map(function($input) {
            if (is_array($input)) {
                return array_filter($input);
            }
            return $input;
        }, $this->request->all()));

        foreach($inputs as $name => $input) {
            $method = str_replace('.', '_', $name);
            
            if (method_exists($this, $method)) {
                $this->{$method}($input);
            }
        }
        
        if ($this->columns) {
            $this->builder->select($this->columns);
        }
        
        if (!$this->builder) {
            $this->builder = $this->model->select('*');
        }
        //dd($inputs);
        return $this->builder;
    }
        
    protected function addColumn(string $name) {
        $this->columns[] = $name;
        return $this->builder;
    }
    
    public function __call($name, $argments) {
        

    }
    


}
