<?php

namespace Vesaka\Core\Database\Repositories;
use Vesaka\Core\Abstracts\BaseRepository;
use Vesaka\Core\Database\Interfaces\ModelInterface;
use Vesaka\Core\Models\Model;
use Vesaka\Core\Http\Requests\Model\SaveDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Description of ModelRepository
 *
 * @author Vesaka
 */
class ModelRepository extends BaseRepository implements ModelInterface {
    //put your code here
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
    
    public function datatable(Request $request): Paginator {
        return $this->model->select('id', 'title', 'content', 'created_at', 'updated_at')
                ->orderBy($request->sortBy ?? 'id', $request->sortType ?? 'asc')
                ->paginate($request->rowsPerPage ?? 10);
    }

}

