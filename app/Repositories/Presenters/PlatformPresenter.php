<?php

namespace App\Repositories\Presenters;

use App\Repositories\Transformers\PlatformTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PlatformPresenter
 *
 * @package namespace App\Repositories\Presenters;
 */
class PlatformPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PlatformTransformer();
    }
}
