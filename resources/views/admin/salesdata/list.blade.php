@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/dataTables/datatables.min.css')}}" rel="stylesheet">
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
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.salesdata.list')!!}</strong>
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
                    {!! $salesdataPresenter->platformSelector($platforms) !!}
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group" id="product_id">
                    <label class="control-label" for="{{trans('admin/salesdata.model.product_id')}}">{{trans('admin/salesdata.model.product_id')}}</label>
                    {!! $salesdataPresenter->productSelector($products) !!}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group" id="data_time">
                    <label class="control-label" for="{{trans('admin/salesdata.model.data_time')}}">{{trans('admin/salesdata.model.data_time')}}</label>
                    <div class="input-daterange input-group" id="datepicker">
                        <input type="text" class="input-sm form-control" name="data_time_start" value="">
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control" name="data_time_end" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="search"></label>
                    <div class="input-group">
                        <input type="button" id="search_salesdata" class="btn btn-info" value="{!!trans('admin/salesdata.action.search')!!}" />
                    </div>

                </div>
            </div>
        </div>
    </div>

  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/salesdata.desc')!!}</h5>
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
          @include('flash::message')
          <div class="table-responsive">
	          <table class="table table-striped table-bordered table-hover dataTablesAjax" >
		          <thead>
			          <tr>
			            <th>{{trans('admin/salesdata.model.id')}}</th>
			            <th>{{trans('admin/salesdata.model.platform_id')}}</th>
			            <th>{{trans('admin/salesdata.model.product_id')}}</th>
                        <th>{{trans('admin/salesdata.model.num')}}</th>
                        <th>{{trans('admin/salesdata.model.amount')}}</th>
                        <th>{{trans('admin/salesdata.model.data_time')}}</th>
			            <th>{{trans('admin/salesdata.model.created_at')}}</th>
			            <th>{{trans('admin/salesdata.model.updated_at')}}</th>
			            <th>{{trans('admin/action.title')}}</th>
			          </tr>
		          </thead>
		          <tbody>
		          </tbody>
	          </table>
          </div>
        </div>
      </div>
  	</div>
  </div>
</div>
@endsection
@section('js')
<script src="{{asset('vendors/dataTables/datatables.min.js')}}"></script>
<script src="{{asset('vendors/layer/layer.js')}}"></script>
<script src="{{asset('admin/js/salesdata/salesdata-datatable.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/datapicker/bootstrap-datepicker.js')}}"></script>
<script type="text/javascript">
  $(document).on('click','.destroy_item',function() {
    var _item = $(this);
    layer.confirm('{{trans('admin/alert.deleteTitle')}}', {
      btn: ['{{trans('admin/action.actionButton.destroy')}}', '{{trans('admin/action.actionButton.no')}}'],
      icon: 5,
    },function(index){
      _item.children('form').submit();
      layer.close(index);
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