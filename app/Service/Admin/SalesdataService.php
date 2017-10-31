<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\PlatformRepositoryEloquent;
use App\Repositories\Eloquent\ProductRepositoryEloquent;
use App\Repositories\Eloquent\SalesdataRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
/**
* 销售数据service
*/
class SalesdataService extends BaseService
{

	protected $salesdata;

    private $platform;

    private $product;

    private $platformService;

    private $productService;

    private $user;

	function __construct(
	    SalesdataRepositoryEloquent $salesdata,
        PlatformRepositoryEloquent $platform,
        ProductRepositoryEloquent $product,
        UserService $user
    )
	{
		$this->salesdata =  $salesdata;

        $this->platform =  $platform;

        $this->product =  $product;

        $this->platformService =  new PlatformService($platform);

        $this->productService =  new ProductService($product);

        $this->user =  $user;

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

        //平台
        $platform_id = request('platform_id', 0);

        //产品
        $product_id = request('product_id', 0);

        $platforms = $this->user->getMyPlatforms();
        $products = $this->user->getMyProducts();
        $platform_ids = array_pluck($platforms, 'id');
        $product_ids = array_pluck($products, 'id');
        if (in_array($platform_id, $platform_ids)) {
            $search['platform_id'] = $platform_id;
        } else {
            $search['platform_ids'] = $platform_ids;
        }

        if (in_array($product_id, $product_ids)) {
            $search['product_id'] = $product_id;
        } else {
            $search['product_ids'] = $product_ids;
        }


        //日期
        $search['data_time_start'] = request('data_time_start', null);
        $search['data_time_end'] = request('data_time_end', null);

		// 排序

		$order['name'] = request('columns.' .request('order.5.column',5) . '.name', 'data_time');
		$order['dir'] = request('order.0.dir','desc');
		$result = $this->salesdata->getSalesdataList($start,$length,$search,$order);

		$salesdatas = [];

		if ($result['salesdatas']) {
			foreach ($result['salesdatas'] as $v) {
			    $tmpPlatfom = $this->platformService->findPlatformByIdFromCache($v->platform_id);
                $tmpProduct = $this->productService->findProductByIdFromCache($v->product_id);
			    $v->platform = array_key_exists('name', $tmpPlatfom) ? $tmpPlatfom['name'] : '';
                $v->product = array_key_exists('name', $tmpProduct) ? $tmpProduct['name'] : '';
				$v->actionButton = $v->getActionButtonAttribute();
                $salesdatas[] = $v;
			}
		}

		return [
			'draw' => $draw,
			'recordsTotal' => $result['count'],
			'recordsFiltered' => $result['count'],
			'data' => $salesdatas,
		];
	}


    /**
     * 创建用户视图数据
     * @author Sheldon
     * @date   2017-04-21T13:29:53+0800
     * @return [type]                   [description]
     */
    public function createView()
    {
        return [$this->platformService->getPlatformList(), $this->productService->getProductList()];
    }

    /**
     * 添加
     * @author Sheldon
     * @date   2017-04-18T16:10:32+0800
     * @param  [type]                   $attributes [表单数据]
     * @return [type]                               [Boolean]
     */
    public function storeSalesdata($attributes)
    {
        try {
            $result = $this->salesdata->create($attributes);
            if ($result) {

            }
            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.salesdata.create_success'):trans('admin/alert.salesdata.create_error'),
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
    public function findSalesdataById($id)
    {
        $salesdata = $this->salesdata->find($id);
        if ($salesdata){
            return $salesdata;
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
    public function updateSalesdata($attributes,$id)
    {
        // 防止用户恶意修改表单id，如果id不一致直接跳转500
        if ($attributes['id'] != $id) {
            return [
                'status' => false,
                'message' => trans('admin/errors.user_error'),
            ];
        }
        try {
            $isUpdate = $this->salesdata->update($attributes,$id);
            if ($isUpdate) {

            }
            return [
                'status' => $isUpdate,
                'message' => $isUpdate ? trans('admin/alert.salesdata.edit_success'):trans('admin/alert.salesdata.edit_error'),
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
    public function destroySalesdata($id)
    {
        try {
            $isDestroy = $this->salesdata->delete($id);
            if ($isDestroy) {

            }
            flash_info($isDestroy,trans('admin/alert.salesdata.destroy_success'),trans('admin/alert.salesdata.destroy_error'));
            return $isDestroy;
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }
}