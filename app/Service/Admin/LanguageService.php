<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Repositories\Eloquent\UserRepositoryEloquent;
use App\Repositories\Eloquent\TranslatorRepositoryEloquent;

use App\Service\Admin\BaseService;
use Exception;

/**
* Language Service
*/
class LanguageService extends BaseService
{

    protected $project;
    protected $languageRepository;
    protected $userRepository;
    protected $translatorRepository;

    function __construct(
        ProjectRepositoryEloquent $project, 
        LanguageRepositoryEloquent $languageRepository,
        UserRepositoryEloquent $userRepository,
        TranslatorRepositoryEloquent $translatorRepository
    )
    {
        $this->project =  $project;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->translatorRepository = $translatorRepository;
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

        if ( $result['languages'] )
        {
            foreach ( $result['languages'] as $v )
            {
                $v->name = trans( 'languages.'.$v->language );
                $status = $v->status;
                $v->status = $v->status == 0 ? trans( 'admin/action.lock' ) : trans( 'admin/action.open' );
                $v->actionButton = $v->getActionButtonAttribute( true, $status );
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
     * 根据 language_id 查找 project_id
     */
    public function findProjectId( $id )
    {
        return $this->languageRepository->findProjectId( $id );
    }

    /**
     * 改变语言翻译状态
     */
    public function changeStatus( $id, $status )
    {
        $status = $status == 0 ? 1 : 0;
        $data = ['status' => $status];
        return $this->languageRepository->changeStatus( $id, $data );
    }

    /**
     * 获取所有用户
     */
    public function getAllUser()
    {
        return $this->userRepository->getAllUser();
    }

    /**
     * 获取邀请到的翻译者
     */
    public function getInviteUser( $id )
    {
        return $this->translatorRepository->getInviteUser( $id );
    }

    /**
     * 保存 翻译者
     */
    public function storeInviteUser( $id, $user_id )
    {
        /* get project and language info */
        $language = $this->languageRepository->find( $id );
        $project_name = $this->project->getProjectName( $language->project_id );

        /*  TODO 删除没有选中的数据 */
        $this->translatorRepository->deleteOtherUser( $id, $user_id );
        $old_invite = $this->translatorRepository->getInviteUser( $id );

        /* get language info */
        $selected_translator = [];
        foreach ( (array)$user_id as $k => $uid )
        {
            if ( array_search( $uid, $old_invite ) !== false ) continue;

            $selected_translator[] = [
                'project_id'    => $language->project_id, 
                'project_name'  => $project_name, 
                'language_id'   => $id, 
                'language_code' => $language->language,
                'user_id'       => $uid,
            ];
        }

        return $this->translatorRepository->insert( $selected_translator );
    }

}