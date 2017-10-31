<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use App\Traits\ActionButtonAttributeTrait;

class Salesdata extends Model implements Transformable
{
    use TransformableTrait;
    use ActionButtonAttributeTrait;


    private $action = 'salesdata';

    protected $fillable = ['num','platform_id','product_id','amount', 'data_time'];


}
