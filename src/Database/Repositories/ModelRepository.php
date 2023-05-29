<?php

namespace Vesaka\Core\Database\Repositories;
use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Core\Database\Interfaces\ModelInterface;
use Vesaka\Core\Models\Model;
use Vesaka\Core\Http\Requests\Model\SaveDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;


/**
 * Description of ModelRepository
 *
 * @author Vesaka
 */
class ModelRepository extends BaseRepository implements ModelInterface {
    //put your code here
    protected $countable_meta = ['tags'];
    
    public function getDocuments() {
        return $this->model
                ->whereIn('type', ['privacy-policy', 'terms-and-conditions'])
                ->withoutGlobalScope('type')
                ->get()
                ->keyBy('type');
    }
    
    public function document(string $type): Model {        
        return $this->model->firstOrNew(['type' => $type]);
    }

    public function saveDocument(SaveDocumentRequest $request): Model {
        $document = $this->model->firstOrNew(['type' => $request->type]);
        $document->title = $request->title;
        $document->content = $request->content;
        $document->save();
        
        return $document;
    }
    
    public function store(Request $request): Model {
        DB::beginTransaction();
        $post = $this->model->findOrNew($request->id);
        $post->author_id = $request->author_id || auth()->id();
        $post->title = $request->title;
        $post->type = $request->type;
        $post->name = Str::slug($request->title);
        $post->content = $request->content;
        $post->save();
        DB::commit();
        
        return $post;
    }
    
    public function onSave(Model $model, Request $request) {
        $model->syncCategories($request->category ?? [])
                ->storeMeta($request)
                ->setFeaturedImage($request);

        return $this;
    }
    
    public function datatable(Request $request): Paginator {       
        return $this->model->select('id', 'title', 'content', 'updated_at as modified')
                        ->where('type', $request->type ?? $this->model->getType())
                        ->orderBy($request->order ?? 'id', $request->sort ?? 'asc')
                        ->paginate($request->limit ?? 10)->through(function ($item) {
                            $item->thumbnail = $item->preview;
                            return $item;
                        });
    }
    
    public function mostRecent(string $category  = '', int $limit = 10): Collection {
        $list =  $this->model
                ->select('id', 'name', 'title', 'content', 'created_at')
                ->where('type', $this->model->getType())
                ->with('media')
//                ->whereHas('categories', function($query) use ($category) {
//                    if ($category) {
//                        $query->where('categories.name', $category);
//                    }
//                })
                ->whereHas('media', function($query) {
                        $query->where('media.collection_name', FEATURED_IMAGE)->limit(1);
                })
                ->orderBy('created_at', 'desc')
                ->get();
    
        $list->transform(function($image) {
            
            $image->sm = $image->getFirstMediaUrl(FEATURED_IMAGE, 'small');
            $image->art = $image->getFirstMediaUrl(FEATURED_IMAGE, 'big');
            return $image;
        });
        //dd($images->toArray());
        
        return $list;
    }
    
    public function collectByType(string $type  = '', $limit = 100): Collection {
        $list =  $this->model
                ->select('id', 'name', 'title', 'content', 'created_at')
                ->where('type', $type ?? $this->model->getType())
                ->with('media')
                ->whereHas('media', function($query) {
                        $query->where('media.collection_name', FEATURED_IMAGE)->limit(1);
                })
                ->orderBy('created_at', 'asc')
                ->limit(100)
                ->get();
    
        $list->transform(function($item) {
            $item->art = $item->getFirstMediaUrl(FEATURED_IMAGE);
            return $item;
        });
        //dd($images->toArray());
        
        return $list;
    }

}

