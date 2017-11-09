<?php
namespace App\Traits;
trait ActionButtonAttributeTrait
{
    /**
     * 查看按钮
     * @author Sheldon
     * @date   2017-04-31T18:14:09+0800
     * @param  boolean      $type [默认为跳转页面查看信息,false时<a>标签带上modal样式]
     * @return [type]
     */
    public function getShowActionButton($type = true)
    {
        //开启查看按钮
        if (config('admin.global.'.$this->action.'.show')) {
            if (auth()->user()->can(config('admin.permissions.'.$this->action.'.show'))) {
                if ($type) {
                    return '<a href="'.url('admin/'.$this->action.'/'.$this->id).'" class="btn btn-xs btn-outline btn-info tooltips" data-toggle="tooltip" data-original-title="' . trans('admin/action.actionButton.show') . '"  data-placement="top"><i class="fa fa-eye"></i></a> ';
                }
                return '<a href="'.url('admin/'.$this->action.'/'.$this->id).'" class="btn btn-xs btn-info tooltips" data-toggle="modal" data-target="#myModal" data-original-title="' . trans('admin/action.actionButton.show') . '"  data-placement="top"><i class="fa fa-eye"></i></a> ';
            }
            return '';
        }
        return '';
    }
    /**
     * 修改按钮
     * @author Sheldon
     * @date   2017-04-31T18:13:50+0800
     * @return [type]
     */
    public function getEditActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.edit'))) {
            return '<a href="'.url('admin/'.$this->action.'/'.$this->id.'/edit').'" class="btn btn-xs btn-outline btn-warning tooltips" data-original-title="' . trans('admin/action.actionButton.edit') . '"  data-placement="top"><i class="fa fa-edit"></i></a> ';
        }
        return '';
    }

    /**
     * 彻底删除按钮
     * @author Sheldon
     * @date   2017-04-31T18:14:39+0800
     * @param  boolean
     * @return [type]
     */
    public function getDestroyActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.destroy'))) {
            return '<a href="javascript:;" onclick="return false" class="btn btn-xs btn-outline btn-danger tooltips destroy_item" data-original-title="' . trans('admin/action.actionButton.destroy') . '"  data-placement="top"><i class="fa fa-trash"></i><form action="'.url('admin/'.$this->action.'/'.$this->id).'" method="POST" name="delete_item" style="display:none"><input type="hidden" name="_method" value="delete"><input type="hidden" name="_token" value="'.csrf_token().'"></form></a> ';
        }
        return '';
    }
    /**
     * 重置用户密码
     * @author Sheldon
     * @date   2017-04-31T18:14:48+0800
     * @return [type]
     */
    public function getResetActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.reset'))) {
            return '<a href="javascript:;" data-id="'.$this->id.'" class="btn btn-outline btn-xs btn-default tooltips reset_password" data-container="body" data-original-title="' . trans('admin/action.actionButton.reset') . '"  data-placement="top"><i class="fa fa-lock"></i></a> ';
        }
        return '';
    }

    /**
     * 录入内容按钮
     * @author Yusure  http://yusure.cn
     * @date   2017-11-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function getInputActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.input'))) {
            return '<a href="'.url('admin/'.$this->action.'/'.$this->id.'/input').'" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-info tooltips" data-container="body" data-original-title="' . trans('admin/action.actionButton.input') . '"  data-placement="top"><i class="fa fa-keyboard-o"></i></a> ';
        }
        return '';
    }

    /**
     * 下载按钮
     */
    public function getDownloadActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.download'))) {
            return '<a href="'.url('admin/'.$this->action.'/'.$this->id.'/download').'" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-info tooltips" data-container="body" data-original-title="' . trans('admin/action.actionButton.download') . '"  data-placement="top"><i class="fa fa-download"></i></a> ';
        }
        return '';
    }

    /**
     * 邀请按钮
     */
    public function getInviteActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.invite'))) {
            return '<a href="'.url('admin/'.$this->action.'/'.$this->id.'/invite').'" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-danger tooltips" data-container="body" data-original-title="' . trans('admin/action.actionButton.invite') . '"  data-placement="top"><i class="fa fa-hand-o-right"></i></a> ';
        }
        return '';
    }
    
    /**
     * 获取按钮
     * @author Sheldon
     * @date   2017-04-31T18:14:57+0800
     * @param  boolean
     * @return [type]
     */
    public function getActionButtonAttribute($showType = true)
    {
        return $this->getShowActionButton($showType).
                $this->getResetActionButton().
                $this->getEditActionButton().
                $this->getInputActionButton().
                $this->getDownloadActionButton().
                $this->getInviteActionButton().
                $this->getDestroyActionButton();
    }
}