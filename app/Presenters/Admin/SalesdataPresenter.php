<?php
namespace App\Presenters\Admin;

class SalesdataPresenter
{

    /**
     * 平台选择select
     * @author Sheldon
     * @date   2017-04-21T09:36:36+0800
     * @param  [type]                   $platforms     [所有平台]
     * @param  [type]                   $platform_id    [当前平台ID]
     * @return [type]                                    [html]
     */
    public function platformSelector ($platforms, $platform_id = null)
    {
        $html = '';
        if (!empty($platforms)) {
            $html .= "<select name=\"platform_id\" class=\"form-control\">";
            $html .= '<option value="0">' . trans('admin/salesdata.model.platform_id') . '</option>';
            foreach ($platforms as $key => $platform) {
                if ($platform_id == $platform['id']) {
                    $html .= '<option selected value="' .$platform['id']. '">' .$platform['name']. '</option>';
                } else {
                    $html .= '<option value="' .$platform['id']. '">' .$platform['name']. '</option>';
                }
            }
            $html .= "</select>";
        }
        return $html;
    }

    /**
     * 产品选择select
     * @author Sheldon
     * @date   2017-04-21T09:36:36+0800
     * @param  [type]                   $products     [所有产品]
     * @param  [type]                   $product_id   [当前产品ID]
     * @return [type]                                    [html]
     */
    public function productSelector ($products, $product_id = null)
    {
        $html = '';
        if (!empty($products)) {
            $html .= "<select name=\"product_id\" class=\"form-control\">";
            $html .= '<option value="0">' . trans('admin/salesdata.model.product_id') . '</option>';
            foreach ($products as $key => $product) {
                if ($product_id == $product['id']) {
                    $html .= '<option selected value="' .$product['id']. '">' .$product['name']. '</option>';
                } else {
                    $html .= '<option value="' .$product['id']. '">' .$product['name']. '</option>';
                }
            }
            $html .= "</select>";
        }
        return $html;
    }
}