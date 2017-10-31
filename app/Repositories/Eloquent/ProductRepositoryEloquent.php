<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ProductRepository;
use App\Models\Product;
use App\Repositories\Validators\ProductValidator;

/**
 * Class PlatformRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ProductRepositoryEloquent extends BaseRepository implements ProductRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Specify Validator class name
     *
     * @return mixed
     */
   /* public function validator()
    {

        return ProductValidator::class;
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
    public function getProductList($start,$length,$search,$order)
    {
        $product = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $product = $product->where('name', 'like', "%{$search['value']}%")
                    ->orWhere('model','like', "%{$search['value']}%")
                    ->orWhere('code','like', "%{$search['value']}%");
            }else{
                $product = $product->where('name', $search['value'])
                    ->orWhere('model', $search['value'])
                    ->orWhere('code', $search['value']);
            }
        }

        $count = $product->count();

        $product = $product->orderBy($order['name'], $order['dir']);

        $products = $product->offset($start)->limit($length)->get();

        return compact('count','products');
    }

    public function allProducts()
    {
        return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createProduct($attributes)
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
