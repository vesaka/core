<?php

namespace Vesaka\Core\Models;

/**
 * Description of Relation
 *
 * @author vesak
 */
class Relation extends Model {
    protected $table = 'relations';

    protected $fillable = ['model_type', 'model_id', 'relation_type', 'relation_id', 'name', 'status', 'order'];
}
