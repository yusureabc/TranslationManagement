<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Service\Admin\UserService;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    private $user;

    function __construct(UserService $user)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:user');
        $this->user = $user;
    }

    /**
     * 用户列表
     * @author Sheldon
     * @date   2016-11-03T11:50:59+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        return view('admin.user.list');
    }

    public function ajaxIndex()
    {
        $responseData = $this->user->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        list( $permissions, $roles ) = $this->user->createView();
        return view('admin.user.create')->with( compact( 'permissions', 'roles' ) );
    }

    /**
     * 添加用户
     * @author Sheldon
     * @date   2016-11-03T15:14:56+0800
     * @param  UserRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(UserRequest $request)
    {
        $this->user->storeUser($request->all());
        return redirect('admin/user');
    }

    /**
     * 查看用户信息
     */
    public function show( $id )
    {
        $user = $this->user->findUserById( $id );
        return view( 'admin.user.show' )->with( compact( 'user' ) );
    }

    /**
     * 修改用户视图
     */
    public function edit( $id )
    {
        list( $user, $permissions, $roles ) = $this->user->editView($id);

        return view('admin.user.edit')->with( compact( 'user', 'permissions', 'roles' ) );
    }

    /**
     * 修改用户
     */
    public function update( UserRequest $request, $id )
    {
        $this->user->updateUser( $request->all(), $id );
        return redirect( 'admin/user' );
    }

    /**
     * 删除用户
     */
    public function destroy( $id )
    {
        $this->user->destroyUser( $id );
        return redirect('admin/user');
    }

    /**
     * 重置用户密码
     */
    public function resetPassword($id)
    {
        $responseData = $this->user->resetUserPassword($id);
        return response()->json($responseData);
    }
}
