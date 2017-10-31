@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/morris/morris-0.4.3.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    @inject('salesdataPresenter','App\Presenters\Admin\SalesdataPresenter')
    @inject('salesdataChartsPresenter','App\Presenters\Admin\SalesdataChartsPresenter')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/salesdata.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.salesdata.charts')!!}</strong>
        </li>
    </ol>
  </div>
  @permission(config('admin.permissions.salesdata.create'))
  <div class="col-lg-2">
    <div class="title-action">
      <a href="{{url('admin/salesdata/create')}}" class="btn btn-info">{!!trans('admin/salesdata.action.create')!!}</a>
    </div>
  </div>
  @endpermission
</div>



<div class="wrapper wrapper-content animated fadeInRight">

    <div class="ibox-content m-b-sm border-bottom">
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group" id="platform_id">
                    <label class="control-label" for="{{trans('admin/salesdata.model.platform_id')}}">{{trans('admin/salesdata.model.platform_id')}}</label>
                    {!! $salesdataPresenter->platformSelector($platforms, $platform_id) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" id="product_id">
                    <label class="control-label" for="{{trans('admin/salesdata.model.product_id')}}">{{trans('admin/salesdata.model.product_id')}}</label>
                    {!! $salesdataPresenter->productSelector($products, $product_id) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group" id="data_time">
                    <label class="control-label" for="{{trans('admin/salesdata.model.data_time')}}">{{trans('admin/salesdata.model.data_time')}}</label>
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="input-sm form-control" name="data_time_start" value="{{ $data_time_start }}">
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control" name="data_time_end" value="{{ $data_time_end }}">
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="search"></label>
                    <div class="input-group">
                        <input type="button" id="charts_show" class="btn btn-info" value="{!!trans('admin/salesdata.action.charts')!!}" />
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{--销售额走势--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleAmountLine')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>

                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="saleAmountLine"></div>
                </div>
            </div>
        </div>
    </div>

    {{--销量走势--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleNumLine')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="saleNumLine"></div>
                </div>
            </div>
        </div>
    </div>

    {{--销售额月走势--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.monthSaleAmountLine')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>

                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="monthSaleAmountLine"></div>
                </div>
            </div>
        </div>
    </div>

    {{--销量月走势--}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.monthSaleNumLine')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div id="monthSaleNumLine"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        {{--平台销售额--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleAmountPlatform')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleAmountPlatform"></div>
                </div>
            </div>
        </div>
        {{--平台销量--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleNumPlatform')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleNumPlatform"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{--产品销售额--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleAmountProduct')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleAmountProduct"></div>
                </div>
            </div>
        </div>
        {{--产品销量--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleNumProduct')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleNumProduct"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{--平台销售额比例--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleAmountPlatformPie')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleAmountPlatformPie"></div>
                </div>
            </div>
        </div>
        {{--平台销量比例--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleNumPlatformPie')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleNumPlatformPie"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{--产品销售额比例--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleAmountProductPie')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleAmountProductPie"></div>
                </div>
            </div>
        </div>
        {{--产品销量比例--}}
        <div class="col-lg-6">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>{!!trans('admin/salesdata.charts.saleNumProductPie')!!}</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content" style="position: relative">
                    <div id="saleNumProductPie"></div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('js')
<script src="{{asset('vendors/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendors/layer/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/datapicker/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/morris/raphael-2.1.0.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/morris/morris.js')}}"></script>
<script src="{{asset('admin/js/salesdata/salesdata-charts.js')}}"></script>
<script>

$(function() {

    /**
     * 销售额走势
     */
    Morris.Line({
        element: 'saleAmountLine',
        data: {!! $salesdataChartsPresenter->saleAmountLine($saleAmountLine) !!},
        xkey: 'day',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        xLabels: ['day'],
        yLabels: ['value'],
        labels: ['{{trans("admin/salesdata.charts.amount")}}'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        pointSize:5,
    });

    /**
     * 销量走势
     */
    Morris.Line({
        element: 'saleNumLine',
        data: {!! $salesdataChartsPresenter->saleNumLine($saleNumLine) !!},
        xkey: 'day',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        xLabels: ['day'],
        yLabels: ['value'],
        labels: ['{!!trans('admin/salesdata.charts.num')!!}'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        pointSize:5,
    });

    /**
     * 销售额月走势
     */
    Morris.Line({
        element: 'monthSaleAmountLine',
        data: {!! $salesdataChartsPresenter->monthSaleAmountLine($monthSaleAmountLine) !!},
        xkey: 'month',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        xLabels: ['month'],
        yLabels: ['value'],
        labels: ['{{trans("admin/salesdata.charts.amount")}}'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        pointSize:5,
    });

    /**
     * 销量月走势
     */
    Morris.Line({
        element: 'monthSaleNumLine',
        data: {!! $salesdataChartsPresenter->monthSaleNumLine($monthSaleNumLine) !!},
        xkey: 'month',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        xLabels: ['month'],
        yLabels: ['value'],
        labels: ['{!!trans('admin/salesdata.charts.num')!!}'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        pointSize:5,
    });

    /**
     * 平台销售额
     */
    Morris.Area({
        element: 'saleAmountPlatform',
        data: {!! $salesdataChartsPresenter->saleAmountPlatform($saleAmountPlatform) !!},
        xkey: 'day',
        ykeys: {!! $platform_names !!},
        labels: {!! $platform_names !!},
        pointSize: 2,
        hideHover: 'auto',
        xLabels: ['day'],
        resize: true,
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        lineWidth:2,
        pointSize:1,
    });

    /**
     * 平台销量
     */
    Morris.Area({
        element: 'saleNumPlatform',
        data: {!! $salesdataChartsPresenter->saleNumPlatform($saleNumPlatform) !!},
        xkey: 'day',
        ykeys: {!! $platform_names !!},
        labels: {!! $platform_names !!},
        pointSize: 2,
        hideHover: 'auto',
        xLabels: ['day'],
        resize: true,
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        lineWidth:2,
        pointSize:1,
    });

    /**
     * 产品销售额
     */
    Morris.Area({
        element: 'saleAmountProduct',
        data: {!! $salesdataChartsPresenter->saleAmountProduct($saleAmountProduct) !!},
        xkey: 'day',
        ykeys: {!! $product_names !!},
        labels: {!! $product_names !!},
        pointSize: 2,
        hideHover: 'auto',
        resize: true,
        xLabels: ['day'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        lineWidth:2,
        pointSize:1,
    });

    /**
     * 产品销量
     */
    Morris.Area({
        element: 'saleNumProduct',
        data: {!! $salesdataChartsPresenter->saleNumProduct($saleNumProduct) !!},
        xkey: 'day',
        ykeys: {!! $product_names !!},
        labels: {!! $product_names !!},
        pointSize: 2,
        hideHover: 'auto',
        resize: true,
        xLabels: ['day'],
        lineColors: {!! $salesdataChartsPresenter->lineColors() !!},
        lineWidth:2,
        pointSize:1,
    });

    /**
     * 平台销售额比例
     */
    Morris.Donut({
        element: 'saleAmountPlatformPie',
        data: {!! $salesdataChartsPresenter->saleAmountPlatformPie($saleAmountPlatformPie) !!},
        resize: true,
        colors: {!! $salesdataChartsPresenter->lineColors() !!}
    });

    /**
     * 平台销量比例
     */
    Morris.Donut({
        element: 'saleNumPlatformPie',
        data: {!! $salesdataChartsPresenter->saleNumPlatformPie($saleNumPlatformPie) !!},
        resize: true,
        colors: {!! $salesdataChartsPresenter->lineColors() !!},
    });

    /**
     * 产品销售额比例
     */
    Morris.Donut({
        element: 'saleAmountProductPie',
        data: {!! $salesdataChartsPresenter->saleAmountProductPie($saleAmountProductPie) !!},
        resize: true,
        colors: {!! $salesdataChartsPresenter->lineColors() !!},
    });

    /**
     * 产品销量比例
     */
    Morris.Donut({
        element: 'saleNumProductPie',
        data: {!! $salesdataChartsPresenter->saleNumProductPie($saleNumProductPie) !!},
        resize: true,
        colors: {!! $salesdataChartsPresenter->lineColors() !!},
    });
});

    $('#data_time .input-daterange').datepicker({
        format: 'yyyy-mm-dd',
        keyboardNavigation: false,
        forceParse: false,
        autoclose: true
    });
</script>
@endsection