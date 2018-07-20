<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\ApplicationService;
use App\Service\Admin\ProjectService;

/**
 * 应用 Controller
 */
class ApplicationController extends Controller
{

    public function __construct(
        ApplicationService $applicationService,
        ProjectService $projectService
    )
    {
        $this->applicationService = $applicationService;
        $this->projectService = $projectService;
    }

    public function index()
    {
        return view( 'admin.application.index' );
    }

    /**
     * 查看应用
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-17
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function show( $id )
    {
        $application = $this->applicationService->findApplicationById( $id );
        $sub_project = $this->projectService->findSubProject( $id );

        return view( 'admin.application.show' )->with( compact( 'application', 'sub_project' ) );
    }

    public function create()
    {
        return view( 'admin.application.create' );
    }

    public function store( Request $request )
    {
        $this->applicationService->storeApplication( $request->all() );
        return redirect( 'admin/application' );
    }

    public function edit( $id )
    {
        $application = $this->applicationService->findApplicationById( $id );
        return view( 'admin.application.edit', compact( 'application' ) );
    }

    public function update( Request $request, $id )
    {
        $application = $this->applicationService->updateApplication( $request->all(), $id );
        return redirect( 'admin/application' );
    }

    public function destroy( $id )
    {
        $this->applicationService->destroy( $id );
        return redirect()->back();
    }

    public function download( $id )
    {
        return view( 'admin.application.download', compact( 'id' ) );
    }

    /**
     * 下载译文 zip 包
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-20
     * @param  [param]
     * @param  [type]     $id     [description]
     * @param  [type]     $method [description]
     * @return [type]             [description]
     */
    public function downloadFile( $id, $method )
    {
        $zip_filename = $this->applicationService->downloadFile( $id, $method );
        return response()->download( $zip_filename );
    }

    public function ajaxIndex()
    {
        $responseData = $this->applicationService->ajaxIndex();
        return response()->json( $responseData );
    }
}