<?php

namespace App\Repositories\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Salesdata;

/**
 * Class SalesdataTransformer
 * @package namespace App\Repositories\Transformers;
 */
class SalesdataTransformer extends TransformerAbstract
{

    /**
     * Transform the \Salesdata entity
     * @param \Salesdata $model
     *
     * @return array
     */
    public function transform(Salesdata $model)
    {
        return [
            'id'            => (int) $model->id,
            'num'           => (int) $model->num,
            'platform_id'   => (int) $model->platform_id,
            'product_id'    => (int) $model->product_id,
            'amount'        => (double) $model->amount,
            'data_time'     => (string) $model->data_time,
            'created_at'    => $model->created_at,
            'updated_at'    => $model->updated_at
        ];
    }
}
