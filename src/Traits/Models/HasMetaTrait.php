<?php

namespace Vesaka\Core\Traits\Models;

use Illuminate\Http\Request;

/**
 * @author vesak
 */
trait HasMetaTrait {
    public function storeMeta(Request $request) {
        $request_data = $request->meta ?? [];
        if (is_string($request_data)) {
            try {
                $request_data = json_decode($request_data, true);
            } catch (\JsonException $ex) {
                return $this;
            }
        }
        $data = $this->meta->groupBy('name');

        if (is_iterable($data) && is_iterable($request_data)) {
            $deletables = [];
            $newMeta = [];
            $class = get_class($this);

            $metables = [];
            if (method_exists($this, 'getMetables')) {
                $metables = $this->getMetables();
            }

            foreach ($request_data as $name => $value) {
                if ($data->has($name) && in_array($name, $metables)) {
                    $deletables[] = $name;
                }

                if (in_array($name, $this->metables)) {
                    $value = explode(',', $value);
                }

                if (is_array($value)) {
                    foreach ($value as $item) {
                        $newMeta[] = [
                            'model_id' => $this->id,
                            'name' => $name,
                            'value' => $item ?? '',
                            'type' => $class,
                        ];
                    }
                } else {
                    $newMeta[] = [
                        'model_id' => $this->id,
                        'name' => $name,
                        'value' => $value ?? '',
                        'type' => $class,
                    ];
                }
            }

            $this->meta()->whereIn('name', $deletables)
                ->where('type', $class)
                ->forceDelete();
            $this->meta()->insert($newMeta);

            return $this;
        }
    }
}
