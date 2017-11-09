<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

/**
 * Language Model
 */
class Language extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'language';

    protected $guarded  = [];

    public $timestamps = false;
}
