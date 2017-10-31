<?php

namespace App\Http\Controllers\Admin;

use App\Service\Admin\SalesdataChartsService;
use App\Service\Admin\UserService;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SalesdataCreateRequest;
use App\Http\Requests\SalesdataUpdateRequest;
use App\Repositories\Contracts\SalesdataRepository;
use App\Repositories\Validators\SalesdataValidator;
use App\Service\Admin\SalesdataService;
use App\Http\Controllers\Controller;


class SalesdataController extends Controller
{

    /**
     * @var SalesdataRepository
     */
    protected $repository;

    /**
     * @var SalesdataValidator
     */
    protected $validator;

    private $salesdata;

    private $salesdataCharts;

    private $user;

    public function __construct(
        SalesdataRepository $repository,
        SalesdataValidator $validator,
        SalesdataService $salesdata,
        SalesdataChartsService $salesdataCharts,
        UserService $user
    )
    {
        // 自定义权限中间件
        $this->middleware('check.permission:salesdata');
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->salesdata  = $salesdata;
        $this->salesdataCharts  = $salesdataCharts;
        $this->user  = $user;
    }


    /**
     * 销售数据列表
     * @author Sheldon
     * @date   2017-04-19T11:50:59+0800
     * @return [type]                   [description]
     */
    public function index()
    {
        $platforms = $this->user->getMyPlatforms();
        $products = $this->user->getMyProducts();
        return view('admin.salesdata.list')->with(compact(
            'platforms',
                'products'
        ));
    }

    /**
     * 销售数据图表
     * @author Sheldon
     * @date   2017-04-25T11:50:59+0800
     * @return [type]                   [description]
     */
    public function charts()
    {
        $platform_id        = request('platform_id', 0);
        $product_id         = request('product_id', 0);
        $data_time_start    = request('data_time_start', date('Y-m-d', strtotime('-1 month')));
        $data_time_end      = request('data_time_end', date('Y-m-d'));

        $platforms = $this->user->getMyPlatforms();
        $products = $this->user->getMyProducts();


        list(
            $monthSaleAmountLine,   //销售额月走势
            $monthSaleNumLine,      //销量月走势
            $saleAmountLine,        //销售额走势
            $saleNumLine,           //销量走势
            $saleAmountPlatform,    //平台销售额
            $saleNumPlatform,       //平台销量
            $saleAmountProduct,     //产品销售额
            $saleNumProduct,        //产品销量
            $saleAmountPlatformPie, //平台销售额比例
            $saleNumPlatformPie,    //平台销量比例
            $saleAmountProductPie,  //产品销售额比例
            $saleNumProductPie      //产品销量比例
            ) = $this->salesdataCharts->chartsView($platforms, $products);

        $platform_names = json_encode(array_pluck($platforms, 'name'));
        $product_names = json_encode(array_pluck($products, 'name'));

        return view('admin.salesdata.charts')->with(compact(
            'platforms',
                'products',
            'monthSaleAmountLine',      //销售额月走势
            'monthSaleNumLine',         //销量月走势
            'saleAmountLine',           //销售额走势
            'saleNumLine',              //销量走势
            'saleAmountPlatform',       //平台销售额
            'saleNumPlatform',          //平台销量
            'saleAmountProduct',        //产品销售额
            'saleNumProduct',           //产品销量
            'saleAmountPlatformPie',    //平台销售额比例
            'saleNumPlatformPie',       //平台销量比例
            'saleAmountProductPie',     //产品销售额比例
            'saleNumProductPie',        //产品销量比例
            'platform_names',           //平台数据
            'product_names',            //产品数据
            'platform_id',              //平台ID
            'product_id',               //产品ID
            'data_time_start',          //开始日期
            'data_time_end'             //结束日期
        ));
    }


    public function ajaxIndex()
    {
        $responseData = $this->salesdata->ajaxIndex();
        return response()->json($responseData);
    }

    /**
     * 添加销售数据视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @return [type]                       [description]
     */
    public function create()
    {
        $platforms = $this->user->getMyPlatforms();
        $products = $this->user->getMyProducts();
        return view('admin.salesdata.create')->with(compact('platforms','products'));
    }

    /**
     * 添加销售数据
     * @author Sheldon
     * @date   2017-04-19T15:14:56+0800
     * @param  ProductCreateRequest              $request [description]
     * @return [type]                            [description]
     */
    public function store(SalesdataCreateRequest $request)
    {
        $this->salesdata->storeSalesdata($request->all());
        return redirect('admin/salesdata');
    }


    /**
     * 查看销售数据信息
     * @author Sheldon
     * @date   2017-04-19T16:42:06+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function show($id)
    {
        $salesdata = $this->salesdata->findSalesdataById($id);
        return view('admin.salesdata.show')->with(compact('salesdata'));
    }


    /**
     * 修改销售数据视图
     * @author Sheldon
     * @date    2017-04-19T16:41:48+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function edit($id)
    {
        $salesdata = $this->salesdata->findSalesdataById($id);
        $platforms = $this->user->getMyPlatforms();
        $products = $this->user->getMyProducts();
        return view('admin.salesdata.edit')->with(compact('salesdata', 'platforms','products'));
    }


    /**
     * 修改销售数据
     * @author Sheldon
     * @date   2017-04-19T16:10:02+0800
     * @param  ProductUpdateRequest              $request [description]
     * @param  [type]                   $id      [description]
     * @return [type]                            [description]
     */
    public function update(SalesdataUpdateRequest $request, $id)
    {
        $this->salesdata->updateSalesdata($request->all(),$id);
        return redirect('admin/salesdata');
    }


    /**
     * 删除销售数据
     * @author Sheldon
     * @date   2017-04-19T17:20:49+0800
     * @param  [type]                   $id [description]
     * @return [type]                       [description]
     */
    public function destroy($id)
    {
        $this->salesdata->destroySalesdata($id);
        return redirect('admin/salesdata');
    }
}
