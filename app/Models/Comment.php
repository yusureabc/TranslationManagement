<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

/**
 * Comment Model
 */
class Comment extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'comment';

    protected $table = 'comments';

    protected $guarded  = [];

    public function getFullTableName()
    {
        return $this->table;
    }
}
