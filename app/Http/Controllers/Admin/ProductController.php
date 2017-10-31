<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\Contracts\ProductRepository;
use App\Repositories\Validators\ProductValidator;
use App\Service\Admin\ProductService;
use App\Http\Controllers\Controller;


class ProductController extends Controller
{

    /**
     * @var ProductRepository
     */
    protected $repository;

    /**
     * @var ProductValidator
     */
    protected $validator;

    private $product;


    public function __construct(ProductRepository $repository, ProductValidator $validator, ProductService $product)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:product');
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->product  = $product;
    }


    /**
     * 产品列表
     * @author Sheldon
     * @date   2017-04-19T11:50:59+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        return view('admin.product.list');
    }


    public function ajaxIndex()
    {
        $responseData = $this->product->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * 添加产品视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @return [type]                       [description]
     */
    public function create()
    {
        return view('admin.product.create');
    }

    /**
     * 添加产品
     * @author Sheldon
     * @date   2017-04-19T15:14:56+0800
     * @param  ProductCreateRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(ProductCreateRequest $request)
    {
        $this->product->storeProduct($request->all());
        return redirect('admin/product');
    }


    /**
     * 查看产品信息
     * @author Sheldon
     * @date   2017-04-19T16:42:06+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function show($id)
    {
        $product = $this->product->findProductById($id);
        return view('admin.product.show')->with(compact('product'));
    }

    /**
     * 修改产品视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $product = $this->product->findProductById($id);
        return view('admin.product.edit')->with(compact('product'));
    }


    /**
     * 修改产品
     * @author Sheldon
     * @date   2017-04-19T16:10:02+0800
     * @param  ProductUpdateRequest              $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(ProductUpdateRequest $request, $id)
    {
        $this->product->updateProduct($request->all(),$id);
        return redirect('admin/product');
    }



    /**
     * 删除产品
     * @author Sheldon
     * @date   2017-04-19T17:20:49+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $this->product->destroyProduct($id);
        return redirect('admin/product');
    }

}
