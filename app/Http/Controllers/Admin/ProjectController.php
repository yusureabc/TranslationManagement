<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\ProjectService;
use App\Service\Admin\LanguageService;
use App\Service\Admin\KeyService;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;


class ProjectController extends Controller
{

    protected $projectService;
    protected $languageService;
    protected $keyService;


    /**
     * 构造方法
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     */
    public function __construct( 
        ProjectService $projectService, 
        LanguageService $languageService, 
        KeyService $keyService 
    )
    {
        $this->projectService  = $projectService;
        $this->languageService = $languageService;
        $this->keyService = $keyService;
    }

    /**
     * 项目列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function index()
    {
        return view( 'admin.project.list' );
    }

    /**
     * ajax 获取数据
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function ajaxIndex()
    {
        $responseData = $this->projectService->ajaxIndex();
        return response()->json( $responseData );
    }

    /**
     * 创建项目
     */
    public function create()
    {
        return view( 'admin.project.create' );
    }

    /**
     * 存储项目
     */
    public function store( ProjectCreateRequest $request )
    {
        $this->projectService->storeProject( $request->all() );
        return redirect( 'admin/project' );
    }

    /**
     * 查看项目
     * @param $id
     * @return $this
     */
    public function show( $id )
    {
        $project = $this->projectService->findProjectById( $id );
        $languages = $this->languageService->showLanguageList( $id );
        return view('admin.project.show')->with( compact( 'project', 'languages' ) );
    }

    /**
     * 编辑项目
     * @author Yusure  http://yusure.cn
     * @date   2017-11-06
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function edit( $id )
    {
        $project = $this->projectService->findProjectById( $id );
        return view( 'admin.project.edit', compact( 'project' ) );
    }

    /**
     * 更新项目
     * @author Yusure  http://yusure.cn
     * @date   2017-11-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function update( ProjectUpdateRequest $request, $id )
    {
        $project = $this->projectService->updateProject( $request->all(), $id );
        return redirect( 'admin/project' );
    }

    /**
     * 录入翻译 key 源内容
     * @author Yusure  http://yusure.cn
     * @date   2017-11-09
     * @param  [param]
     * @return [type]     [description]
     */
    public function input( $id )
    {
        $keys = $this->keyService->getKeyList( $id );
        $tags = config( 'tag' );

        return view( 'admin.project.input', compact( 'keys', 'id', 'tags' ) );
    }

    /**
     * 保存录入的信息
     */
    public function storeKey( $id )
    {
        $result = $this->keyService->storeKey( $id, request()->all() );
        if ( $result !== false )
        {
            if ( isset( $result->id ) )
            {
                $response = ['status' => 1, 'id' => $result->id];
            }
            else
            {
                $response = ['status' => 1];
            }            
        }
        else
        {
            $response = ['status' => 0];
        }
        return $response;
    }

    /**
     * 删除翻译 key
     */
    public function deleteKey( $id )
    {
        return $this->keyService->deleteKey( $id, request()->input( 'key_id' ) );
    }

    /**
     * 排序 key
     */
    public function sortKey( Request $request )
    {
        $key_id = $request->input( 'key_id' );
        $sort = $request->input( 'sort' );
        return $this->keyService->updateSort( $key_id, $sort );
    }

    /**
     * 修改 tag
     */
    public function tagChange( Request $request )
    {
        $key_id = $request->input( 'key_id' );
        $tag = $request->input( 'tag' );

        return $this->keyService->updateTag( $key_id, $tag );
    }

    /**
     * 销毁项目数据
     * @author Yusure  http://yusure.cn
     * @date   2017-11-06
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function destroy( $id )
    {
        $this->projectService->destroyProject( $id );
        return redirect()->back();
    }

    /**
     * 导入 key + source
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function import( $id )
    {
        $url = config( 'import.key_url' );
        $result = $this->keyService->importSource( $id, $url );
        if ( $result )
        {
            return 'import successful';
        }
        else
        {
            return 'import error';
        }
    }

    /**
     * 导入 key + source
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function importiOS( $id )
    {
        $file_path = "./en_ios.strings";
        $file_arr = file( $file_path );

        $source_data = $keys = [];
        foreach ( $file_arr as $k => $item )
        {
            $item_arr = explode( '=', $item );

            $key = trim( $item_arr[0] );
            $key = trim( $key, '"' );
            if ( in_array( $key, $keys ) )
            {
                continue;
            }
            else
            {
                $keys[] = $key;
            }

            $value = trim( $item_arr[1] );
            $value = trim( $value, ';' );
            $value = trim( $value, '"' );

            $source_data[] = [
                'project_id' => 20,
                'key' => $key,
                'source' => $value
            ];

            /* 查看 key_id */
            $condition = [
                'project_id' => 20,
                'key' => $key,
            ];

            $key_id = DB::table( 'keys' )->where( $condition )->value( 'id' );
            $data = [
                'project_id' => 20,
                'language_id' => 124,
                'key_id' => $key_id,
                'content' => $value
            ];
            // var_dump( $data );die;
            Content::create( $data );
        }

        var_dump( $source_data );die;

        // DB::table( 'keys_copy' )->insert( $source_data );
    }

}