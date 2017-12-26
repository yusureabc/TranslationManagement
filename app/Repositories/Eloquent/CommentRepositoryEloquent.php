<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\CommentRepository;
use App\Models\Comment;
use DB;

/**
 * Class CommentRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class CommentRepositoryEloquent extends BaseRepository implements CommentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Comment::class;
    }

    /**
     * 获取评论
     */
    public function getList( $condition )
    {
        return $this->model->where( $condition )->get();
    }

    /**
     * 获取单个字段值
     */
    public function getField( $condition, $field = 'id' )
    {
        return $this->model->where( $condition )->value( $field );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
