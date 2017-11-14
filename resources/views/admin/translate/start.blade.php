@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
@endsection
@section( 'content' )
@inject( 'ProjectPresenter', 'App\Presenters\Admin\ProjectPresenter' )
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/project.translated')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/translate')}}">{!!trans('admin/breadcrumb.translate.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.translate.start')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/project.translated')!!}</h5>
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
          <form method="post" action="{{url( route( 'translate.finish', ['id' => $id] ) )}}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="language_id" value="{{ $id }}">
            <?php if ( $source->isNotEmpty() ) { ?>
            <?php foreach ( $source as $k => $item ) { ?>
            <div class="form-group source-item">
              <label class="col-sm-2 control-label"></label>
              <input type="hidden" name="key_id" value="<?php echo $item->key_id; ?>">
              <div class="col-sm-3">
                <input type="text" class="form-control" name="content" value="{{old( 'content', $item->content )}}">
              </div>
              <div class="col-sm-3">
                <input type="text" class="form-control" name="translated" value="{{ $translated[$item->key_id] or '' }}" placeholder="{{trans('admin/project.translated')}}">
              </div>
              <button type="button" class="btn btn-default" aria-label="Left Align" title="保存" onclick="save_translated( $(this) );">
                <span class="glyphicon glyphicon-saved" aria-hidden="true"></span>
              </button>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if ( ! empty( $errors->first( 'translated' ) ) ) { ?>
            <div class="alert alert-warning alert-dismissable">
                <?php echo $errors->first( 'translated' ); ?>
            </div>
            <?php } ?>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                    <a class="btn btn-white" href="{{url( 'admin/translate' )}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                    <button type="submit" class="btn btn-primary">{!!trans('admin/action.actionButton.submit')!!}</button>
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
<script type="text/javascript" src="{{asset('vendors/iCheck/icheck.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendors/layer/layer.js')}}"></script>
<script src="{{asset('admin/js/translate/translate.js')}}"></script>
@endsection