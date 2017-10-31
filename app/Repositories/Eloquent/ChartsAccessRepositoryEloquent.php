<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ChartsAccessRepository;
use App\Models\ChartsAccess;
use App\Repositories\Validators\ChartsAccessValidator;

/**
 * Class ChartsAccessRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ChartsAccessRepositoryEloquent extends BaseRepository implements ChartsAccessRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ChartsAccess::class;
    }



    /**
     * 获取所有的权限
     * @author Sheldon
     * @date   2017-04-19T13:20:18+0800
     * @return [type]                   [description]
     */
    public function allAccesses()
    {
        return $this->model->all();
    }
}
