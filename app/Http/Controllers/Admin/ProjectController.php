<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\ProjectService;
use App\Service\Admin\languageService;
use App\Http\Requests\ProjectCreateRequest;
use App\Http\Requests\ProjectUpdateRequest;


class ProjectController extends Controller
{

    protected $projectService;

    /**
     * 构造方法
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     */
    public function __construct( ProjectService $projectService, languageService $languageService )
    {
        $this->projectService  = $projectService;
        $this->languageService = $languageService;
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
        return view('admin.project.show')->with( compact( 'project' ) );
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
     * 销毁项目数据
     * @author Yusure  http://yusure.cn
     * @date   2017-11-06
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function destroy( $id )
    {

    }

}