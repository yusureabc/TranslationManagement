@extends('layouts.admin')
@section('css')
<link href="{{asset('vendors/iCheck/custom.css')}}" rel="stylesheet">
<style type="text/css">
    .old-content
    {
        height: auto;
    }
</style>
@endsection
@section( 'content' )
@inject( 'TranslatePresenter', 'App\Presenters\Admin\TranslatePresenter' )
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
              <input type="hidden" name="key_id" value="<?php echo $item->key_id; ?>">
              <div class="col-sm-3">
                <div class="reference-key">{{old( 'key', $item->key )}}</div> 
              </div>
              <div class="col-sm-4">
                <div class="form-control old-content">{{old( 'content', $item->content )}}</div> 
              </div>
              <div class="col-sm-4">
                <div class="form-control old-content" name="translated" contenteditable="true" onblur="save_translated( $(this) );">{{ $translated[$item->key_id]['content'] or '' }}</div>
              </div>
              <input type="hidden" name="content_id" value="{{ $translated[$item->key_id]['id'] or '' }}">
              <button type="button" class="btn btn-default" aria-label="Left Align" title="Comments" onclick="comments( $(this) );">
                <span class="{!! $TranslatePresenter->showComments( isset( $translated[$item->key_id]['id'] ) ? $translated[$item->key_id]['id'] : 0 ) !!}" aria-hidden="true"></span>
              </button>
              <button type="button" class="btn btn-default" aria-label="Left Align" title="Flag" onclick="flag( $(this) );">
                <span name="flag" class="{!! $TranslatePresenter->showFlag( $translated[$item->key_id]['flag'] ?? 0 ) !!}" aria-hidden="true"></span>
              </button>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if ( ! empty( $errors->first( 'translated' ) ) ) { ?>
            <div class="alert alert-warning alert-dismissable">
                <?php echo $errors->first( 'translated' ); ?>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
            <?php } ?>

            <div class="hr-line-dashed"></div>
            <div class="form-group">
              <div class="col-sm-4 col-sm-offset-2">
                    <a class="btn btn-white" onclick="history.back();">{!!trans('admin/action.actionButton.cancel')!!}</a>
                    <!-- <a class="btn btn-warning" href="">{!!trans('admin/action.actionButton.save')!!}</a> -->
                    <!-- <button type="submit" onclick="return check_translated();" class="btn btn-primary">{!!trans('admin/action.actionButton.done')!!}</button> -->
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
<script type="text/javascript" src="{{asset('vendors/layer/layer.js')}}"></script>
<script src="{{asset('admin/js/translate/translate.js?ver=20180713')}}"></script>
@endsection