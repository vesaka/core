<?php

namespace Vesaka\Core\Database\Cache;
use Vesaka\Core\Database\Interfaces\ImageInterface;
use Illuminate\Support\Collection;

/**
 * Description of ImageCache
 *
 * @author Vesaka
 */
class ImageCache extends ModelCache implements ImageInterface  {
    
    public function mostRecent(string $category  = '', int $limit = 10): Collection {
        //return $this->raw();
        return $this->tags('image', 'recent', $category)->fetch();
    }

}
