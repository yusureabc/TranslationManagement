<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\KeyRepository;
use App\Models\Key;

/**
 * Class KeyRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class KeyRepositoryEloquent extends BaseRepository implements KeyRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Key::class;
    }

    /**
     * 获取 key 和 源语言 列表
     */
    public function getKeyList( $project_id )
    {
        return $this->model->where( 'project_id', $project_id )->orderBy( 'id', 'asc')->get();
    }

    /**
     * 更新 key + source
     */
    public function updateKey( $project_id, $data )
    {
        $condition = ['id' => $data['key_id'], 'project_id' => $project_id ];
        $update = ['key' => $data['key'], 'source' => $data['source']];
        return $this->model->where( $condition )->update( $update );
    }

    /**
     * 删除翻译 key
     */
    public function deleteKey( $project_id, $key_id )
    {
        $condition = ['id' => $key_id, 'project_id' => $project_id];
        return $this->model->where( $condition )->delete();
    }

    /**
     * 获取源内容
     */
    public function getSourceContents( $project_id )
    {
        return $this->model->where( 'project_id', $project_id )->select( 'id as key_id', 'source as content' )->get();
    }

    /**
     * 获取 key 和 译文
     */
    public function getTranslatedList( $project_id, $language_id )
    {
        $condition = ['keys.project_id' => $project_id, 'contents.language_id' => $language_id];
        return $this->model->where( $condition )
                ->join( 'contents', 'keys.id', '=', 'contents.key_id' )
                ->orderBy( 'keys.id', 'asc' )->get();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
