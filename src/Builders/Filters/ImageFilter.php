<?php

namespace Vesaka\Core\Builders\Filters;

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
