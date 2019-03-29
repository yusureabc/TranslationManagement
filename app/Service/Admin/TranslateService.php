<?php
namespace App\Service\Admin;

use Illuminate\Support\Facades\Auth;
use App\Repositories\Eloquent\TranslatorRepositoryEloquent;
use App\Repositories\Eloquent\LanguageRepositoryEloquent;
use App\Repositories\Eloquent\KeyRepositoryEloquent;
use App\Repositories\Eloquent\ContentRepositoryEloquent;
use App\Repositories\Eloquent\CommentRepositoryEloquent;

use App\Service\Admin\BaseService;
use Exception;
use DB;
use App\Models\Comment;
use App\Models\Content;

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
        ContentRepositoryEloquent $contentRepository,
        CommentRepositoryEloquent $commentRepository

    )
    {
        $this->translateRepository = $translateRepository;
        $this->languageRepository  = $languageRepository;
        $this->keyRepository       = $keyRepository;
        $this->contentRepository   = $contentRepository;
        $this->commentRepository   = $commentRepository;
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
            /* 适配源语言为英语，例如 Alexa 没有中文 strings */
            if ( $contrast_contents->isEmpty() )
            {
                $contrast_contents = $this->keyRepository->getSourceContents( $project_id );
            }
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
        $contents = $this->contentRepository->getTranslatedContents( $id );
        return replace_array_key( $contents, 'key_id' );
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
        $data['translated'] = strip_tags( htmlspecialchars_decode( $data['translated'] ) );
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
     * 获取翻译内容ID
     */
    public function getContentId( $data )
    {
        $condition = [
            'language_id' => $data['language_id'],
            'key_id'      => $data['key_id'],
        ];

        $content_id = $this->contentRepository->getField( $condition, 'id' );
        return $content_id;
    }

    /**
     * 完成翻译
     */
    public function finshTranslate( $id )
    {
        $this->languageRepository->changeStatus( $id, ['status' => 0] );
        return $this->languageRepository->finshTranslate( $id );
    }

    /**
     * 修改标记
     */
    public function flag( $id, $flag )
    {
        $condition = [ 'id' => $id ];
        $data = [ 'flag' => $flag ];
        return $this->contentRepository->update_data( $condition, $data );
    }

    /**
     * 获取评论列表
     */
    public function getComment( $id )
    {
        $condition = ['content_id' => $id];
        return $this->commentRepository->getList( $condition );
    }

    /**
     * 是否有评论
     */
    public function hasComment( $translated )
    {
        if ( empty( $translated ) )  return [];
        foreach ( $translated as $value )
        {
            $ids[] = $value['id'];
        }
        $result = Comment::whereIn( 'content_id', $ids )->get();

        $has_result = [];
        if ( $result->isNotEmpty() )
        {
            foreach ( $result as $k => $v )
            {
                $has_result[] = $v->content_id;
            }
        }

        return $has_result;
    }

    /**
     * 存储评论
     */
    public function commentStore( $id, $comment )
    {
        $user = Auth::user();
        $data = [
            'content_id' => $id,
            'user_id'    => Auth::id(),
            'username'   => $user->username,
            'comment'    => $comment
        ];
        return $this->commentRepository->create( $data );
    }

    /**
     * getKeySourceContent
     */
    public function getKeySourceContent( $id )
    {
        $resource = [];
        $condition = ['id' => $id];
        $content_info = $this->contentRepository->getInfo( $condition );
        $resource['content'] = $content_info->content;

        $key_info = $this->keyRepository->getInfo( ['id' => $content_info->key_id] );
        $resource['key'] = $key_info->key;

        /* 获取语言code */
        $language_code = $this->languageRepository->findLanguageCode( $content_info->language_id );
        $contrast_code = $this->_contrastLang( $language_code );

        /**
         * 例如：英文翻译需要参照中文（源语言）
         */
        if ( $contrast_code == config( 'sourcelang.base_lang' ) )
        {
            $resource['source'] = $key_info->source;
        }
        else
        {
            /* 用 contrast_code + project_id 查询依赖的 language_id */
            $contrast_language_id = $this->languageRepository->getLanguageID( $content_info->project_id, $contrast_code );
            $condition = [
                'language_id' => $contrast_language_id,
                'key_id' => $content_info->key_id,
            ];
            $resource['source'] = $this->contentRepository->getField( $condition, 'content' );
        }

        return $resource;
    }

    /**
     * 导入译文
     */
    public function importTranslated( $id, $data, $force = 0 )
    {
        $project_id = $this->languageRepository->findProjectId( $id );
        $translated = replace_array_key( $data, 'key' );

        $keys = array_keys( $translated );
        $exist = $this->keyRepository->keyExist( $project_id, $keys )->toArray();
        foreach ( $exist as $item )
        {
            $condition = [
                'project_id'  => $project_id,
                'language_id' => $id,
                'key_id'      => $item['id'],
            ];
            $content = $this->contentRepository->getField( $condition, 'content' );

            /* 如果 $content 有内容 + force 是 false 就不做任何处理 */
            if ( $content && 0 == $force )  continue;

            $translatedContent = $translated[ $item['key'] ]['translated'] ?? '';
            Content::updateOrCreate( $condition, ['content' => $translatedContent] );
        }

        return true;
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

    /**
     * 判断翻译是否完成
     * @author Scott Yu  <yusureyes@gmail.com>  http://yusure.cn
     * @date   2019-02-25
     * @param  [type]     $source     [description]
     * @param  [type]     $translated [description]
     * @return [type]                 [description]
     */
    public function judgeTranslatedFinished( $source, $translated )
    {
        if ( $source->isNotEmpty() )
        {
            foreach ( $source as $k => $item )
            {
                $translatedContent = $translated[$item->key_id]['content'] ?? '';
                if ( ! $translatedContent )  return false;
            }
        }

        return true;
    }

}