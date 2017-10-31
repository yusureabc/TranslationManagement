<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ChartsAccessRepositoryEloquent;
use Exception;

/**
* 图表权限
*/
class ChartsAccessService extends BaseService
{

	private $charts_access;

	function __construct(ChartsAccessRepositoryEloquent $charts_access)
	{
		$this->charts_access =  $charts_access;
	}


	/**
	 * 添加Access
	 * @author Sheldon
	 * @date   2017-5-04T10:32:18+0800
	 * @param  [type]                   $formData [表单中所有的数据]
	 * @return [type]                             [true or false]
	 */
	public function storeChartsAccess($formData)
	{
		try {
			$result = $this->charts_access->create($formData);
			flash_info($result,trans('admin/alert.charts_access.create_success'),trans('admin/alert.charts_access.create_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}

	/**
	 * 修改Access
	 * @author Sheldon
	 * @date   2017-5-04T10:32:18+0800
	 * @param  [type]                   $attributes [表单数据]
	 * @param  [type]                   $id         [resource路由传递过来的id]
	 * @return [type]                               [true or false]
	 */
	public function updateChartsAccess($attributes,$id)
	{
		// 防止用户恶意修改表单id，如果id不一致直接跳转500
		if ($attributes['id'] != $id) {
			abort(500);
		}
		try {
			$result = $this->charts_access->update($attributes,$id);
			flash_info($result,trans('admin/alert.charts_access.edit_success'),trans('admin/alert.charts_access.edit_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
	}
	/**
	 * 删除Access
	 * @author Sheldon
	 * @date   2017-5-04T10:32:18+0800
	 * @param  [type]                   $id [id]
	 * @return [type]                       [true or false]
	 */
	public function destroyChartsAccess($id)
	{
		try {
			$result = $this->charts_access->delete($id);
			flash_info($result,trans('admin/alert.charts_access.destroy_success'),trans('admin/alert.charts_access.destroy_error'));
			return $result;
		} catch (Exception $e) {
			// 错误信息发送邮件
			$this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
			return false;
		}
		
	}
}