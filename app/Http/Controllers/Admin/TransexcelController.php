<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\Admin\BaseService;
use Illuminate\Http\Request;
use Excel;

class TransexcelController extends Controller
{

    private $baseService;

    public function __construct(BaseService $baseService)
    {
        // 自定义权限中间件
        $this->middleware('check.permission:transexcel');
        $this->baseService  = $baseService;
    }


    public function import()
    {
        return view('admin.transexcel.import');
    }


    public function export(Request $request)
    {
        $excelfile = $this->baseService->uploadExcel($request->file ('excelfile'));
        $data = Excel::selectSheetsByIndex(0)->load((storage_path('app/public').'/'. $excelfile), function($reader) {

        })->get();
        $data = $data->toArray();
        $exports = [
            [
                '购货方名称',
                '购货方纳税人识别号',
                '购货方地址',
                '购货方电话',
                '购货方开户银行',
                '购货方银行账号',
                '店铺ID',
                '订单编号',
                '联系人',
                '联系电话',
                '邮箱',
                '配送地址',
                '开票金额',
                '备注',
                '商品分类编码',
                '商品编码',
                '商品名称',
                '规格型号',
                '含税单价',
                '数量',
                '单位',
                '价税合计金额',
                '商品IMEI号',
            ]
        ];

        $orderids = [];
        foreach ($data as $item) {
            if (isset($orderids[$item['订单编号']])) {
                $orderids[$item['订单编号']] += $item['总价'];
            } else {
                $orderids[$item['订单编号']] = $item['总价'];
            }
        }

        foreach ($data as $item) {
            $tmp = [
                '个人',
                '',
                '',
                $item['电话'],
                '',
                '',
                '',
                $item['订单编号'],
                $item['收货人'],
                $item['电话'],
                '',
                '',
                floatval ($orderids[$item['订单编号']]),
                '',
                '',
                $item['供应商sku'],
                $item['商品名称'],
                '',
                $item['单价'],
                $item['数量'],
                '件',
                $item['总价'],
                '',
            ];
            $exports[] = $tmp;

        }

        //dd($exports);
        ob_clean();
        Excel::create(date("Y-m-d") . "_order_import", function($excel) use ($exports){
            $excel->sheet('Sheet1', function($sheet) use ($exports){
                // Sheet manipulation
                //需要注意的地方1
                $sheet->fromArray($exports, null, 'A1', false, false);
            });
        })->export('xlsx');
    }
}
