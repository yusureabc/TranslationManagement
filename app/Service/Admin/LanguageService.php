<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Repositories\Eloquent\UserRepositoryEloquent;
use App\Repositories\Eloquent\TranslatorRepositoryEloquent;
use App\Repositories\Eloquent\KeyRepositoryEloquent;

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
    protected $keyRepository;

    function __construct(
        ProjectRepositoryEloquent $project, 
        LanguageRepositoryEloquent $languageRepository,
        UserRepositoryEloquent $userRepository,
        TranslatorRepositoryEloquent $translatorRepository,
        KeyRepositoryEloquent $keyRepository
    )
    {
        $this->project =  $project;
        $this->languageRepository = $languageRepository;
        $this->userRepository = $userRepository;
        $this->translatorRepository = $translatorRepository;
        $this->keyRepository = $keyRepository;
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

        /* 删除没有选中的数据 */
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

    /**
     * 获取翻译结果
     */
    public function getTranslateResult( $id, $method )
    {
        $project_id = $this->languageRepository->findProjectId( $id );

        /* 获取 language_code  源语言 和 译文 不同查询 */
        $language_code = $this->languageRepository->getLanguageCode( $id );
        if ( $language_code == config( 'sourcelang.base_lang' ) )
        {
            $result = $this->keyRepository->getBaseList( $project_id, $id );
        }
        else
        {
            $result = $this->keyRepository->getTranslatedList( $project_id, $id );
        }
        /* 记录下载时间 */
        $this->languageRepository->downloadTranslate( $id );
        /* 根据要输出的格式用不同的方法处理 */
        switch ( $method )
        {
            case 'xml':
                return $this->_output_xml_result( $result );
            break;

            case 'iOS_strings':
                return $this->_output_iOS_strings_result( $result );
            break;

            case 'iOS_js':
                return $this->_output_iOS_js_result( $result );
            break;
        }
        
    }

    /**
     * 输出 XML 结果
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    private function _output_xml_result( $result )
    {
$string = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<resources>
</resources>
XML;
        $xml = simplexml_load_string($string);
        foreach ( $result as $k => $item )
        {
            $content = $item->content;
            $string = $xml->addChild( 'string', $content );
            $string->addAttribute( 'name', $item->key );
        }

        return $xml->asXML();
    }

    /**
     * 输出 iOS 的语言文件
     * @author Yusure  http://yusure.cn
     * @date   2017-11-22
     * @param  [param]
     * @param  [type]     $result [description]
     * @return [type]             [description]
     */
    private function _output_iOS_strings_result( $result )
    {
        $output = '';
        foreach ( $result as $k => $item )
        {
            $output .= '"' . $item->key . '" = "' . $item->content . '";<br>';
        }
        return $output;
    }

    /**
     * 输出 iOS 插件 js 的语言文件
     * @author Yusure  http://yusure.cn
     * @date   2017-11-22
     * @param  [param]
     * @param  [type]     $result [description]
     * @return [type]             [description]
     */
    private function _output_iOS_js_result( $result )
    {
        $output = '';
        foreach ( $result as $k => $item )
        {
            $output .= $item->key . ': "' . $item->content . '",<br>';
        }
        return $output;
    }

}