<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Repositories\Eloquent\TranslatorRepositoryEloquent;
use App\Repositories\Eloquent\InviteRepositoryEloquent;

use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
* Project Service
*/
class ProjectService extends BaseService
{

    protected $project;
    protected $languageRepository;
    protected $translatorRepository;
    protected $inviteRepository;

    function __construct(
        ProjectRepositoryEloquent $project, 
        LanguageRepositoryEloquent $languageRepository,
        TranslatorRepositoryEloquent $translatorRepository,
        InviteRepositoryEloquent $inviteRepository
    )
    {
        $this->project =  $project;
        $this->languageRepository = $languageRepository;
        $this->translatorRepository = $translatorRepository;
        $this->inviteRepository = $inviteRepository;
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
     * 添加项目
     */
    public function storeProject($attributes)
    {
        try {
            $languages = $attributes['languages'];
            /* 将 languages 转为字符串 */
            $attributes['languages'] = implode( ',', $attributes['languages'] );
            $attributes['user_id']   = getUser()->id;
            $attributes['username']  = getUser()->username;
            $result = $this->project->create( $attributes );
            if ( $result->id )
            {
                $languages_data = $this->_buildLanguagesData( $result->id, $languages );
                $this->languageRepository->insert( $languages_data );
                /* 自动邀请 */
                $this->_auto_invite( $languages, $result->id, $attributes['name'] );
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
     * 生成待翻译语言多条数据
     */
    private function _buildLanguagesData( $id, $languages )
    {
        $languages_data = [];

        foreach ( (array)$languages as $k => $v )
        {
            $languages_data[$k]['project_id'] = $id;
            $languages_data[$k]['language']   = $v;
            $languages_data[$k]['status']     = 1;
        }

        return $languages_data;
    }

    /**
     * 自动邀请
     */
    private function _auto_invite( $languages, $project_id, $project_name )
    {
        foreach ( (array)$languages as $language_code )
        {
            /* 先用 language_code 去 invite 查找 user_id */
            $condition = [ 'language_code' => $language_code ];
            $user_id = $this->inviteRepository->getField( $condition, 'user_id' );
            if ( ! $user_id ) continue;

            /* 如果有 user_id 用 project_id + language_code 去 `languages` 表 反查 id */
            $language_id = $this->languageRepository->getLanguageID( $project_id, $language_code );

            /* 多个user_id  写入 translation 表 */
            $user_id = explode( ',', $user_id );
            foreach ( $user_id as $k => $id )
            {
                $translator_data[] = [
                    'project_id'    => $project_id,
                    'project_name'  => $project_name,
                    'language_id'   => $language_id,
                    'language_code' => $language_code,
                    'user_id'       => $id
                ];
            }
            $this->translatorRepository->insert( $translator_data );
        }
    }

    /**
     * 根据ID查找数据
     */
    public function findProjectById( $id )
    {
        $project = $this->project->find( $id );
        /* 查找 language 数据 */
        if ($project){
            return $project;
        }
        // TODO替换正查找不到数据错误页面
        abort(404);
    }

    /**
     * 修改数据
     */
    public function updateProject( $attributes, $id )
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
            DB::beginTransaction();
            /* 关联子表操作：存储多语言 */
            $this->_storeLanguage( $attributes['languages'], $id );
            $attributes['languages'] = implode( ',', $attributes['languages'] );
            $attributes['user_id']   = getUser()->id;
            $attributes['username']  = getUser()->username;
            $isUpdate = $this->project->update( $attributes, $id );
            $this->translatorRepository->updateProjectName( $attributes['name'], $id );
            DB::commit();

            return [
                'status' => $isUpdate,
                'message' => $isUpdate ? trans('admin/alert.project.edit_success'):trans('admin/alert.project.edit_error'),
            ];
        } catch (Exception $e) {
            var_dump( $e->getMessage() );die;
            DB::rollBack();
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 存储 多语言
     * @author Yusure  http://yusure.cn
     * @date   2017-11-08
     * @param  [param]
     * @return [type]     [description]
     */
    private function _storeLanguage( $languages, $id )
    {
        /* 查找旧的 language 用来作比对 */
        $old_languages = $this->languageRepository->getOldLanguage( $id );

        /* 删除其他未选中的语言 */
        $this->languageRepository->deleteOtherLanguage( $languages, $id );

        /* foreach 新的 languages 判断是否存在，不存在就写入 */
        foreach ( $languages as $k => $language )
        {
            $result = array_search( $language, $old_languages );
            if ( false === $result )
            {
                /* 写入 languages */
                $data = [
                    'project_id' => $id,
                    'language'   => $language,
                    'status'     => 1,
                ];
                $this->languageRepository->insert( $data );
            }
        }
    }

    /**
     * 删除
     */
    public function destroyProject( $id )
    {
        try {
            $isDestroy = $this->project->delete( $id );
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