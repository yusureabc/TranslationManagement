@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
@endsection
@section('content')
@inject( 'ProjectPresenter', 'App\Presenters\Admin\ProjectPresenter' )
<div class="row wrapper border-bottom white-bg page-heading">
  <div class="col-lg-10">
    <h2>{!!trans('admin/project.input')!!}</h2>
    <ol class="breadcrumb">
        <li>
            <a href="{{url('admin/dash')}}">{!!trans('admin/breadcrumb.home')!!}</a>
        </li>
        <li>
            <a href="{{url('admin/project')}}">{!!trans('admin/breadcrumb.project.list')!!}</a>
        </li>
        <li class="active">
            <strong>{!!trans('admin/breadcrumb.project.input')!!}</strong>
        </li>
    </ol>
  </div>
</div>
<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>{!!trans('admin/project.input')!!}</h5>
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
          <form method="post" action="{{url('admin/project')}}" class="form-horizontal" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" id="project_id" value="{{ $id }}">
            <?php if ( $keys->isNotEmpty() ) { ?>
            <?php foreach ( $keys as $k => $item ) { ?>
            <div class="form-group source-item">
                <input type="hidden" name="sort" value="<?php echo $item->sort; ?>" onchange="sort_change( this.value );">
                <label name="key_id" class="col-sm-2 control-label"><?php echo $item->id; ?></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="key" value="{{old( 'key', $item->key )}}" onchange="save_key( $(this) );" placeholder="key">
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="source" value="{{old( 'source', $item->source )}}" onchange="save_key( $(this) );" placeholder="{{trans('admin/project.source')}}">
                </div>
                <button type="button" class="btn btn-default" aria-label="Left Align" title="下方插入" onclick="below_insert( $(this) );">
                  <span class="fa fa-plus" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-default" aria-label="Left Align" title="删除" onclick="remove_key( $(this) );">
                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
            </div>
            <?php } ?>
            <?php } else { ?>
            <div class="form-group source-item">
                <input type="hidden" name="sort" value="0" onchange="sort_change( this.value );">
                <label name="key_id" class="col-sm-2 control-label"></label>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="key" value="" onchange="save_key( $(this) );" placeholder="key">
                </div>
                <div class="col-sm-3">
                  <input type="text" class="form-control" name="source" value="" onchange="save_key( $(this) );" placeholder="源语言">
                </div>
                <button type="button" class="btn btn-default" aria-label="Left Align" title="下方插入" onclick="below_insert( $(this) );">
                  <span class="fa fa-plus" aria-hidden="true"></span>
                </button>
                <button type="button" class="btn btn-default" aria-label="Left Align" title="删除" onclick="remove_key( $(this) );">
                  <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </button>
            </div>
            <?php } ?>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                  <a class="btn btn-white" href="{{url()->previous()}}">{!!trans('admin/action.actionButton.cancel')!!}</a>
                  <button type="button" class="btn btn-primary" aria-label="Left Align">
                    <span id="append_line" class="glyphicon glyphicon-plus" aria-hidden="true"> <b>追加10行</b> </span>
                  </button>
                  <!-- <button class="btn btn-primary" type="submit">{!!trans('admin/action.actionButton.submit')!!}</button> -->
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
<script type="text/javascript" src="{{asset('vendors/ddsort/ddsort.js')}}"></script>
<script src="{{asset('admin/js/project/project.js?ver=2017120802')}}"></script>
@endsection