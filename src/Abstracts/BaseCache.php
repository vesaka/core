<?php

namespace Vesaka\Core\Abstracts;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Description of AbstractCacheDecorator
 *
 * @author User
 */
class BaseCache implements BaseInterface {
    protected $repository;

    protected $cache;

    protected $minutes = 1;

    protected $seconds = 60;

    protected string $name;

    protected $tags = [];

    protected string $plural;

    /**
     * @param  AbstractRepository  $repository
     * @param  Cache  $cache
     */
    public function __construct(BaseRepository $repository, Repository $cache) {
        $this->repository = $repository;
        $this->cache = $cache;
        $this->name = Str::singular($repository->getTable());
        $this->plural = Str::plural($repository->getTable());
        $this->tags = [$this->name];
    }

    public function __call($method, $args) {
        return call_user_func_array([$this->repository, $method], $args);
    }

    public function getModel(): Model {
        return $this->repository->getModel();
    }

    public function observe($class) {
        return $this->repository->getModel()->observe($class);
    }

    public function filter($request, $paginate = false) {
        //return $this->raw();
        return $this->tags($this->name.'.page.'.$request->page, (int) $paginate)
            ->fetch($this->name.'.page.'.$request->page);
    }

    public function all() {
        return $this->tags($this->name)->fetch('all-'.$this->plural);
    }

    public function find($key) {
        return $this->raw();

        return $this->tags([$this->name, "$this->name.$key"])->fetch($key);
    }

    public function findWith($key, $with = []) {
        return $this->raw();
        if (! is_array($with)) {
            $with = [$with];
        }
        $keys = Arr::isAssoc($with) ? array_keys($with) : $with;

        return $this->tags(array_merge([$this->name, "$this->name.$key"], $keys))->fetch();
    }

    public function create($data) {
        $model = $this->repository->create($data);
        $this->creating($model);
        $this->tagResult($this->cache
            ->tags([$this->name, "$this->name.$model->id"])
            ->put($model->id, $model, $this->seconds));
        $this->forget('all');
        $this->created($model);

        return $model;
    }

    public function update($model, $data) {
        if (! is_object($model)) {
            $model = $this->find($model);
        }

        $this->repository->update($model, $data);
        $this->updating($model);
        $this->forget($model->id);
        $this->forget('all');
        $this->updated($model);
    }

    public function delete($model) {
        if (! is_object($model)) {
            $key = $model;
            $model = $this->repository->destroy($model);
        } else {
            $key = $model->id;
            $model->remove();
        }

        $this->deleting($model);
        $this->cache->tags($this->tags)->forget($key);
        $this->forget('all');
        $this->deleted($model);
    }

    public function put($key, $value, $seconds = null) {
        return $this->tagResult($this->cache->tags($this->tags)->put($key, $value, $seconds ?? $this->seconds));
    }

    public function forget($key = null) {
        if (! $key) {
            $key = implode('.', $this->tags);
        }
        $this->cache->tags($this->tags)->forget($key);

        return $this;
    }

    public function fetch($key = null) {
        $log = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);

        if (! $key) {
            $key = implode('.', $this->tags);
        }

        return $this->cache->tags($this->tags)->remember($key, $this->seconds, function () use ($log) {
            return call_user_func_array([$this->repository, $log[1]['function']], $log[1]['args']);
        });
    }

    public function refresh() {
        $log = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $result = call_user_func_array([$this->repository, $log[1]['function']], $log[1]['args']);

        return $result;
    }

    public function raw() {
        $log = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, 2);
        $result = call_user_func_array([$this->repository, $log[1]['function']], $log[1]['args']);

        return $result;
    }

    public function remember($key, $closure, $seconds = null) {
        if (! $key) {
            $key = implode('.', $this->tags);
        }

        return $this->cache->tags($this->tags)->remember($key, $seconds ?? $this->seconds, $closure);
    }

    public function forever($key, $closure) {
        return $this->cache->tags($this->tags)->rememberForever($key, $closure);
    }

    public function flush() {
        $this->cache->tags($this->tags)->flush();

        return $this;
    }

    public function tags($tags = null) {
        if (null === $tags) {
            $this->tags = [$this->name];

            return $this;
        }

        if (is_array($tags)) {
            $this->tags = array_merge($this->tags, $tags);
        } else {
            $this->tags = array_merge($this->tags, func_get_args());
        }

        $this->tags = array_unique($this->tags);

        return $this;
    }

    public function tag($data) {
        if (! $data instanceof Collection) {
            $data = collect($data);

            $data->each(function ($model) {
                $singular = Str::singular($model->getTable());
            });
        }
    }

    public function weeks($weeks = 1) {
        $this->seconds = 60 * 60 * 60 * 7 * $weeks;

        return $this;
    }

    public function days($days = 1) {
        $this->seconds = 60 * 60 * 60 * $days;

        return $this;
    }

    public function hours($hours = 1) {
        $this->seconds = 60 * 60 * $hours;

        return $this;
    }

    public function minutes($minutes = null) {
        if ($minutes) {
            $this->minutes = intval($minutes);
            $this->seconds = 60 * $this->minutes;
        }

        return $this;
    }

    public function seconds($seconds = null) {
        if ($seconds) {
            $this->seconds = intval($seconds);
        }

        return $this;
    }

    protected function creating($model) {
        return $this;
    }

    protected function created($model) {
        $this->afterChange($model);

        return $this;
    }

    protected function updating($model) {
        return $this;
    }

    protected function updated($model) {
        $this->afterChange($model);

        return $this;
    }

    protected function deleting($model) {
        return $this;
    }

    protected function deleted($model) {
        $this->afterChange($model);

        return $this;
    }

    protected function beforeChange($model) {
        return $this;
    }

    public function afterChange($model) {
        return $this;
    }

    public function tagResult($result) {
        if ($result instanceof Collection) {
            $this->tagCollection($result);
        } elseif ($result instanceof Model) {
            $this->tagModel($result);
        }

        return $result;
    }

    public function tagCollection(Collection $collection) {
        $model = $collection->first();
        if (! $model) {
            return;
        }

        $plural = $this->tags(Str::plural($model->getTable()));
        foreach ($collection as $row) {
            if ($row instanceof Model) {
                $this->tagModel($row);
            }
        }

        return $this;
    }

    public function tagModel(Model $model) {
        $name = Str::singular($model->getTable());
        $items = $model->getRelations();
        foreach ($items as $item) {
            $this->tagResult($item);
        }
        $this->tags("$name.$model->id");
    }
}
