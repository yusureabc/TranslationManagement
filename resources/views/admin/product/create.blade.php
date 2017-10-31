@extends('layouts.admin')
@section('css')

@endsection
@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/product.title')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/product')}}">{!!trans('admin/breadcrumb.product.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.product.create')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/product.create')!!}</h5>
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
          <form method="post" action="{{url('admin/product')}}" class="form-horizontal">
            {{csrf_field()}}
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/product.model.name')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="name" value="{{old('name')}}" placeholder="{{trans('admin/product.model.name')}}">
                @if ($errors->has('name'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('model') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/product.model.model')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="model" value="{{old('model')}}" placeholder="{{trans('admin/product.model.model')}}">
                @if ($errors->has('model'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('model') }}</span>
                @endif
              </div>
            </div>
            <div class="hr-line-dashed"></div>
            <div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
              <label class="col-sm-2 control-label">{{trans('admin/product.model.code')}}</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" name="code" value="{{old('code')}}" placeholder="{{trans('admin/product.model.code')}}">
                @if ($errors->has('code'))
                <span class="help-block m-b-none text-danger">{{ $errors->first('code') }}</span>
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
<script type="text/javascript" src="{{asset('admin/js/product/product.js')}}"></script>
@endsection