@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>转换Excel</h2>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">

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
          <form method="post" action="{{url('admin/salesdata/transexcel-export')}}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">选择Excel文件</label>
              <div class="col-sm-10">
                  <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                      <div class="form-control" data-trigger="fileinput">
                          <i class="glyphicon glyphicon-file fileinput-exists"></i>
                          <span class="fileinput-filename">Excel文件</span>
                      </div>
                      <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">Select file</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="excelfile" value="{{old('excelfile')}}" placeholder="Excel文件"/>
                    </span>
                      <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                  </div>
                @if ($errors->has('excelfile'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('excelfile') }}</span>
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
<script type="text/javascript" src="{{asset('vendors/jasny/jasny-bootstrap.min.js')}}"></script>
@endsection