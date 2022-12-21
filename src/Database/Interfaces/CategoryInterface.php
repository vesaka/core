<?php

namespace Vesaka\Core\Database\Interfaces;

use Vesaka\Core\Abstracts\BaseInterface;
use Vesaka\Core\Http\Requests\Category\StoreCategoryRequest;
/**
 *
 * @author vesak
 */
interface CategoryInterface extends BaseInterface {
    public function nested();
    
    public function store(StoreCategoryRequest $request);
}
