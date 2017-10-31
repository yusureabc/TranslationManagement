<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

class Platform extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;

    private $action = 'platform';

    protected $fillable = ['name','slug','url','logo','sort'];

    public function setSortAttribute($value)
    {
        if ($value && is_numeric($value)) {
            $this->attributes['sort'] = intval($value);
        }else{
            $this->attributes['sort'] = 0;
        }
    }

}
