<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ContentRepository;
use App\Models\Content;

/**
 * Class ContentRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ContentRepositoryEloquent extends BaseRepository implements ContentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Content::class;
    }

    /**
     * 判断译文是否存在
     */
    public function translated_exist( $language_id, $key_id )
    {
        $condition = ['language_id' => $language_id, 'key_id' => $key_id];
        return $this->model->where( $condition )->value( 'id' );
    }

    /**
     * 更新译文
     * @author Yusure  http://yusure.cn
     * @date   2017-11-14
     * @param  [param]
     * @return [type]     [description]
     */
    public function update_content( $language_id, $key_id, $content )
    {
        $condition = ['language_id' => $language_id, 'key_id' => $key_id];
        $data = ['content' => $content];
        return $this->model->where( $condition )->update( $data );
    }

    /**
     * 获取 翻译者 的译文
     */
    public function getTranslatedContents( $id )
    {
        return $this->model->where( 'language_id', $id )->pluck( 'content', 'key_id' )->toArray();
    }

    /**
     * 获取源对照内容
     */
    public function getSourceContents( $id )
    {
        return $this->model->where( 'language_id', $id )->select( 'key_id', 'content' )->get();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
