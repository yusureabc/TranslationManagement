@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/platform.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/platform')}}">{!!trans('admin/breadcrumb.platform.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.platform.edit')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/platform.edit')!!}</h5>
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
          <form method="post" action="{{url('admin/platform',[$platform->id])}}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            {{method_field('PUT')}}
            <input type="hidden" name="id" value="{{$platform->id}}">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/platform.model.name')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name',$platform->name)}}" placeholder="{{trans('admin/platform.model.name')}}">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/platform.model.slug')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="slug" value="{{old('slug',$platform->slug)}}" placeholder="{{trans('admin/platform.model.slug')}}">
                @if ($errors->has('slug'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('slug') }}</span>
                @endif
              </div>
            </div>
              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">{{trans('admin/platform.model.url')}}</label>
                  <div class="col-sm-10">
                      <input type="text" class="form-control" name="url" value="{{old('url',$platform->url)}}" placeholder="{{trans('admin/platform.model.url')}}">
                      @if ($errors->has('url'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('url') }}</span>
                      @endif
                  </div>
              </div>


              <div class="hr-line-dashed"></div>
              <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                  <label class="col-sm-2 control-label">{{trans('admin/platform.model.logo')}}</label>
                  <div class="col-sm-10">
                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                          <div class="form-control" data-trigger="fileinput">
                              <i class="glyphicon glyphicon-file fileinput-exists"></i>
                              <span class="fileinput-filename">{{old('logo',$platform->logo)}}</span>
                          </div>
                          <span class="input-group-addon btn btn-default btn-file">
                        <span class="fileinput-new">Select file</span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="logo" value="{{old('logo',$platform->logo)}}" placeholder="{{trans('admin/platform.model.logo')}}"/>
                    </span>
                          <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                      </div>
                      @if ($errors->has('logo'))
                          <span class="help-block m-b-none text-danger">{{ $errors->first('logo') }}</span>
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
<script type="text/javascript" src="{{asset('admin/js/platform/platform.js')}}"></script>
@endsection