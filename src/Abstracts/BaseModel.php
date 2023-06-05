<?php

namespace Vesaka\Core\Abstracts;

use Illuminate\Database\Eloquent\Model as AppModel;
use Vesaka\Core\Traits\HasCollectionTrait;
use Vesaka\Core\Traits\UserTimezoneAware;

/**
 * Description of Model
 *
 * @author Vesaka
 */
class BaseModel extends AppModel {
    use UserTimezoneAware;
    use HasCollectionTrait;

    public $timestamps = true;

    public function meta() {
        return $this->hasMany('Vesaka\Core\Models\Meta', 'model_id', 'id')->where('type', self::class);
    }

    public function json($attribute = null) {
        try {
            return json_decode($this->{$attribute});
        } catch (\JsonException $ex) {
            return $this->{$attribute};
        }
    }
}
