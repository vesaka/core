<?php

namespace Vesaka\Core\Builders\Filters;

use Vesaka\Core\Builders\Filters\BaseFilter;
/**
 * Description of ImageFilter
 *
 * @author vesak
 */
class ImageFilter extends BaseFilter {
    
    public function setup() {
        $this->builder->with('media');
    }
}
