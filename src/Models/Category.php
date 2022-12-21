<?php

namespace Vesaka\Core\Models;

use Vesaka\Core\Collections\CategoryCollection;
/**
 * Description of Category
 *
 * @author vesak
 */
class Category extends CoreModel {
    
    protected $table = 'categories';
    
    protected $fillable = ['name', 'slug', 'priority', 'parent_id'];
    
    protected $metables = ['icon', 'author', 'order'];
    
    public function children() {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('priority');
    }
    
    public function path() {
        return $this->hasOne(Path::class, 'id', 'id');
    }
    
    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    public function allChildren() {
        return $this->children()->with('allChildren')->orderBy('priority');
    }

    public function newCollection(array $models = array()) {
        return new CategoryCollection($models);
    }
    
    public function getIsRootAttribute() {
        return null === $this->parent_id;
    }
    
    
    public function getHasChildrenAttribute() {
        return $this->children->isNotEmpty();
    }
    
    public function getPidAttribute() {
        return $this->parent_id;
    }
    
    public function getIconAttribute() {
        return $this->field('icon');
    }
    
    public function getDraggableAttribute() {
        return $this->field('draggable');
    }
    
    public function getCanHaveNewNodeAttribute() {
        return $this->field('can-have-new-Node');
    }
    
    public function getCanHaveNewLeafAttribute() {
        return $this->field('can-have-new-leaf');
    }
    
    public function getEnabledAttribute() {
        return $this->field('enabled');
    }
    
    public function getEditableAttribute() {
        return $this->field('editable');
    }
    
    public function getDeletableAttribute() {
        return $this->field('deletable');
    }
    
    public static function nested($parent = null, $query = null) {
        $items = self::from('categories as c')
                ->with('children', 'meta', 'path')
                ->orderBy('c.priority')
                ;
        if (ctype_digit($parent) || is_int($parent)) {
            $items->where('c.parent_id', $parent);
        } else if(is_string($parent)) {
            $items->select('c.*')
                    ->join('categories as p', 'p.id', '=', 'c.parent_id')
                    ->where('p.slug', $parent);
        } else {
            $items->whereNull('c.parent_id');
        }
        
        if (is_callable($query)) {
            $query($items);
        }


        return $items->get()->treeView()->toArray();
    }
    
}
