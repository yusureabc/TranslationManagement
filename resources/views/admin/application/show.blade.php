<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
  <h4 class="modal-title">{{trans('admin/application.info')}}</h4>
</div>
<div class="modal-body">
  <form class="form-horizontal">
    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">{{trans('admin/application.model.name')}}</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{ $application->name }}</p>
      </div>
    </div>

    <div class="hr-line-dashed no-margins"></div>
    <div class="form-group">
      <label class="col-sm-3 control-label">{{trans('admin/application.model.description')}}</label>
      <div class="col-sm-8">
        <p class="form-control-static">{{$application->description}}</p>
      </div>
    </div>

    <div class="form-group">
      <div class="col-sm-12">
        <div class="ibox float-e-margins">
          <div class="ibox-content">
            <table class="table table-bordered">
              <thead>
              <tr>
                <th class="col-md-10 text-center">{{trans('admin/application.sub_project')}}</th>
              </tr>
              </thead>
              <tbody>
                @forelse ($sub_project as $project)
                <tr>
                    <td>
                        <div class="col-md-2">
                            <label> {{ $project->name }} </label>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td> 暂无子项目，请编辑项目分配 </td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">{!!trans('admin/action.actionButton.close')!!}</button>
</div>