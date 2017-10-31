<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\SalesdataRepository;
use App\Models\Salesdata;
use App\Repositories\Validators\SalesdataValidator;

/**
 * Class PlatformRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class SalesdataRepositoryEloquent extends BaseRepository implements SalesdataRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Salesdata::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
    /*public function validator()
    {

        return SalesdataValidator::class;
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
    public function getSalesdataList($start,$length,$search,$order)
    {
        $salesdata = $this->model;

        if (isset($search['platform_id'])) {
            $salesdata = $salesdata->where('platform_id' ,'=' , $search['platform_id']);
        }
        if (isset($search['product_id'])) {
            $salesdata = $salesdata->where('product_id' ,'=' , $search['product_id']);
        }
        if (isset($search['platform_ids'])) {
            $salesdata = $salesdata->whereIn('platform_id' , $search['platform_ids']);
        }
        if (isset($search['product_ids'])) {
            $salesdata = $salesdata->whereIn('product_id', $search['product_ids']);
        }
        if ($search['data_time_start']) {
            $salesdata = $salesdata->where('data_time' ,'>=' , $search['data_time_start']);
        }
        if ($search['data_time_end']) {
            $salesdata = $salesdata->where('data_time' ,'<=' , $search['data_time_end']);
        }

        $count = $salesdata->count();

        $salesdata = $salesdata->orderBy($order['name'], $order['dir']);

        $salesdatas = $salesdata->offset($start)->limit($length)->get();

        return compact('count','salesdatas');
    }

    /**
     * 查询销售数据
     * @author Sheldon
     * @date   2017-04-26T11:56:28+0800
     * @param  [type]                   $select  [搜索字段]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @param  [type]                   $groupBy  [分组数据]
     * @return [type]                           [查询结果对象]
     */
    public function getSalesdataGrouply($select, $search,$order, $groupBy)
    {
        $salesdata = $this->model;

        $salesdata = $salesdata->select($select);

        if (isset($search['platform_id'])) {
            $salesdata = $salesdata->where('platform_id' ,'=' , $search['platform_id']);
        }
        if (isset($search['product_id'])) {
            $salesdata = $salesdata->where('product_id' ,'=' , $search['product_id']);
        }
        if (isset($search['platform_ids'])) {
            $salesdata = $salesdata->whereIn('platform_id' , $search['platform_ids']);
        }
        if (isset($search['product_ids'])) {
            $salesdata = $salesdata->whereIn('product_id', $search['product_ids']);
        }
        if ($search['data_time_start']) {
            $salesdata = $salesdata->where('data_time' ,'>=' , $search['data_time_start']);
        }
        if ($search['data_time_end']) {
            $salesdata = $salesdata->where('data_time' ,'<=' , $search['data_time_end']);
        }

        $salesdata = $salesdata->orderBy($order['name'], $order['dir']);

        $salesdata = $salesdata->groupBy($groupBy);

        $salesdatas = $salesdata->get();

        return $salesdatas;
    }

    public function createSalesdata($attributes)
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
