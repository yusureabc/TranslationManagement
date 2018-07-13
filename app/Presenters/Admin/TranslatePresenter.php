<?php 
namespace App\Presenters\Admin;

use App\Repositories\Eloquent\CommentRepositoryEloquent;

class TranslatePresenter
{

    public function __construct( CommentRepositoryEloquent $commentRepository )
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * 显示这条译文是否标记
     * @return 被标记显示实心的小旗，未标记显示线框小旗
     */
    public function showFlag( $flag )
    {
        return $flag == 1 ? 'fa fa-flag red' : 'fa fa-flag-o';
    }

    /**
     * 显示是否有评论
     */
    public function showComments( $content_id = 0, $has_comment )
    {
        if ( in_array( $content_id, $has_comment ) )
        {
            return 'fa fa-commenting red';
        }
        else
        {
            return 'fa fa-commenting-o';
        }
    }
}