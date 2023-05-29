<?php

namespace Vesaka\Core\Database\Repositories;

use Vesaka\Core\Database\Interfaces\ImageInterface;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;


/**
 * Description of ImageRepository
 *
 * @author Vesaka
 */
class ImageRepository extends ModelRepository implements ImageInterface {

    public function datatable(Request $request): Paginator {
        return $this->model->select('id', 'title', 'content', 'updated_at as modified')
                        ->where('type', 'image')
                        ->orderBy($request->order ?? 'id', $request->sort ?? 'asc')
                        ->paginate($request->limit ?? 10)->through(function ($item) {
                            $item->thumbnail = $item->preview;
                            return $item;
                        });
    }

    public function mostRecent(string $category  = '', int $limit = 10): Collection {

        $images =  $this->model
                ->select('id', 'name', 'title', 'content', 'created_at')
                ->where('type', 'image')
                ->with(['categories' => function($query) {
                    $query->select('categories.id', 'categories.name');
                }])
                ->with('media')
//                ->with(['media' => function($query) {
//                    $query
//                            ->select('id', 'model_id', 'collection_name', 'file_name', 'disk', 'generated_conversions')
//                            ->where('collection_name', FEATURED_IMAGE);
//                }])
                ->whereHas('categories', function($query) use ($category) {
                    $query->where('categories.name', $category);
                })
                 ->whereHas('media', function($query) {
                        $query->where('media.collection_name', FEATURED_IMAGE)->limit(1);
                })
                ->orderBy('created_at', 'desc')
                        ->get();
    
        $images->transform(function($image) {
            
            $image->sm = $image->getFirstMediaUrl(FEATURED_IMAGE, 'small');
            $image->art = $image->getFirstMediaUrl(FEATURED_IMAGE, 'big');
            return $image;
        });
        //dd($images->toArray());
        
        return $images;
    }

}
