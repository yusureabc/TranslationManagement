<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;

/**
* Project Service
*/
class ProjectService extends BaseService
{

	private $project;

	function __construct(ProjectRepositoryEloquent $project)
	{
		$this->project =  $project;
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

		$result = $this->project->getProjectList($start,$length,$search,$order);

		$projects = [];

		if ($result['projects']) {
			foreach ($result['projects'] as $v) {
				$v->actionButton = $v->getActionButtonAttribute();
				$projects[] = $v;
			}
		}

		return [
			'draw' => $draw,
			'recordsTotal' => $result['count'],
			'recordsFiltered' => $result['count'],
			'data' => $projects,
		];
	}

    /**
     * 获取所有平台并缓存
     * @author Sheldon
     * @date   2017-04-18T16:12:11+0800
     * @return [type]                   [Array]
     */
    public function getProjectSetCache()
    {
        $projectList = $this->project->allProjects();
        if ($projectList) {
            // 缓存数据
            cache()->forever(config('admin.global.cache.projectList'), $projectList);
            return $projectList;

        }
        return '';
    }

    /**
     * 获取所以平台数据
     * @author Sheldon
     * @date   2016-11-04T10:45:38+0800
     * @return [type]                   [description]
     */
    public function getProjectList()
    {
        // 判断数据是否缓存
        if (cache()->has(config('admin.global.cache.projectList'))) {
            return cache()->get(config('admin.global.cache.projectList'));
        }

        return $this->getProjectSetCache();
    }


    /**
     * 根据ID从缓冲中查找数据
     * @author Sheldon
     * @date   2017-04-21T16:25:59+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function findProjectByIdFromCache ($id)
    {
        $projects = $this->getProjectList();
        $return = [];
        if (!empty($projects)) {
            foreach ($projects as $project) {
                if ($project['id'] == $id) {
                    $return = $project;
                    break;
                }
            }
        }

        return $return;
    }

    /**
     * 添加平台
     * @author Sheldon
     * @date   2017-04-18T16:10:32+0800
     * @param  [type]                   $attributes [表单数据]
     * @return [type]                               [Boolean]
     */
    public function storeProject($attributes)
    {
        try {
            $attributes['logo'] = $this->uploadImage($attributes['logo']);
            $result = $this->project->create($attributes);
            if ($result) {
                // 更新缓存
                $this->getProjectSetCache();
            }
            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.project.create_success'):trans('admin/alert.project.create_error'),
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
    public function findProjectById($id)
    {
        $project = $this->project->find($id);
        if ($project){
            return $project;
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
    public function updateProject($attributes,$id)
    {
        // 防止用户恶意修改表单id，如果id不一致直接跳转500
        if ($attributes['id'] != $id) {
            return [
                'status' => false,
                'message' => trans('admin/errors.user_error'),
            ];
        }
        try {
            if (isset($attributes['logo'])) {
                $attributes['logo'] = $this->uploadImage($attributes['logo']);
            }
            $isUpdate = $this->project->update($attributes, $id);
            if ($isUpdate) {
                // 更新缓存
                $this->getProjectSetCache();
            }
            return [
                'status' => $isUpdate,
                'message' => $isUpdate ? trans('admin/alert.project.edit_success'):trans('admin/alert.project.edit_error'),
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
    public function destroyProject($id)
    {
        try {
            $isDestroy = $this->project->delete($id);
            if ($isDestroy) {
                // 更新缓存
                $this->getProjectSetCache();
            }
            flash_info($isDestroy,trans('admin/alert.project.destroy_success'),trans('admin/alert.project.destroy_error'));
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
                $this->project->update(['sort' => $v['sort']],$v['id']);
                $bool = true;
            }
            DB::commit();
            if ($bool) {
                // 更新缓存
                $this->getProjectSetCache();
            }
            return [
                'status' => $bool,
                'message' => $bool ? trans('admin/alert.project.order_success'):trans('admin/alert.project.order_error')
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            DB::rollBack();
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }
}