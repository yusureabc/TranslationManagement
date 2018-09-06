<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

/**
 * Project Model
 */
class Project extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'project';

    protected $guarded  = ['id'];

    public static function getName( $id )
    {
        return self::where( ['id' => $id] )->value( 'name' );
    }
}