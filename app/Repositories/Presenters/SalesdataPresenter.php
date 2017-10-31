<?php

namespace App\Repositories\Presenters;

use App\Repositories\Transformers\SalesdataTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class SalesdataPresenter
 *
 * @package namespace App\Repositories\Presenters;
 */
class SalesdataPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new SalesdataTransformer();
    }
}
