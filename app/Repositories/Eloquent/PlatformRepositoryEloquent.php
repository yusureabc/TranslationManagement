<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\PlatformRepository;
use App\Models\Platform;
use App\Repositories\Validators\PlatformValidator;

/**
 * Class PlatformRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class PlatformRepositoryEloquent extends BaseRepository implements PlatformRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Platform::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    /*public function validator()
    {

        return PlatformValidator::class;
    }*/

    /**
     * 查询平台并分页
     * @author Sheldon
     * @date   2017-04-18T12:56:28+0800
     * @param  [type]                   $start  [起始数目]
     * @param  [type]                   $length [读取条数]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @return [type]                           [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getPlatformList($start,$length,$search,$order)
    {
        $platform = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $platform = $platform->where('name', 'like', "%{$search['value']}%")
                    ->orWhere('slug','like', "%{$search['value']}%")
                    ->orWhere('url','like', "%{$search['value']}%");
            }else{
                $platform = $platform->where('name', $search['value'])
                    ->orWhere('slug', $search['value'])
                    ->orWhere('url', $search['value']);
            }
        }

        $count = $platform->count();

        $platform = $platform->orderBy($order['name'], $order['dir']);

        $platforms = $platform->offset($start)->limit($length)->get();

        return compact('count','platforms');
    }

    public function allPlatforms()
    {
        return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createPlatform($attributes)
    {
        $model = new $this->model;
        return $model->fill($attributes)->save();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
