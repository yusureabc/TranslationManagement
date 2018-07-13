<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

use App\Service\Admin\TranslateService;

use App\Http\Requests\TranslateCreateRequest;
use App\Http\Requests\TranslateUpdateRequest;

/**
 * 开始翻译  控制器
 */
class TranslateController extends Controller
{

    protected $translateService;

    /**
     * 构造方法
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     */
    public function __construct( TranslateService $translateService )
    {
        $this->translateService = $translateService;
    }

    /**
     * 待翻译列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function index()
    {
        return view( 'admin.translate.list' );
    }

    /**
     * ajax 获取数据
     */
    public function ajaxIndex()
    {
        $responseData = $this->translateService->ajaxIndex();
        return response()->json( $responseData );
    }

    /**
     * 开始翻译
     */
    public function start( $id )
    {
        $source = $this->translateService->getTranslateSource( $id );
        $translated = $this->translateService->getTranslatedContents( $id );
        $has_comment = $this->translateService->hasComment( $translated );

        return view( 'admin.translate.start', compact( 'id', 'source', 'translated', 'has_comment' ) );
    }

    /**
     * 存储译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    public function store()
    {
        $result = $this->translateService->storeTranslated( request()->all() );
        if ( $result !== false )
        {
            $content_id = $this->translateService->getContentId( request()->all() );
            $response = ['status' => 1, 'content_id' => $content_id];
        }
        else
        {
            $response = ['status' => 0];   
        }

        return $response;
    }

    /**
     * 完成翻译
     */
    public function finish( TranslateUpdateRequest $request, $id )
    {
        $result = $this->translateService->finshTranslate( $id );
        return redirect( 'admin/translate' );
    }

    /**
     * 翻译内容评论查看
     */
    public function comment( $id )
    {
        /* TODO: 显示当前的 key source content */
        $resource = $this->translateService->getKeySourceContent( $id );


        /* 根据 content_id 查询 comments 数据 */
        $comments = $this->translateService->getComment( $id );
        return view( 'admin.translate.comment', compact( 'comments', 'resource' ) );
    }

    /**
     * 保存评论内容
     */
    public function commentStore( Request $request, $id )
    {
        $comment = $request->input( 'comment' );
        $this->translateService->commentStore( $id, $comment );

        return redirect()->back();
    }

    /**
     * 标记译文
     */
    public function flag( $id, $flag )
    {
        $result = $this->translateService->flag( $id, $flag );
        return $result;
    }

    /**
     * 导入译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @return [type]     [description]
     */
    public function import( $id )
    {
        $url = config( 'import.translated_url' );
        $result = $this->translateService->importTranslated( $id, $url );
        if ( $result )
        {
            return 'import successful';
        }
        else
        {
            return 'import error';
        }
    }

}