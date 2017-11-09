<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;

/**
* Language Service
*/
class LanguageService extends BaseService
{

    protected $project;
    protected $languageRepository;

    function __construct(ProjectRepositoryEloquent $project, LanguageRepositoryEloquent $languageRepository)
    {
        $this->project =  $project;
        $this->languageRepository = $languageRepository;
    }
    /**
     * datatables获取数据
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

        $search['project_id'] = request( 'project_id', 0 );

        $result = $this->languageRepository->getLanguageList($start,$length,$search,$order);

        $languages = [];

        if ($result['languages']) {
            foreach ($result['languages'] as $v) {
                $v->name = trans( 'languages.'.$v->language );
                $v->actionButton = $v->getActionButtonAttribute();
                $languages[] = $v;
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['count'],
            'recordsFiltered' => $result['count'],
            'data' => $languages,
        ];
    }

    /**
     * 查看语言列表
     */
    public function showLanguageList( $project_id )
    {
        return $this->languageRepository->showLanguageList( $project_id );
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