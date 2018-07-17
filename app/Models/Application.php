<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

class Application extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    protected $table = 'apps';

    private $action = 'application';

    protected $guarded  = ['id'];
}