<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Product;

/**
 * Class ProductTransformer
 * @package namespace App\Repositories\Transformers;
 */
class ProductTransformer extends TransformerAbstract
{

    /**
     * Transform the \Product entity
     * @param \Product $model
     *
     * @return array
     */
    public function transform(Product $model)
    {
        return [
            'id'            => (int) $model->id,
            'name'          => (string) $model->name,
            'model'         => (string) $model->model,
            'code'          => (string) $model->code,
            'sort'          => (int) $model->sort,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}
