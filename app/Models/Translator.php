<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

/**
 * Translator Model
 */
class Translator extends Model implements Transformable 
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'translate';

    protected $guarded  = ['id'];

    public $timestamps = false;

    public function language()
    {
        return $this->belongsTo( 'App\Models\Language' );
    }
}