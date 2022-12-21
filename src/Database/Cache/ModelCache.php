<?php

namespace Vesaka\Core\Database\Cache;
use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Core\Database\Interfaces\ModelInterface;
use Vesaka\Core\Http\Requests\Model\SaveDocumentRequest;
use Vesaka\Core\Models\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;

/**
 * Description of ModelCache
 *
 * @author Vesaka
 */
class ModelCache extends BaseCache implements ModelInterface  {
    
    public function getDocuments() {
        return $this->raw();
        return $this->fetch('privacy-policy', 'terms-and-conditions');
    }

    public function document(string $type): Model {
        return $this->raw();
        return $this->fetch($type);
    }

    public function saveDocument(SaveDocumentRequest $request): Model {
        return $this->forget($request->type)->refresh();
    }
    
    public function store(Request $request): Model {
        return $this->raw();
    }

    public function datatable(Request $request): Paginator {
        return $this->raw();
    }

}
