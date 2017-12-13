<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\TranslatorRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Repositories\Eloquent\KeyRepositoryEloquent;
use App\Repositories\Eloquent\ContentRepositoryEloquent;

use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
* Translate Service
*/
class TranslateService extends BaseService
{

    protected $translateRepository;
    protected $languageRepository;
    protected $keyRepository;
    protected $contentRepository;

    public function __construct( 
        TranslatorRepositoryEloquent $translateRepository,
        LanguageRepositoryEloquent $languageRepository,
        KeyRepositoryEloquent $keyRepository,
        ContentRepositoryEloquent $contentRepository
    )
    {
        $this->translateRepository = $translateRepository;
        $this->languageRepository  = $languageRepository;
        $this->keyRepository       = $keyRepository;
        $this->contentRepository   = $contentRepository;
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
        $search['user_id'] = getUser()->id;

        $result = $this->translateRepository->getTranslateList($start,$length,$search,$order);

        $translators = [];

        if ( $result['translators'] )
        {
            foreach ( $result['translators'] as $v )
            {
                $v->status = $v->language->status == 0 ? trans( 'admin/action.lock' ) : trans( 'admin/action.open' );
                $v->language_name = trans( 'languages.'.$v->language_code );
                $v->actionButton = $v->getActionButtonAttribute();
                $translators[] = $v;
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['count'],
            'recordsFiltered' => $result['count'],
            'data' => $translators,
        ];
    }

    /**
     * 获取待翻译列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-13
     * @param  [param]
     * @param  [type]     $user_id [description]
     * @return [type]              [description]
     */
    public function getTranslateList( $user_id )
    {
        return $this->translateRepository->getTranslateList( $user_id );
    }

    /**
     * 获取翻译源语言
     * @author Yusure  http://yusure.cn
     * @date   2017-11-13
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function getTranslateSource( $id )
    {
        $project_id = $this->languageRepository->findProjectId( $id );
        $language_code = $this->languageRepository->findLanguageCode( $id );
        /* 获取对照语言 */
        $contrast_code = $this->_contrastLang( $language_code );

        /**
         * 例如：英文翻译需要参照中文（源语言）
         */
        if ( $contrast_code == config( 'sourcelang.base_lang' ) )
        {
            /* 查找 keys 表 */
            $contrast_contents = $this->keyRepository->getSourceContents( $project_id );
        }
        else
        {
            $contrast_language_id = $this->languageRepository->getLanguageID( $project_id, $contrast_code );
            /* 查找 contents 表 */
            $contrast_contents = $this->contentRepository->getSourceContents( $contrast_language_id );
        }

        return $contrast_contents;
    }

    /**
     * 获取 翻译者 的译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    public function getTranslatedContents( $id )
    {
        return $this->contentRepository->getTranslatedContents( $id );
    }

    /**
     * 获取对照语言
     */
    private function _contrastLang( $code )
    {
        return config( 'sourcelang.' . $code );
    }

    /**
     * 存储译文
     */
    public function storeTranslated( $data )
    {
        $project_id = $this->languageRepository->findProjectId( $data['language_id'] );
        /* 检查 contents 表是否存在 存在就更新 不存在就写入 */
        $exist = $this->contentRepository->translated_exist( $data['language_id'], $data['key_id'] );
        if ( $exist )
        {
            /* update */
            $result = $this->contentRepository->update_content( $data['language_id'], $data['key_id'], $data['translated'] );
        }
        else
        {
            /* insert */
            $create_data = [
                'project_id'  => $project_id,
                'language_id' => $data['language_id'],
                'key_id'      => $data['key_id'],
                'content'     => $data['translated']
            ];

            $result = $this->contentRepository->create( $create_data );
        }

        return $result;
    }

    /**
     * 完成翻译
     */
    public function finshTranslate( $id )
    {
        return $this->languageRepository->finshTranslate( $id );
    }

    /**
     * 导入译文
     */
    public function importTranslated( $id, $url )
    {
        $project_id = $this->languageRepository->findProjectId( $id );
        $xml_res = xmlToArray( $url );

        $keys = array_keys( $xml_res );
        $exist = $this->keyRepository->keyExist( $project_id, $keys )->toArray();
        $all_translated = [];
        foreach ( $exist as $item )
        {
            $all_translated[] = [
                'project_id'  => $project_id,
                'language_id' => $id,
                'key_id'      => $item['id'],
                'content'     => $xml_res[ $item['key'] ]
            ];
        }

        $key_ids = array_column( $all_translated, 'key_id' );
        $exist_content = $this->contentRepository->contentExist( $id, $key_ids );
        /* 处理重复译文 */
        $all_translated = $this->_handle_exist_content( $all_translated, $exist_content, $key_ids );

        return $this->contentRepository->batchInsertContent( $all_translated );
    }

    /**
     * 处理已存在的译文内容
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @param  [type]     $exist_content [description]
     * @param  [type]     $key_ids       [description]
     * @return [type]                    [description]
     */
    private function _handle_exist_content( $all_translated, $exist_content, $key_ids )
    {
        if ( $exist_content->isNotEmpty() )
        {
            foreach ( $exist_content as $content )
            {
                $index = array_search( $content['key_id'], $key_ids );
                if ( $index !== false )
                {
                    unset( $all_translated[ $index ] );
                }
            }
        }

        return $all_translated;
    }

}