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
                        <th>{{trans('admin/language.model.completion_status')}}</th>                    
                        <th>{{trans('admin/action.title')}}</th>
                      </tr>
                  </thead>
                  <tbody>
                  </tbody>
              </table>
              <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <a class="btn btn-white" href="{{url( 'admin/project' )}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                    <button class="btn btn-primary" id="scan_completion_status">{!!trans('admin/action.actionButton.scan_completion_status')!!}</button>
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
<script src="{{asset('admin/js/language/language-datatable.js?ver=20190225')}}"></script>
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
<script type="text/javascript">
  $(document).on('click','.download_item',function() {
    var _item = $(this);
    var language_id = _item.attr( 'data-id' );
    var url = '/admin/language/' + language_id + '/download';

    layer.open({
      type: 2,
      title: '',
      skin: 'layui-layer-rim', //加上边框
      area: ['320px', '180px'], //宽高
      content: url
    });
  });

$(document).on( 'click', '.sendmail_item', function() {
    var _item = $(this);
    var language_id = _item.attr( 'data-id' );
    var url = '/admin/language/' + language_id + '/send';
    var index = layer.load(1, {
      shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
    $.get( url, function( msg ) {
        layer.closeAll( 'loading' );
        if ( msg.status == 1 )
        {
            layer.msg( '发送成功' );
        }
        else
        {
            layer.msg( '发送失败' );
        }
    } );
})

$(document).on( 'click', '#scan_completion_status', function() {
    var project_id = $( '#project_id' ).val();
    var url = "{{ route( 'language.completion', ['project_id' => 'project_id'] ) }}";
    url = url.replace( 'project_id', project_id );

    var index = layer.load(1, {
      shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
    $.get( url, function( msg ) {
        layer.closeAll( 'loading' );
        if ( msg.status == 1 )
        {
            layer.msg( 'Done' );
            location.reload();
        }
        else
        {
            layer.msg( 'Faild' );
        }
    } );
})

</script>
@endsection