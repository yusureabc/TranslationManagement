<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\InviteRepository;
use App\Models\Invite;
use DB;

/**
 * Class InviteRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class InviteRepositoryEloquent extends BaseRepository implements InviteRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Invite::class;
    }

    /**
     * 获取评论
     */
    public function getList( $condition )
    {
        return $this->model->where( $condition )->get();
    }

    /**
     * 获取单条信息
     */
    public function getInfo( $condition )
    {
        return $this->model->where( $condition )->first();
    }

    /**
     * 获取单个字段
     */
    public function getField( $condition, $field = 'user_id' )
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
