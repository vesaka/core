<?php
namespace Vesaka\Core\Database\Interfaces;

use Vesaka\Core\Abstracts\BaseInterface;
use Vesaka\Core\Models\Model;
use Vesaka\Core\Http\Requests\Model\SaveDocumentRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 *
 * @author Vesaka
 */
interface ModelInterface extends BaseInterface {
    
    public function getDocuments();
    
    public function document(string $type): Model;
    
    public function saveDocument(SaveDocumentRequest $request): Model;
    
    public function store(Request $request): Model;
    
    public function datatable(Request $request): Paginator;
    
    public function onSave(Model $model, Request $request);
    
    public function mostRecent(string $category  = '', int $limit = 10): Collection;
    
    public function collectByType(string $type  = '', $limit = 100): Collection;
    
}

