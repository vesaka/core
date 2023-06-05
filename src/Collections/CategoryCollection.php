<?php

namespace Vesaka\Core\Collections;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Vesaka\Core\Models\Category;

/**
 * Description of CategoryCollection
 *
 * @author Vesaka
 */
class CategoryCollection extends Collection {
    public function __construct($items = []) {
        parent::__construct($items);
    }

    public function treeView() {
        return $this->map([$this, 'populate']);
    }

    public function populate($category) {
        $item = new Category();
        $item->id = $category->id;
        $item->name = $category->name;
        $item->title = Str::of($category->name)->replace('-', ' ')->title()->toString();
        $item->slug = $category->slug;
        $item->pid = $category->parent_id;
        $item->dragDisabled = $category->draggable;
        $item->addTreeNodeDisabled = $category->canHaveNewNode;
        $item->addLeafNodeDisabled = $category->canHaveNewLeaf;
        $item->editNodeDisabled = $category->editable;
        $item->delNodeDisabled = $category->deletable;
        $item->showUrl = route('admin::category.show', ['category' => $category->id]);
        $item->storeUrl = route('admin::category.store', ['category' => $category->id]);
        $item->deleteUrl = route('admin::category.destroy', ['category' => $category->id]);
        $item->isLeaf = ! $category->hasChildren;
        $item->hasChildren = $category->hasChildren;
        $item->breadcrumb = $category->path->breadcrumb;
        $item->path = $category->path->ids;
        if ($category->hasChildren) {
            $item->setRelation('children', $category->children->map([$this, 'populate']));
        }

        if (is_iterable($metables = $category->getMetables())) {
            foreach ($metables as $metable) {
                if (! $category->meta->has($metable)) {
                    $category->meta->put($metable, '');
                } else {
                }
            }
            //dump();
            $item->setRelation('meta', $category->meta->filter()->keyBy('key')
                ->map(function ($item) {
                    return $item->value;
                }));
        }

        return $item;
    }
}
