<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PlatformCreateRequest;
use App\Http\Requests\PlatformUpdateRequest;
use App\Repositories\Contracts\PlatformRepository;
use App\Repositories\Validators\PlatformValidator;
use App\Service\Admin\PlatformService;

class PlatformController extends Controller
{

    /**
     * @var PlatformRepository
     */
    protected $repository;

    /**
     * @var PlatformValidator
     */
    protected $validator;

    private $platform;

    public function __construct(PlatformRepository $repository, PlatformValidator $validator, PlatformService $platform)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:platform');
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->platform  = $platform;
    }


    /**
     * 平台列表
     * @author Sheldon
     * @date   2017-04-19T11:50:59+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        return view('admin.platform.list');
    }


    public function ajaxIndex()
    {
        $responseData = $this->platform->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * 添加平台视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @return [type]                       [description]
     */
    public function create()
    {
        return view('admin.platform.create');
    }

    /**
     * 添加平台
     * @author Sheldon
     * @date   2017-04-19T15:14:56+0800
     * @param  PlatformCreateRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(PlatformCreateRequest $request)
    {
        $this->platform->storePlatform($request->all());
        return redirect('admin/platform');
    }


    /**
     * 查看平台信息
     * @author Sheldon
     * @date   2017-04-19T16:42:06+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function show($id)
    {
        $platform = $this->platform->findPlatformById($id);
        return view('admin.platform.show')->with(compact('platform'));
    }



    /**
     * 修改平台视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $platform = $this->platform->findPlatformById($id);
        return view('admin.platform.edit')->with(compact('platform'));
    }


    /**
     * 修改平台
     * @author Sheldon
     * @date   2017-04-19T16:10:02+0800
     * @param  PlatformUpdateRequest              $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(PlatformUpdateRequest $request, $id)
    {
        $this->platform->updatePlatform($request->all(),$id);
        return redirect('admin/platform');
    }


    /**
     * 删除平台
     * @author Sheldon
     * @date   2017-04-19T17:20:49+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $this->platform->destroyPlatform($id);
        return redirect('admin/platform');
    }

}
