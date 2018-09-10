@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
<style>
    /* 上传按钮样式 */
   .custom-file {
       position: relative;
       display: inline-block;
       background: #D0EEFF;
       border: 1px solid #99D3F5;
       border-radius: 4px;
       padding: 4px 12px;
       overflow: hidden;
       color: #1E88C7;
       text-decoration: none;
       text-indent: 0;
       line-height: 20px;
   }
   .custom-file input {
       position: absolute;
       font-size: 100px;
       right: 0;
       top: 0;
       opacity: 0;
   }
   .custom-file:hover {
       background: #AADFFD;
       border-color: #78C3F3;
       color: #004974;
       text-decoration: none;
   }
</style>
@endsection
@section('content')


<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/project.import_excel')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/project')}}">{!!trans('admin/breadcrumb.project.list')!!}</a>
        </li> 
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.project.import')!!}</strong>
        </li>
    </ol>
  </div>
</div>


<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/project.import_excel')!!}</h5>
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

            @if ( $errors->has('no_file') )
            <div class="alert alert-danger">
                <ul style="color:red;">
                    <li> {{ $errors->first('no_file') }} </li>
                </ul>
            </div>
            @endif

          <form method="post" action="{{ url()->full() }}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="custom-file">
                <input id="excel" type="file" name="excel" class="custom-file-input">
                <label for="excel" class="custom-file-label">Choose file...</label>
            </div>
            <br>
            <input type="submit" class="btn btn-primary" value="Submit">
          </form>
            
            <br><br>
            <a href="{{ asset('admin/file/import_demo.xls') }}" class="btn btn-primary">
                <i class="fa fa-download"></i> Download Demo Excel
            </a>

        </div>
    </div>
    </div>
  </div>
</div>

@endsection
@section('js')
<script type="text/javascript">
    $('.custom-file-input').on('change', function() {
       let fileName = $(this).val().split('\\').pop();
       $(this).next('.custom-file-label').addClass("selected").html(fileName);
    }); 
</script>
<script type="text/javascript" src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/layer/layer.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/ddsort/ddsort.js')}}"></script>
<script src="{{asset('admin/js/project/project.js?ver=2018071301')}}"></script>
@endsection