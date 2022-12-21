<?php

namespace Vesaka\Core\Models;
use Vesaka\Core\Casts\Json;
/**
 * Description of Path
 *
 * @author vesak
 */
class Path extends CoreModel {
    public $timestamps = false;
    
    protected $table = 'categories_paths';
    
    protected $fillable = ['path'];
    
    protected $casts = [
        'path' => Json::class
    ];
    
    protected function getPathsAttribute() {
        return $this->path;
    }
    
    protected function getBreadcrumbAttribute() {
        return implode('/', array_values($this->path));
    }
    
    protected function getIdsAttribute() {
        return array_keys($this->path);
    }
}
