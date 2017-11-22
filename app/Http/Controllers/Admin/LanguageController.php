<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\LanguageService;
use App\Service\Admin\UserService;


use App\Http\Requests\LanguageCreateRequest; 
use App\Http\Requests\LanguageUpdateRequest;


class LanguageController extends Controller
{

    /**
     * @var languageService
     */
    protected $languageService;

    /**
     * @var LanguageValidator
     */
    protected $validator;

    public function __construct( LanguageService $languageService )
    {
        $this->languageService = $languageService;
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
        $responseData = $this->languageService->ajaxIndex();
        return response()->json( $responseData );
    }

    /**
     * 切换状态
     */
    public function status( $id, $status )
    {
        $this->languageService->changeStatus( $id, $status );
        return redirect()->back();
    }

    /**
     * 邀请翻译者
     * @author Yusure  http://yusure.cn
     * @date   2017-11-10
     * @param  [param]
     * @return [type]     [description]
     */
    public function invite( $id )
    {
        $all_user = $this->languageService->getAllUser();
        $invite_user = $this->languageService->getInviteUser( $id );
        $project_id = $this->languageService->findProjectId( $id );

        return view( 'admin.language.invite', compact( 'all_user', 'invite_user', 'project_id' ) );
    }

    /**
     * 存储邀请的翻译者
     */
    public function storeInviteUser( $id )
    {
        $user_id = request()->input( 'user_id' );
        $result = $this->languageService->storeInviteUser( $id, $user_id );
        flash_info( $result, trans('admin/alert.project.invite_success'), trans('admin/alert.project.invite_error') );

        return redirect()->route( 'language.invite', $id );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  LanguageUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(LanguageUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $language = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Language updated.',
                'data'    => $language->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }

    /**
     * 下载翻译文本
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    public function download( $id )
    {
        return view( 'admin.language.download', compact( 'id' ) );
    }

    /**
     * 输出下载内容
     * @author Yusure  http://yusure.cn
     * @date   2017-11-22
     * @param  [param]
     * @return [type]     [description]
     */
    public function downloadOutput( $id, $method = 'xml' )
    {
        $result = $this->languageService->getTranslateResult( $id, $method );
        switch ( $method )
        {
            case 'xml':
                header('Content-Type: text/xml');
            break;
        }
        die( $result );
    }

}