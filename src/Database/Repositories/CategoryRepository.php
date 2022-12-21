<?php
namespace Vesaka\Core\Database\Repositories;

use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Core\Database\Interfaces\CategoryInterface;
use Vesaka\Core\Http\Requests\Category\StoreCategoryRequest;
use Illuminate\Support\Str;

/**
 * Description of CategoryRepository
 *
 * @author vesak
 */
class CategoryRepository extends BaseRepository implements CategoryInterface {
    
    public function nested($parent = null) {
        $items = $this->model
                ->from('categories as c')
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

        return $items->get()->treeView();
    }
    
    public function store(StoreCategoryRequest $request) {
        if ($request->category) {
            $category = $this->model->find($request->category);
            $category->parent_id = $request->parent_id;
            $category->name = $request->name;
            $category->priority = $request->priority;
            $category->slug = Str::slug($request->name);
            $category->save();
        } else {
            $this->model->create([
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => Str::slug($request->name)
            ]);
        }
        
    }
}
