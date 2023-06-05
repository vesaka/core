<?php

namespace Vesaka\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Description of Meta
 *
 * @author Vesaka
 */
class Meta extends Model {
    protected $table = 'meta';

    protected $fillable = ['type', 'model_id', 'key', 'value'];

    public $timestamps = true;
}
