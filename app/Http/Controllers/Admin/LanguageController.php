<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\LanguageService;
use App\Service\Admin\UserService;
use App\Service\Admin\TranslateService;


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

    public function __construct( LanguageService $languageService, TranslateService $translateService )
    {
        $this->languageService = $languageService;
        $this->translateService = $translateService;
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
     * 查看翻译
     */
    public function show( $id )
    {
        $source = $this->translateService->getTranslateSource( $id );
        $translated = $this->translateService->getTranslatedContents( $id );
        $has_comment = $this->translateService->hasComment( $translated );

        return view( 'admin.language.show', compact( 'id', 'source', 'translated', 'has_comment' ) );
    }

    /**
     * 修改译文
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-16
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function edit( $id )
    {
        $source = $this->translateService->getTranslateSource( $id );
        $translated = $this->translateService->getTranslatedContents( $id );
        $has_comment = $this->translateService->hasComment( $translated );

        return view( 'admin.language.edit', compact( 'id', 'source', 'translated', 'has_comment' ) );
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
     * 发送通知
     * @author Yusure  http://yusure.cn
     * @date   2017-12-27
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function sendEmail( $id )
    {
        /* 根据 language_id 查找 user_id 关联 email */
        $this->languageService->sendEmail( $id );
        return ['status' => 1, 'msg' => 'success'];
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

    /**
     * 导出 JS 文本
     * @author Sure Yu  http://yusure.cn
     * @date   2018-09-07
     * @param  [param]
     * @param  Request    $request [description]
     * @param  [type]     $id      [description]
     * @return [type]              [description]
     */
    public function exportJs( Request $request, $id )
    {
        $translation = $this->languageService->getTranslateData( $id, 'common' );

        $titles = '';
        $contents = '';
        $js_code = '';
        foreach ( $translation as $k => $content )
        {
            if ( ($k % 2) == 0 )
            {
                /* Question */
                $titles .= ' "' . $content->content . '", ';
            }
            else
            {
                /* Answer */
                $contents .= ' "' . $content->content . '", ';
            }
        }

        $js_code .= 'var titles = new Array(' . $titles . ')' . '<br>';
        $js_code .= 'var contents = new Array(' . $contents . ')';

        return $js_code;
    }

}