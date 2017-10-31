<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\PlatformRepositoryEloquent;
use App\Repositories\Eloquent\ProductRepositoryEloquent;
use App\Repositories\Eloquent\SalesdataRepositoryEloquent;
use Exception;
use Illuminate\Support\Facades\DB;

/**
* 销售数据图表service
*/
class SalesdataChartsService extends SalesdataService
{
    private $platform_id;

    private $product_id;

    private $data_time_start;

    private $data_time_end;

    protected $platforms;

    protected $products;

    /**
     * 创建销售数据图表数据
     * @author Sheldon
     * @date   2017-04-26T13:29:53+0800
     * @param  [type]                   $platforms [平台数据]
     * @param  [type]                   $products  [产品数据]
     * @return [type]                   [description]
     */
    public function chartsView($platforms, $products)
    {
        $this->platforms = $platforms;

        $this->products = $products;

        // 平台ID
        $this->platform_id = request('platform_id', 0);

        //产品ID
        $this->product_id = request('product_id', 0);

        //开始日期
        $this->data_time_start = request('data_time_start', date('Y-m-d', strtotime('-1 month')));

        //结束日期
        $this->data_time_end = request('data_time_end', date('Y-m-d'));

        return [
            $this->monthSaleAmountLine(),   //销售额月走势
            $this->monthSaleNumLine(),      //销量月走势
            $this->saleAmountLine(),        //销售额走势
            $this->saleNumLine(),           //销量走势
            $this->saleAmountPlatform(),    //平台销售额
            $this->saleNumPlatform(),       //平台销量
            $this->saleAmountProduct(),     //产品销售额
            $this->saleNumProduct(),        //产品销量
            $this->saleAmountPlatformPie(), //平台销售额比例
            $this->saleNumPlatformPie(),    //平台销量比例
            $this->saleAmountProductPie(),  //产品销售额比例
            $this->saleNumProductPie()      //产品销量比例
        ];
    }

    /**
     * 查询销售数据
     * @author Sheldon
     * @date   2017-04-26T11:56:28+0800
     * @param  [type]                   $select  [搜索字段]
     * @param  [type]                   $groupBy  [分组数据]
     * @param  [type]                   $order      [排序数据]
     * @return [type]                           [查询结果对象]
     */
    public function getSalesdataForCharts($select, $groupBy, $order = [])
    {
        $salesdata = $this->salesdata;

        $search = [
            'data_time_start'   => $this->data_time_start,
            'data_time_end'     => $this->data_time_end,
        ];

        $platform_ids = array_pluck($this->platforms, 'id');
        $product_ids = array_pluck($this->products, 'id');
        if (in_array($this->platform_id, $platform_ids)) {
            $search['platform_id'] = $this->platform_id;
        } else {
            $search['platform_ids'] = $platform_ids;
        }

        if (in_array($this->product_id, $product_ids)) {
            $search['product_id'] = $this->product_id;
        } else {
            $search['product_ids'] = $product_ids;
        }

        $order = [
            'name'  => isset($order['name']) ? $order['name'] : 'data_time',
            'dir'   => isset($order['dir']) ? $order['dir'] : 'asc',
        ];

        $salesdatas = $salesdata->getSalesdataGrouply($select, $search, $order, $groupBy);

        return $salesdatas;
    }

    /**
     * 销售额月走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function monthSaleAmountLine ()
    {
        $groupBy = ['month'];
        $order   = [
            'name'  => 'month',
            'dir'   => 'asc',
        ];
        $select = DB::raw("date_format(FROM_UNIXTIME(UNIX_TIMESTAMP(`data_time`)),'%Y-%m') month,sum(amount) as total_amount");
        return $this->getSalesdataForCharts($select, $groupBy, $order);
    }


    /**
     * 销量月走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function  monthSaleNumLine ()
    {
        $groupBy = ['month'];
        $order   = [
            'name'  => 'month',
            'dir'   => 'asc',
        ];
        $select = DB::raw("date_format(FROM_UNIXTIME(UNIX_TIMESTAMP(`data_time`)),'%Y-%m') month,sum(num) as total_num");
        return $this->getSalesdataForCharts($select, $groupBy, $order);
    }

    /**
     * 销售额走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function saleAmountLine ()
    {
        $groupBy = ['data_time'];
        $select = DB::raw('data_time,sum(amount) as total_amount');
        return $this->getSalesdataForCharts($select, $groupBy);
    }


    /**
     * 销量走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function  saleNumLine ()
    {
        $groupBy = ['data_time'];
        $select = DB::raw('data_time,sum(num) as total_num');
        return $this->getSalesdataForCharts($select, $groupBy);
    }

    /**
     * 平台销售额
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function saleAmountPlatform ()
    {
        $groupBy = ['data_time', 'platform_id'];
        $select = DB::raw('data_time,platform_id,sum(amount) as total_amount');
        $saleAmountPlatform = $this->getSalesdataForCharts($select, $groupBy);

        $return = [];

        foreach ($saleAmountPlatform->toArray() as $sk => $sv) {
            $return[$sv['data_time']]['data_time'] = $sv['data_time'];
            $tmpKey = collect($this->platforms)->where('id', $sv['platform_id'])->shift();
            $return[$sv['data_time']][$tmpKey['name']] = number_format($sv['total_amount'], 2, '.', '');
        }
        return array_values($return);
    }

    /**
     * 平台销量
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                    [array]
     */
    private function saleNumPlatform ()
    {
        $groupBy = ['data_time', 'platform_id'];
        $select = DB::raw('data_time,platform_id,sum(num) as total_num');
        $saleNumPlatform = $this->getSalesdataForCharts($select, $groupBy);

        $return = [];

        foreach ($saleNumPlatform->toArray() as $sk => $sv) {
            $return[$sv['data_time']]['data_time'] = $sv['data_time'];
            $tmpKey = collect($this->platforms)->where('id', $sv['platform_id'])->shift();
            $return[$sv['data_time']][$tmpKey['name']] = $sv['total_num'];
        }
        return array_values($return);
    }

