<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\TranslatorRepository;
use App\Models\Translator;
use App\Repositories\Validators\TranslatorValidator;
use DB;

/**
 * Class TranslatorRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class TranslatorRepositoryEloquent extends BaseRepository implements TranslatorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Translator::class;
    }

    /**
     * 写入多条
     */
    public function insert( $data )
    {
        $language = $this->model;
        return $language->insert( $data );
    }

    /**
     * 查询并分页
     * @param  [type]                   $start  [起始数目]
     * @param  [type]                   $length [读取条数]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @return [type]                           [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getTranslateList($start,$length,$search,$order)
    {
        $translator = $this->model;
        if ( $search['value'] ) 
        {
            if ( $search['regex'] == 'true' )
            {
                $translator = $translator->where('project_name', 'like', "%{$search['value']}%");
            }
            else
            {
                $translator = $translator->where( 'project_name', $search['value'] );
            }
        }
        if ( isset( $search['user_id'] ) )
        {
            $translator = $translator->where( 'user_id', $search['user_id'] );
        }

        $count = $translator->count();

        $translator = $translator->orderBy($order['name'], $order['dir']);

        $translators = $translator->offset($start)->limit($length)->get();

        return compact( 'count', 'translators' );
    }

    public function getList( $condition )
    {
        return $this->model->where( $condition )->get();
    }

    /**
     * 获取邀请的翻译者
     */
    public function getInviteUser( $language_id )
    {
        return $this->model->where( 'language_id', $language_id )->pluck( 'user_id' )->toArray();
    }

    /**
     * 删除其他用户
     */
    public function deleteOtherUser( $language_id, $user_id )
    {
        return $this->model->where( 'language_id', $language_id )->whereNotIn( 'user_id', $user_id )->delete();
    }

    public function updateProjectName( $project_name, $project_id )
    {
        return $this->model->where( 'project_id', $project_id )->update( ['project_name' => $project_name] );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
