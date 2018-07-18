<?php

namespace App\Traits;

trait ActionButtonAttributeTrait
{

    /**
     * 查看按钮
     * @param  boolean $type [默认为跳转页面查看信息,false时<a>标签带上modal样式]
     * @return [type]
     */
    public function getShowActionButton($type = true)
    {
        /* 查看按钮开关，需要修改 config/admin/global.php */
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
            return '<a href="javascript:;" target="_blank" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-info tooltips download_item" data-container="body" data-original-title="' . trans('admin/action.actionButton.download') . '"  data-placement="top"><i class="fa fa-download"></i></a> ';
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
     * 锁定/开启  按钮
     * $status  状态  0 lock, 1 open
     */
    public function getStatusActionButton( $status )
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.status'))) 
        {
            $icon = $status == 0 ? 'fa fa-unlock' : 'fa fa-lock';
            $title = $status == 0 ? trans('admin/action.open') : trans('admin/action.lock');
            return '<a href="'.url('admin/'.$this->action.'/'.$this->id.'/status/'.$status).'" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-success tooltips" data-container="body" data-original-title="' . $title . '"  data-placement="top"><i class="'. $icon .'"></i></a> ';
        }
        return '';
    }

    /**
     * 发送通知
     */
    public function getSendActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.send'))) {
            return '<a href="javascript:;" target="_blank" data-id="'.$this->id.'" class="btn btn-xs btn-outline btn-info tooltips sendmail_item" data-container="body" data-original-title="' . trans('admin/action.actionButton.send') . '"  data-placement="top"><i class="fa fa-envelope-o"></i></a> ';
        }
        return '';
    }

    /**
     * 开始翻译
     */
    public function getStartActionButton()
    {
        if (auth()->user()->can(config('admin.permissions.'.$this->action.'.start'))) {
            return '<a href="'.url('admin/'.$this->action.'/'.$this->language_id.'/start').'" data-id="'.$this->language_id.'" class="btn btn-xs btn-outline btn-primary tooltips" data-container="body" data-original-title="' . trans('admin/action.actionButton.start') . '"  data-placement="top"><i class="fa fa-play"></i></a> ';
        }
        return '';
    }
    
    /**
     * 获取按钮
     */
    public function getActionButtonAttribute($showType = true, $status = 1)
    {
        return $this->getShowActionButton($showType).
                $this->getResetActionButton().
                $this->getEditActionButton().
                $this->getInputActionButton().
                $this->getDownloadActionButton().
                $this->getInviteActionButton().
                $this->getStatusActionButton( $status ).
                $this->getStartActionButton().
                $this->getDestroyActionButton().
                $this->getSendActionButton();
    }
}