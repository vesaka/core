<?php

namespace Vesaka\Core\Models;

use Illuminate\Database\Eloquent\Model as AppModel;
//use Vesaka\Core\Traits\UserTimezoneAware;
//use Vesaka\Core\Traits\HasCollectionTrait;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Vesaka\Core\Traits\HasCollectionTrait;

/**
 * Description of Model
 *
 * @author Vesaka
 */
class CoreModel extends AppModel implements HasMedia {
    use InteractsWithMedia;
    use HasCollectionTrait;

    public $timestamps = true;

    protected $__meta = null;

    protected $metables = [];

    public function meta() {
        return $this->hasMany(Meta::class, 'model_id', 'id')->where('type', static::class);
    }

    public function getMetables() {
        return $this->metables;
    }

    public function field($name) {
        if (! $this->__meta) {
            $this->__meta = $this->meta->keyBy('key');
        }

        $meta = $this->__meta->get($name);

        return $meta ? $meta->value : null;
    }

    public function json($attribute = null) {
        try {
            return json_decode($this->{$attribute});
        } catch (\JsonException $ex) {
            return $this->{$attribute};
        }
    }

    public function registerMediaConversions(Media $media = null): void {
        $options = config('frontend.gallery');
        $crop = array_merge($options['crop'], request('crop', []));
        foreach ($options['conversions'] as $name => $conversion) {
            $this->addMediaConversion($name)
                ->manualCrop($crop['width'], $crop['height'], $crop['x'], $crop['y'])
                ->fit(Manipulations::FIT_CROP, $conversion['width'], $conversion['height'])
                ->keepOriginalImageFormat()
                ->nonQueued();

            $this->addMediaConversion($name.'-webp')
                ->format(Manipulations::FORMAT_WEBP)
                ->manualCrop($crop['width'], $crop['height'], $crop['x'], $crop['y'])
                ->fit(Manipulations::FIT_CROP, $conversion['width'], $conversion['height'])
                ->nonQueued();
        }
    }
}
