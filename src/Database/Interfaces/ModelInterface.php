<?php

namespace Vesaka\Core\Database\Interfaces;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Vesaka\Core\Abstracts\BaseInterface;
use Vesaka\Core\Http\Requests\Model\StoreModelRequest;
use Vesaka\Core\Models\Model;

/**
 * @author Vesaka
 */
interface ModelInterface extends BaseInterface {
    public function getDocuments();

    public function document(string $type): Model;

    public function saveDocument(StoreModelRequest $request): Model;

    public function store(Request $request): Model;

    public function datatable(Request $request): Paginator;

    public function onSave(Model $model, Request $request);

    public function mostRecent(string $category = '', int $limit = 10): Collection;

    public function collectByType(string $type = '', $limit = 100): Collection;
}
