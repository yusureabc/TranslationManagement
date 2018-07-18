<?php

namespace App\Service\Admin;

use App\Repositories\Eloquent\ApplicationRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
 * ApplicationService Service
 */
class ApplicationService extends BaseService
{

    protected $applicationRepository;

    function __construct(
        ApplicationRepositoryEloquent $applicationRepository
    )
    {
        $this->applicationRepository = $applicationRepository;
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

        $result = $this->applicationRepository->getApplicationList($start,$length,$search,$order);

        $apps = [];

        if ( $result['apps'] ) {
            foreach ( $result['apps'] as $v ) {
                $v->actionButton = $v->getActionButtonAttribute( false );
                $apps[] = $v;
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['count'],
            'recordsFiltered' => $result['count'],
            'data' => $apps,
        ];
    }

    /**
     * 添加应用
     */
    public function storeApplication( $attributes )
    {
        try {
            $result = $this->applicationRepository->create( $attributes );
            flash_info( $result, trans('admin/alert.common.create_success'), trans('admin/alert.common.create_error') );

            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.common.create_success'):trans('admin/alert.common.create_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 获取所有应用
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-17
     * @param  [param]
     * @return [type]     [description]
     */
    public function getApps()
    {
        return $this->applicationRepository->get();
    }

    /**
     * 根据ID查找数据
     */
    public function findApplicationById( $id )
    {
        $application = $this->applicationRepository->find( $id );
        /* 查找 language 数据 */
        if ( $application )
        {
            return $application;
        }

        abort(404);
    }

    /**
     * 修改数据
     */
    public function updateApplication( $attributes, $id )
    {
        // 防止用户恶意修改表单id，如果id不一致直接跳转500
        if ( $attributes['id'] != $id )
        {
            return [
                'status' => false,
                'message' => trans('admin/errors.user_error'),
            ];
        }
        try {
            $result = $this->applicationRepository->update( $attributes, $id );

            flash_info( $result, trans('admin/alert.common.edit_success'), trans('admin/alert.common.edit_error') );
            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.common.edit_success') : trans('admin/alert.common.edit_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 删除
     */
    public function destroy( $id )
    {
        try {
            $isDestroy = $this->applicationRepository->delete( $id );
            flash_info($isDestroy,trans('admin/alert.common.destroy_success'),trans('admin/alert.common.destroy_error'));
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