<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

/**
 * Key Model
 */
class Key extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'key';

    protected $table = 'keys';

    protected $guarded  = [];

    public $timestamps = false;

    public function content()
    {
        return $this->hasOne( 'App\Models\Content' );
    }

    public function getFullTableName()
    {
        return $this->table;
    }
}
