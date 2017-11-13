<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\TranslatorRepository;
use App\Models\Translator;
use App\Repositories\Validators\TranslatorValidator;
use DB;

/**
 * Class LanguageRepositoryEloquent
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
    public function getLanguageList($start,$length,$search,$order)
    {
        $language = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $language = $language->where('name', 'like', "%{$search['value']}%")
                    ->orWhere('slug','like', "%{$search['value']}%")
                    ->orWhere('url','like', "%{$search['value']}%");
            }else{
                $language = $language->where('name', $search['value']);
            }
        }
        if ( isset( $search['project_id'] ) )
        {
            $language = $language->where( 'project_id', $search['project_id'] );
        }

        $count = $language->count();

        $language = $language->orderBy($order['name'], $order['dir']);

        $languages = $language->offset($start)->limit($length)->get();

        return compact('count','languages');
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

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
