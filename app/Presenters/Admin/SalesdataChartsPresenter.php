<?php
namespace App\Presenters\Admin;

class SalesdataChartsPresenter
{

    /**
     * 图表Lines颜色
     * @author Sheldon
     * @date   2017-04-26T10:36:36+0800
     * @return [type]                                    [html]
     */
    public function lineColors ()
    {
        return "[
            '#87d6c6',
            '#54cdb4',
            '#1ab394',
            '#dfda9e',
            '#dfb14f',
            '#df8c12',
            '#df857a',
            '#df4d3d',
            '#df1d17',
            '#df8cd1',
            '#db3bdf',
            '#bb25df',
            '#afaddf',
            '#9a6adf',
            '#4d13df',
            '#9ac9df',
            '#3ca0df'
        ]";
    }

    /**
     * 销售额走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountLine  [销售额走势数据]
     * @return [type]                                    [html]
     */
    public function saleAmountLine ($saleAmountLine)
    {
        $saleAmountLine = collect($saleAmountLine->toArray())->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            $item['value'] = number_format ($item['total_amount'], 2, '.', '');
            unset($item['data_time'], $item['total_amount']);
            return $item;
        });

        return $saleAmountLine->toJson();
    }


    /**
     * 销量走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumLine  [销量走势数据]
     * @return [type]                                    [html]
     */
    public function  saleNumLine ($saleNumLine)
    {
        $saleNumLine = collect($saleNumLine->toArray())->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            $item['value'] = $item['total_num'];
            unset($item['data_time'], $item['total_num']);
            return $item;
        });

        return $saleNumLine->toJson();
    }

    /**
     * 销售额月走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountLine  [销售额走势数据]
     * @return [type]                                    [html]
     */
    public function monthSaleAmountLine ($monthSaleAmountLine)
    {
        $monthSaleAmountLine = collect($monthSaleAmountLine->toArray())->map(function ($item, $key) {
            //$item['day'] = date('Y-m-d', strtotime($item['data_time']));
            $item['value'] = number_format ($item['total_amount'], 2, '.', '');
            unset($item['data_time'], $item['total_amount']);
            return $item;
        });

        return $monthSaleAmountLine->toJson();
    }


    /**
     * 销量月走势
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumLine  [销量走势数据]
     * @return [type]                                    [html]
     */
    public function  monthSaleNumLine ($monthSaleNumLine)
    {
        $monthSaleNumLine = collect($monthSaleNumLine->toArray())->map(function ($item, $key) {
            //$item['day'] = date('Y-m-d', strtotime($item['data_time']));
            $item['value'] = $item['total_num'];
            unset($item['data_time'], $item['total_num']);
            return $item;
        });

        return $monthSaleNumLine->toJson();
    }

    /**
     * 平台销售额
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountPlatform  [平台销售额数据]
     * @return [type]                                    [html]
     */
    public function saleAmountPlatform ($saleAmountPlatform)
    {
        $saleAmountPlatform = collect($saleAmountPlatform)->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            unset($item['data_time']);
            return $item;
        });

        return $saleAmountPlatform->toJson();
    }

    /**
     * 平台销量
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumPlatform  [平台销量数据]
     * @return [type]                                    [html]
     */
    public function saleNumPlatform ($saleNumPlatform)
    {
        $saleNumPlatform = collect($saleNumPlatform)->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            unset($item['data_time']);
            return $item;
        });

        return $saleNumPlatform->toJson();
    }

    /**
     * 产品销售额
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountProduct  [产品销售额数据]
     * @return [type]                                       [html]
     */
    public function saleAmountProduct ($saleAmountProduct)
    {
        $saleAmountProduct = collect($saleAmountProduct)->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            unset($item['data_time']);
            return $item;
        });

        return $saleAmountProduct->toJson();
    }


    /**
     * 产品销量
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumProduct     [产品销量数据]
     * @return [type]                                       [html]
     */
    public function saleNumProduct ($saleNumProduct)
    {
        $saleNumProduct = collect($saleNumProduct)->map(function ($item, $key) {
            $item['day'] = date('Y-m-d', strtotime($item['data_time']));
            unset($item['data_time']);
            return $item;
        });

        return $saleNumProduct->toJson();
    }


    /**
     * 平台销售额比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountPlatformPie     [平台销售额比例数据]
     * @return [type]                                               [html]
     */
    public function saleAmountPlatformPie ($saleAmountPlatformPie)
    {
        return collect($saleAmountPlatformPie)->toJson();
    }

    /**
     * 平台销量比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumPlatformPie     [平台销量比例数据]
     * @return [type]                                               [html]
     */
    public function saleNumPlatformPie ($saleNumPlatformPie)
    {
        return collect($saleNumPlatformPie)->toJson();
    }

    /**
     * 产品销售额比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleAmountProductPie     [产品销售额比例数据]
     * @return [type]                                               [html]
     */
    public function saleAmountProductPie ($saleAmountProductPie)
    {
        return collect($saleAmountProductPie)->toJson();
    }

    /**
     * 产品销量比例
     * @author Sheldon
     * @date   2017-04-26T09:36:36+0800
     * @param  [type]                   $saleNumProductPie          [产品销量比例数据]
     * @return [type]                                               [html]
     */
    public function saleNumProductPie ($saleNumProductPie)
    {
        return collect($saleNumProductPie)->toJson();
    }
}