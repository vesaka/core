<?php
namespace Vesaka\Core\Observers;

use Vesaka\Core\Abstracts\BaseObserver;
use Vesaka\Core\Models\Category;
/**
 * Description of CategoryObserver
 *
 * @author Vesaka
 */
class CategoryObserver extends BaseObserver {
    
    public function created(Category $category) {
        $this->storeMeta($category);
    }
    
    public function updated(Category $category) {
        $this->storeMeta($category);
    }
    
    public function saved(Category $category) {
        $this->storeMeta($category);
    }
    
}

