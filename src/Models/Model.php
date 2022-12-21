<?php

namespace Vesaka\Core\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use ActionDigital\Admin\Model\User;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Vesaka\Core\Models\Category;

/**
 * Description of Model
 *
 * @author vesak
 */
class Model extends CoreModel {

    use SoftDeletes;

    protected $table = 'models';
    protected $fillable = ['author_id', 'title', 'content', 'type', 'status', 'name'];
    protected $__locales;
    protected $attributes = [
        'type' => 'model',
        'status' => 'pending'
    ];

    public function author() {
        return $this->belongsTo(User::class);
    }

    public function categories() {
        return $this->morphToMany(Category::class, 'relation', 'relations', 'relation_id', 'model_id')
                        ->where('relation_type', self::class);
    }

    public function syncCategories(array $ids = []) {
        return $this->categories()->syncWithPivotValues($ids, [
                    'model_type' => Category::class,
                    'name' => 'post-category',
                    'order' => 1
        ]);
    }

    public function related_posts() {
        return $this->hasMany(self::class, 'type', 'type')->where('id', '!=', $this->id);
    }

    public function setNameAttribute($value) {
        if (!empty($value) || null === $value) {
            $this->attributes['name'] = Str::slug($this->title);
        }
    }

    public function setAuthorIdAttribute($value) {
        if (!empty($value) || null === $value) {
            $this->attributes['author_id'] = auth()->id() ?? 1;
        }
    }

    public function trans(string $path): string {
        if (isset($this->locales[$path]) && is_string($this->locales[$path])) {
            return $this->locales[$path];
        }

        return $path;
    }

    public function getLocalesAttribute() {
        if (!isset($this->__locales)) {
            $this->__locales = Arr::dot(trans('cms::documents.' . $this->type) ?? []);
        }

        return $this->__locales;
    }

    public function getUnderscoreTypeAttribute() {
        return str_replace(['-', '.', ''], '_', $this->type);
    }

    public function getKebabTypeAttribute() {
        return Str::kebab($this->type);
    }

    public function getInfoAttribute() {
        $data = [];

        if ($this->relationLoaded('media')) {

            $filepath = $this->getFirstMediaUrl(MEDIA_GROUP_GALLERY, MEDIA_CONVERSION_ORIGINAL);
            $image = \Storage::disk('public')->path(Str::of($filepath)->replace(url('storage/'), ''));
            if (file_exists($image) && is_file($image)) {
                $info = getimagesize($image);
                $data = [
                    'src' => Str::replace('/storage/', '/src/', $filepath),
                    'width' => $info[0],
                    'height' => $info[1],
                    'ratio' => round($info[0] / $info[1], 2)
                ];
            }
        }

        return (object) array_merge([
                    'src' => '',
                    'width' => 0,
                    'height' => 0,
                    'ratio' => 1
                        ], $data);
    }

}
