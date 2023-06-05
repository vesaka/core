<?php

namespace Vesaka\Core\Models;

/**
 * Description of Meta
 *
 * @author Vesaka
 */
class Settings extends CoreModel {
    protected $table = 'settings';

    protected $fillable = ['type', 'model_id', 'name', 'value'];

    public $timestamps = true;
}
