<?php

namespace Vesaka\Core\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserMeta extends Pivot {
    protected $table = 'user_meta';

    protected $fillable = ['user_id', 'key', 'value'];

    public $timestamps = true;

    public function player() {
        return $this->belongsTo('Vesaka\Core\Model\User');
    }
}
