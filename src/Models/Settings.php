<?php

namespace Vesaka\Core\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Description of Meta
 *
 * @author Vesaka
 */
class Settings extends CoreModel {
    
    protected $table = 'settings';
    
    protected $fillable = ['type', 'model_id', 'name', 'value'];
    
    public $timestamps = true;
}
