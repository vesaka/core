<?php

namespace Vesaka\Core\Database\Cache;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vesaka\Core\Abstracts\BaseCache;
use Vesaka\Core\Database\Interfaces\ModelInterface;
use Vesaka\Core\Http\Requests\Model\StoreModelRequest;
use Vesaka\Core\Models\Model;

/**
 * Description of ModelCache
 *
 * @author Vesaka
 */
class ModelCache extends BaseCache implements ModelInterface {
    public function getDocuments() {
        return $this->raw();

        return $this->fetch('privacy-policy', 'terms-and-conditions');
    }

    public function document(string $type): Model {
        return $this->raw();

        return $this->fetch($type);
    }

    public function saveDocument(StoreModelRequest $request): Model {
        return $this->forget($request->type)->refresh();
    }

    public function store(Request $request): Model {
        return $this->raw();
    }

    public function datatable(Request $request): Paginator {
        return $this->raw();
    }

    public function onSave(Model $model, Request $request) {
        return $this->tags("$model->type.$model->id", $model->type)
            ->forget()
            ->refresh();
    }

    public function mostRecent(string $category = '', int $limit = 10): Collection {
        $model = $this->repository->getModel();
        return $this->tags($model->getType(), 'recent', $category)->fetch();
    }

    public function collectByType(string $type = '', $limit = 100): Collection {
        $model = $this->repository->getModel();
        return $this->tags($type ?? $model->getType(), 'list', $limit)->fetch();
    }
}
