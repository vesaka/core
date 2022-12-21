<?php
namespace Vesaka\Core\Abstracts;
/**
 * Description of BaseObserver
 *
 * @author Vesaka
 */
class BaseObserver {
    
    protected function storeMeta($model, array|string $request_data) {
        if (is_string($request_data)) {
            try {
                $request_data = json_decode($request_data, true);
            } catch (\JsonException $ex) {
                return;
            }
        }
        $data = $model->meta->keyBy('key');

        if (is_iterable($data) && is_iterable($request_data)) {
            $deletables = [];
            $newMeta = [];
            $class = get_class($model);
            
            $metables = [];
            if (method_exists($model, 'getMetables')) {
                $metables = $model->getMetables();
            }

            foreach($request_data as $name => $value) {
                if ($data->has($name) && in_array($name, $metables)) {
                    $deletables[] = $name;
                }
                
                if (is_array($value)) {
                    foreach($value as $item) {
                        $newMeta[] = [
                            'model_id' => $model->id,
                            'name' => $name,
                            'value' => $item ?? '',
                            'type' => $class
                        ];
                    }
                } else {
                    $newMeta[] = [
                        'model_id' => $model->id,
                        'name' => $name,
                        'value' => $value ?? '',
                        'type' => $class
                    ];
                }
            }
           
            $model->meta()->whereIn('key', $deletables)
                    ->where('type', $class)
                    ->forceDelete();
            $model->meta()->insert($newMeta);
            
        }
    }
    
    protected function store($model, ...$meta) {
        ;
    }
}
