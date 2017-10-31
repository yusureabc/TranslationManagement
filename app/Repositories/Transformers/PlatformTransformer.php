<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Platform;

/**
 * Class PlatformTransformer
 * @package namespace App\Repositories\Transformers;
 */
class PlatformTransformer extends TransformerAbstract
{

    /**
     * Transform the \Platform entity
     * @param \Platform $model
     *
     * @return array
     */
    public function transform(Platform $model)
    {
        return [
            'id'            => (int) $model->id,
            'name'          => (string) $model->name,
            'slug'          => (string) $model->slug,
            'url'           => (string) $model->url,
            'logo'          => (string) $model->logo,
            'sort'          => (int) $model->sort,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}
