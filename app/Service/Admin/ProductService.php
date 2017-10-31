<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProductRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
/**
* 平台service
*/
class ProductService extends BaseService
{

	private $product;

	function __construct(ProductRepositoryEloquent $product)
	{
		$this->product =  $product;
	}
	/**
	 * datatables获取数据
	 * @author Sheldon
	 * @date   2017-04-18T15:54:46+0800
	 * @return [type]                   [description]
	 */
	public function ajaxIndex()
	{
		// datatables请求次数
		$draw = request('draw', 1);
		// 开始条数
		$start = request('start', config('admin.golbal.list.start'));
		// 每页显示数目
		$length = request('length', config('admin.golbal.list.length'));
		// datatables是否启用模糊搜索
		$search['regex'] = request('search.regex', false);
		// 搜索框中的值
		$search['value'] = request('search.value', '');
		// 排序
		$order['name'] = request('columns.' .request('order.0.column',0) . '.name');
		$order['dir'] = request('order.0.dir','asc');

		$result = $this->product->getProductList($start,$length,$search,$order);

		$products = [];

		if ($result['products']) {
			foreach ($result['products'] as $v) {
				$v->actionButton = $v->getActionButtonAttribute();
                $products[] = $v;
			}
		}

		return [
			'draw' => $draw,
			'recordsTotal' => $result['count'],
			'recordsFiltered' => $result['count'],
			'data' => $products,
		];
	}

    /**
     * 获取所有产品并缓存
     * @author Sheldon
     * @date   2017-04-18T16:12:11+0800
     * @return [type]                   [Array]
     */
    public function getProductSetCache()
    {
        $productList = $this->product->allProducts();
        if ($productList) {
            // 缓存数据
            cache()->forever(config('admin.global.cache.productList'), $productList);
            return $productList;

        }
        return '';
    }

    /**
     * 根据ID从缓冲中查找数据
     * @author Sheldon
     * @date   2017-04-21T16:25:59+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findProductByIdFromCache ($id)
    {
        $products = $this->getProductList();
        $return = [];
        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product['id'] == $id) {
                    $return = $product;
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * 获取所以平台数据
     * @author Sheldon
     * @date   2016-11-04T10:45:38+0800
     * @return [type]                   [description]
     */
    public function getProductList()
    {
        // 判断数据是否缓存
        if (cache()->has(config('admin.global.cache.productList'))) {
            return cache()->get(config('admin.global.cache.productList'));
        }

        return $this->getProductSetCache();
    }

    /**
     * 添加产品
     * @author Sheldon
     * @date   2017-04-18T16:10:32+0800
     * @param  [type]                   $attributes [表单数据]
     * @return [type]                               [Boolean]
     */
    public function storeProduct($attributes)
    {
        try {
            $result = $this->product->create($attributes);
            if ($result) {
                // 更新缓存
                $this->getProductSetCache();
            }
            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.product.create_success'):trans('admin/alert.product.create_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 根据ID查找数据
     * @author Sheldon
     * @date   2017-04-18T16:25:59+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findProductById($id)
    {
        $product = $this->product->find($id);
        if ($product){
            return $product;
        }
        // TODO替换正查找不到数据错误页面
        abort(404);
    }

    /**
     * 修改数据
     * @author Sheldon
     * @date   2017-04-18
     * @param  [type]     $attributes [表单数据]
     * @param  [type]     $id         [resource路由id]
     * @return [type]                 [Array]
     */
    public function updateProduct($attributes,$id)
    {
        // 防止用户恶意修改表单id，如果id不一致直接跳转500
        if ($attributes['id'] != $id) {
            return [
                'status' => false,
                'message' => trans('admin/errors.user_error'),
            ];
        }
        try {
            $isUpdate = $this->product->update($attributes,$id);
            if ($isUpdate) {
                // 更新缓存
                $this->getProductSetCache();
            }
            return [
                'status' => $isUpdate,
                'message' => $isUpdate ? trans('admin/alert.product.edit_success'):trans('admin/alert.product.edit_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }


    }
    /**
     * 删除
     * @author Sheldon
     * @date   2017-04-18
     * @param  [type]     $id [菜单ID]
     * @return [type]         [description]
     */
    public function destroyProduct($id)
    {
        try {
            $isDestroy = $this->product->delete($id);
            if ($isDestroy) {
                // 更新缓存
                $this->getProductSetCache();
            }
            flash_info($isDestroy,trans('admin/alert.product.destroy_success'),trans('admin/alert.product.destroy_error'));
            return $isDestroy;
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    public function orderable($nestableData)
    {
        try {
            $dataArray = json_decode($nestableData,true);
            $bool = false;
            DB::beginTransaction();
            foreach ($dataArray as $k => $v) {
                $this->product->update(['sort' => $v['sort']],$v['id']);
                $bool = true;
            }
            DB::commit();
            if ($bool) {
                // 更新缓存
                $this->getProductSetCache();
            }
            return [
                'status' => $bool,
                'message' => $bool ? trans('admin/alert.product.order_success'):trans('admin/alert.product.order_error')
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            DB::rollBack();
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }
}