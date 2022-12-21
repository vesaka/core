<?php

namespace Vesaka\Core\Abstracts;
use Illuminate\Database\Eloquent\Model;
/**
 *
 * @author User
 */
interface BaseInterface {
    
    public function __call($method, $args);
    
    public function observe($class);
    
    public function getModel(): Model;
    
    public function filter($request, $paginate = false);
    
    public function seconds($seconds = 60);
    
    public function minutes($minutes = 1);
    
    public function hours($hours = 1);
    
    public function weeks($weeks = 1);
}
