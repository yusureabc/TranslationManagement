<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

class Product extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;


    private $action = 'product';

    protected $fillable = ['name','model','code','sort'];

    public function setSortAttribute($value)
    {
        if ($value && is_numeric($value)) {
            $this->attributes['sort'] = intval($value);
        }else{
            $this->attributes['sort'] = 0;
        }
    }

}
