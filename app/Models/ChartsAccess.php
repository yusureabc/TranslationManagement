<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChartsAccess extends Model
{
    private $action = 'charts_access';


    /**
     * ChartsAccess belongs to many platforms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function platforms()
    {
        return $this->belongsTo('App\Models\Platform')->withTimestamps();
    }

    /**
     * ChartsAccess belongs to many products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsTo('App\Models\Product')->withTimestamps();
    }

}
