<?php

namespace Vesaka\Core\Observers;

use File;
use Vesaka\Core\Abstracts\BaseObserver;
use Vesaka\Core\Models\Model;

/**
 * Description of ModelObserver
 *
 * @author Vesaka
 */
class ModelObserver extends BaseObserver {
    public const GROUP = 'gallery';

    public const WEBP_GROUP = 'webp_gallery';

    protected $countable_meta = ['tags'];

    public function saved(Model $model) {
        if (0 < intval(request('id'))) {
            $this->onUpdated($model);
        } else {
            $this->onCreated($model);
        }
    }

    public function onUpdated(Model $model) {
        $request = request();
        $crop = $request->crop ?? [];
        $media = $model->getFirstMedia(FEATURED_IMAGE);

        $cropChanged = false;
        if ($media) {
            $cropData = $media->getCustomProperty('crop');
            foreach ($crop as $key => $value) {
                if (isset($cropData[$key])) {
                    if (intval($cropData[$key]) === intval($value)) {
                        $cropChanged = true;
                        break;
                    }
                } else {
                    $cropChanged = true;
                    break;
                }
            }
        }

        $newFile = null;
        if ($request->hasFile('file')) {
            $newFile = $request->file('file');
        } elseif ($cropChanged) {
            $oldFile = $media->getPath();
            $newFile = storage_path('media-library/temp/'.basename($oldFile));
            File::copy($oldFile, $newFile);
        }

        if ($newFile) {
            $model->clearMediaCollection(FEATURED_IMAGE);
            $model->addMedia($newFile)
                ->withCustomProperties(['crop' => $crop])
                ->toMediaCollection(FEATURED_IMAGE);
            if (file_exists($newFile)) {
                File::delete($newFile);
            }
        }

        $model->syncCategories($request->category ?? []);
        $this->storeMeta($model, $request->meta ?? []);
    }

    public function onCreated(Model $model) {
        $request = request();

        $model->syncCategories($request->category ?? []);
        $this->storeMeta($model, $request->meta ?? []);

        $model->addMedia($request->file('file'))
            ->withCustomProperties(['crop' => $request->crop])
            ->toMediaCollection(FEATURED_IMAGE);
    }

    public function deleting(Model $model) {
        $model->meta()->delete();
    }

    public function deleted(Model $model) {
        $model->tags()->delete();
        $model->meta()->delete();
        $model->clearMediaGroup(self::GROUP);
        $model->clearMediaGroup(self::WEBP_GROUP);
    }

    protected function storeMeta($model, array|string $request_data) {
        if (is_string($request_data)) {
            try {
                $request_data = json_decode($request_data, true);
            } catch (\JsonException $ex) {
                return;
            }
        }
        $data = $model->meta->groupBy('name');

        if (is_iterable($data) && is_iterable($request_data)) {
            $deletables = [];
            $newMeta = [];
            $class = get_class($model);

            $metables = [];
            if (method_exists($model, 'getMetables')) {
                $metables = $model->getMetables();
            }

            foreach ($request_data as $name => $value) {
                if ($data->has($name) && in_array($name, $metables)) {
                    $deletables[] = $name;
                }

                if (in_array($name, $this->countable_meta)) {
                    $value = explode(',', $value);
                }

                if (is_array($value)) {
                    foreach ($value as $item) {
                        $newMeta[] = [
                            'model_id' => $model->id,
                            'name' => $name,
                            'value' => $item ?? '',
                            'type' => $class,
                        ];
                    }
                } else {
                    $newMeta[] = [
                        'model_id' => $model->id,
                        'name' => $name,
                        'value' => $value ?? '',
                        'type' => $class,
                    ];
                }
            }

            $model->meta()->whereIn('name', $deletables)
                ->where('type', $class)
                ->forceDelete();
            $model->meta()->insert($newMeta);
        }
    }
}
