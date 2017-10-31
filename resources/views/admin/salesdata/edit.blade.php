@extends('layouts.admin')
@section('css')
    <link href="{{asset('vendors/datapicker/datepicker3.css')}}" rel="stylesheet">
    <link href="{{asset('vendors/touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    @inject('salesdataPresenter','App\Presenters\Admin\SalesdataPresenter')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{!!trans('admin/salesdata.title')!!}</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
                </li>
                <li>
                    <a href="{{url('admin/salesdata')}}">{!!trans('admin/breadcrumb.salesdata.list')!!}</a>
                </li>
                <li class="active">
                    <strong>{!!trans('admin/breadcrumb.salesdata.edit')!!}</strong>
                </li>
            </ol>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{!!trans('admin/salesdata.edit')!!}</h5>
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
                          <form method="post" action="{{url('admin/salesdata',[$salesdata->id])}}" class="form-horizontal">
                            {{csrf_field()}}
                            {{method_field('PUT')}}
                            <input type="hidden" name="id" value="{{$salesdata->id}}">
                            <div class="form-group{{ $errors->has('platform_id') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('admin/salesdata.model.platform_id')}}</label>
                                <div class="col-sm-10">
                                    {!! $salesdataPresenter->platformSelector($platforms, $salesdata->platform_id) !!}
                                    @if ($errors->has('platform_id'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('platform_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('product_id') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('admin/salesdata.model.product_id')}}</label>
                                <div class="col-sm-10">
                                    {!! $salesdataPresenter->productSelector($products, $salesdata->product_id) !!}
                                    @if ($errors->has('product_id'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('product_id') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group{{ $errors->has('num') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('admin/salesdata.model.num')}}</label>
                                <div class="col-sm-10 input-group bootstrap-touchspin">

                                    <div class="input-group bootstrap-touchspin">
                                        <input class="touchspin1 form-control" type="text" value="{{old('num' ,$salesdata->num)}}" name="num" placeholder="{{trans('admin/salesdata.model.num')}}" style="display: block;">
                                    </div>
                                    @if ($errors->has('num'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('num') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('admin/salesdata.model.amount')}}</label>
                                <div class="col-sm-10 input-group m-b bootstrap-touchspin">
                                    <div class="input-group bootstrap-touchspin">
                                        <input class="touchspin2 form-control" type="text" value="{{old('amount', $salesdata->amount)}}" name="amount" placeholder="{{trans('admin/salesdata.model.amount')}}" style="display: block;">
                                    </div>

                                    @if ($errors->has('amount'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('amount') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group{{ $errors->has('data_time') ? ' has-error' : '' }}">
                                <label class="col-sm-2 control-label">{{trans('admin/salesdata.model.data_time')}}</label>
                                <div class="col-sm-10">
                                    <div class="input-group date">
                          <span class="input-group-addon">
                              <i class="fa fa-calendar"></i>
                          </span>
                                        <input type="text" class="form-control" name="data_time" value="{{old('data_time', $salesdata->data_time)}}" placeholder="{{trans('admin/salesdata.model.data_time')}}">
                                    </div>

                                    @if ($errors->has('data_time'))
                                        <span class="help-block m-b-none text-danger">{{ $errors->first('data_time') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <a class="btn btn-white" href="{{url()->previous()}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                                    <button class="btn btn-primary" type="submit">{!!trans('admin/action.actionButton.submit')!!}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript" src="{{asset('admin/js/salesdata/salesdata.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendors/datapicker/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendors/touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
@endsection