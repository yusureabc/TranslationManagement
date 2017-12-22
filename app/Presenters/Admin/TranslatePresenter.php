<?php 
namespace App\Presenters\Admin;

class TranslatePresenter
{
    /**
     * 显示这条译文是否标记
     * @return 被标记显示实心的小旗，未标记显示线框小旗
     */
    public function showFlag( $flag )
    {
        return $flag == 1 ? 'fa fa-flag red' : 'fa fa-flag-o';
    }
}