    /**
     * 产品销售额
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                       [array]
     */
    private function saleAmountProduct ()
    {
        $groupBy = ['data_time', 'product_id'];
        $select = DB::raw('data_time,product_id,sum(amount) as total_amount');
        $saleAmountProduct = $this->getSalesdataForCharts($select, $groupBy);

        $return = [];

        foreach ($saleAmountProduct->toArray() as $sk => $sv) {
            $return[$sv['data_time']]['data_time'] = $sv['data_time'];
            $tmpKey = collect($this->products)->where('id', $sv['product_id'])->shift();
            $return[$sv['data_time']][$tmpKey['name']] = number_format($sv['total_amount'], 2, '.', '');
        }
        return array_values($return);
    }


    /**
     * 产品销量
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                       [array]
     */
    private function saleNumProduct ()
    {
        $groupBy = ['data_time', 'product_id'];
        $select = DB::raw('data_time,product_id,sum(num) as total_num');
        $saleNumProduct = $this->getSalesdataForCharts($select, $groupBy);

        $return = [];

        foreach ($saleNumProduct->toArray() as $sk => $sv) {
            $return[$sv['data_time']]['data_time'] = $sv['data_time'];
            $tmpKey = collect($this->products)->where('id', $sv['product_id'])->shift();
            $return[$sv['data_time']][$tmpKey['name']] = $sv['total_num'];
        }
        return array_values($return);
    }


    /**
     * 平台销售额比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                               [array]
     */
    private function saleAmountPlatformPie ()
    {
        $groupBy = ['platform_id'];
        $select = DB::raw('platform_id,sum(amount) as total_amount');
        $order = [
            'name' => 'platform_id',
            'dir'   => 'asc'
        ];
        $saleAmountPlatformPie = $this->getSalesdataForCharts($select, $groupBy, $order);

        $total = collect($saleAmountPlatformPie->toArray())->pluck('total_amount')->sum();
        $return = [];

        foreach ($saleAmountPlatformPie->toArray() as $sk => $sv) {
            $tmpKey = collect($this->platforms)->where('id', $sv['platform_id'])->shift();
            $return[$sv['platform_id']]['label'] = $tmpKey['name'];
            $return[$sv['platform_id']]['value'] = number_format($sv['total_amount'], 2, '.', '');
        }

        return array_values($return);
    }

    /**
     * 平台销量比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                               [array]
     */
    private function saleNumPlatformPie ()
    {
        $groupBy = ['platform_id'];
        $select = DB::raw('platform_id,sum(num) as total_num');
        $order = [
            'name' => 'platform_id',
            'dir'   => 'asc'
        ];
        $saleNumPlatformPie = $this->getSalesdataForCharts($select, $groupBy, $order);

        $total = collect($saleNumPlatformPie->toArray())->pluck('total_num')->sum();
        $return = [];

        foreach ($saleNumPlatformPie->toArray() as $sk => $sv) {
            $tmpKey = collect($this->platforms)->where('id', $sv['platform_id'])->shift();
            $return[$sv['platform_id']]['label'] = $tmpKey['name'];
            $return[$sv['platform_id']]['value'] = $sv['total_num'];
        }

        return array_values($return);
    }

    /**
     * 产品销售额比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                               [array]
     */
    private function saleAmountProductPie ()
    {
        $groupBy = ['product_id'];
        $select = DB::raw('product_id,sum(amount) as total_amount');
        $order = [
            'name' => 'product_id',
            'dir'   => 'asc'
        ];
        $saleAmountProductPie = $this->getSalesdataForCharts($select, $groupBy, $order);

        $total = collect($saleAmountProductPie->toArray())->pluck('total_amount')->sum();
        $return = [];

        foreach ($saleAmountProductPie->toArray() as $sk => $sv) {
            $tmpKey = collect($this->products)->where('id', $sv['product_id'])->shift();
            $return[$sv['product_id']]['label'] = $tmpKey['name'];
            $return[$sv['product_id']]['value'] = number_format($sv['total_amount'], 2, '.', '');
        }

        return array_values($return);
    }

    /**
     * 产品销量比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @return [type]                                               [array]
     */
    private function saleNumProductPie ()
    {
        $groupBy = ['product_id'];
        $select = DB::raw('product_id,sum(num) as total_num');
        $order = [
            'name' => 'product_id',
            'dir'   => 'asc'
        ];
        $saleNumProductPie = $this->getSalesdataForCharts($select, $groupBy, $order);

        $total = collect($saleNumProductPie->toArray())->pluck('total_num')->sum();
        $return = [];

        foreach ($saleNumProductPie->toArray() as $sk => $sv) {
            $tmpKey = collect($this->products)->where('id', $sv['product_id'])->shift();
            $return[$sv['product_id']]['label'] = $tmpKey['name'];
            $return[$sv['product_id']]['value'] = $sv['total_num'];
        }

        return array_values($return);
    }
}