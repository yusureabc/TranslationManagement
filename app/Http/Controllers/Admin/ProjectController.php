<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\StoreKeyRequest;
use App\Http\Controllers\Controller;
use App\Service\Admin\ProjectService;
use App\Service\Admin\LanguageService;
use App\Service\Admin\KeyService;
use App\Service\Admin\ApplicationService;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;
use Storage;
use Excel;

/**
 * 项目 Controller
 */
class ProjectController extends Controller
{
    protected $projectService;
    protected $languageService;
    protected $keyService;
    protected $applicationService;

    /**
     * 构造方法
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     */
    public function __construct( 
        ProjectService $projectService, 
        LanguageService $languageService, 
        KeyService $keyService,
        ApplicationService $applicationService
    )
    {
        $this->projectService  = $projectService;
        $this->languageService = $languageService;
        $this->keyService = $keyService;
        $this->applicationService = $applicationService;
    }

    /**
     * 项目列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function index( Request $request )
    {
        $app_id = $request->input( 'app_id' );
        return view( 'admin.project.list', compact( 'app_id' ) );
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
        $apps = $this->applicationService->getApps();

        return view( 'admin.project.create', compact( 'apps' ) );
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
        $apps = $this->applicationService->getApps();

        return view( 'admin.project.edit', compact( 'project', 'apps' ) );
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

        return redirect( $request->input( 'callback' ) );
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
        $lengthType = [
            0 => 'NoLimit',
            1 => 'Short',
            2 => 'Medium',
            3 => 'Long'
        ];

        return view( 'admin.project.input', compact( 'keys', 'id', 'tags', 'lengthType' ) );
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
    public function sortKey( StoreKeyRequest $request )
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
     * LengthType 修改
     * @author Scott Yu  <yusureyes@gmail.com>  http://yusure.cn
     * @date   2019-02-25
     * @param  Request    $request [description]
     * @return [type]              [description]
     */
    public function lengthChange( Request $request )
    {
        $key_id = $request->input( 'key_id' );
        $length = $request->input( 'length' );

        return $this->keyService->updateLength( $key_id, $length );
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
        $url = config( 'import.key_url' );
        $result = $this->keyService->importSourceOniOS( $id, $url );
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
     * Excel 导入 key + 源语言
     */
    public function importExcel( Request $request, $id )
    {
        if ( $request->isMethod( 'post' ) )
        {
            /* 上传 Excel */
            $excel = $request->file( 'excel' );
            if ( ! $excel )
            {
                return back()->withErrors( ['no_file' => 'Please choose excel file'] );
                /* Please choose excel file */
            }
            $filePath = $this->projectService->storeExcel( $excel );            

            /* 解析数据 */
            Excel::load( $filePath, function( $reader ) use ( $id ) {
                $data = $reader->all()->toArray();
                $data = reset( $data );
                /* 写入数据库 */
                foreach ( $data as $item )
                {
                    $this->keyService->storeKey( $id, $item );                    
                }
            });

            return redirect( route( 'key.input', ['id' => $id] ) );
        }
        else
        {
            return view( 'admin.project.import_excel', compact( 'id' ) );
        }
    }

}