<?php

namespace Vesaka\Core\Abstracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionObject;

/**
 * Description of EloquentAbstractRepository
 *
 * @author User
 */
class BaseRepository implements BaseInterface {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->model, $method], $args);
    }

    public function observe($class) {
        $this->model->observe($class);
    }

    public function getModel(): Model {
        return $this->model;
    }

    public function find($id) {
        return $this->model->find($id);
    }

    public function findWith($id, $with = [], $callback = null) {
        $model = $this->model->with($with)->find($id);

        if ($model->meta->isNotEmpty()) {
            $model->setRelation('meta', $model->meta->keyBy('key')
                ->map(function ($item) {
                    return $item->value;
                }));
        } else {
            $model->setRelation('meta', collect());
        }

        if (method_exists($model, 'getMetables') && is_iterable($metables = $this->getMetables())) {
            foreach ($metables as $metable) {
                if (! $model->meta->has($metable)) {
                    $model->meta->put($metable, '');
                }
            }
        }

        if (is_callable($callback)) {
            $callback($model);
        }

        return $model;
    }

    private function resolveWith($with = []) {
        if (is_array($with) && ! empty($with)) {
            foreach ($with as $key => $item) {
                if (! Str::startsWith($key, '_meta:')) {
                    continue;
                }

                $list = explode(',', str_teplace($key, '_meta:', ''));

                $with['meta'] = function ($query) use ($list) {
                    $query->whereIn('key', $list);
                };

                unset($with[$key]);
                break;
            }
        }

        return $with;
    }

    public function create($data) {
        return $this->model->create($data);
    }

    public function update($model, $data) {
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function remove($key) {
        $this->model->where('id', $key)->delete();
    }

    public function fetch(array $data = [], ...$columns) {
        if (empty($columns)) {
            $builder = $this->model->select('*');
        } else {
            $builder = call_user_func_array([$this->model, 'select'], $columns);
        }

        foreach ($data as $i => $row) {
            $where = $i === 0 ? 'whereRaw' : 'orWhereRaw';
            $query = [];
            foreach ($row as $key => $value) {
                $query = "$key='$value'";
            }
            $builder->{$where}('('.implode(' AND ', $query).')');
        }

        return $builder->get();
    }

    public function filter($request, $paginate = false) {
        $reflection = new ReflectionObject($this);
        $name = (new ReflectionClass($this->model))->getShortName();
        $namespace = $reflection->getNamespaceName();
        $baseNamespace = substr($namespace, 0, strpos($namespace, '\\Database'));
        $classFilter = "$baseNamespace\\Builders\\Filters\\".$name.'Filter';

        if (class_exists($classFilter)) {
            $filter = new $classFilter($request, $this->model);
            $builder = $filter->build();

            if (null !== $request->page || $paginate) {
                return $builder->paginate();
            }

            return $builder->get();
        }

        if ($paginate) {
            return $this->paginate();
        }

        return $this->get();
    }

    public function dataFilter($request, $paginate = false) {
        return $this->filter($request, $paginate);
    }

    public function crudFilter($request, $data = []) {
        $model = $this->model->getTable();
        $viewData = [
            'items' => $this->filter($request, true),
            'model' => $model,
            'plural' => Str::plural($model),
            'singular' => Str::singular($model),
        ] + $data;

        return $viewData;
    }

    public function crudList($view, $request, $data = []) {
        $viewData = $this->crudFilter($request, $data);

        return [
            'view' => view($view, $viewData)->render(),
        ] + $viewData;
    }

    public function getPage($request, $data = []) {
        $model = $this->model->getTable();
        $singular = Str::singular($model);
        $plural = Str::plural($model);
        $viewData = [
            $plural => $this->filter($request, true),
            $singular => $model,
        ] + $data;

        return $viewData;
    }

    public function search($view, $request, $data = []) {
        $viewData = $this->getPage($request, $data);

        return [
            'view' => view($view, $viewData)->render(),
        ] + $viewData;
    }

    public function weeks($weeks = 1) {
        return $this;
    }

    public function hours($hours = 1) {
        return $this;
    }

    public function minutes($minutes = 1) {
        return $this;
    }

    public function seconds($seconds = 60) {
        return $this;
    }
}
