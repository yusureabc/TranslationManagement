@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/dataTables/datatables.min.css')}}" rel="stylesheet">
    <style>
        .fadeInRight img{
            max-height: 100px;
            max-width: 100px;
        }
    </style>
@endsection
@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/language.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.language.list')!!}</strong>
        </li>
    </ol>
  </div>
  @permission(config('admin.permissions.language.create'))
  <div class="col-lg-2">
    <div class="title-action">
      <a href="{{url('admin/language/create')}}" class="btn btn-info">{!!trans('admin/language.action.create')!!}</a>
    </div>
  </div>
  @endpermission
</div>
<div class="wrapper wrapper-content animated fadeInRight">
    <input type="hidden" id="project_id" value="{{$project->id}}">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/language.desc')!!}</h5>
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
                        <th>{{trans('admin/language.model.id')}}</th>
                        <th>{{trans('admin/language.model.language')}}</th>
                        <th>{{trans('admin/language.model.name')}}</th>
                        <th>{{trans('admin/language.model.submit_at')}}</th>
                        <th>{{trans('admin/language.model.download_at')}}</th>
                        <th>{{trans('admin/language.model.status')}}</th>
                        <th>{{trans('admin/action.title')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <a class="btn btn-white" href="{{url()->previous()}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                </div>
              </div>
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
<script src="{{asset('admin/js/language/language-datatable.js')}}"></script>
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
</script>
@endsection