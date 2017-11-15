<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\LanguageRepository;
use App\Models\Language;
use App\Repositories\Validators\LanguageValidator;
use DB;
use Carbon\Carbon;

/**
 * Class LanguageRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class LanguageRepositoryEloquent extends BaseRepository implements LanguageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Language::class;
    }

    /**
     * 写入待翻译语言条目
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

    public function allLanguages()
    {
        return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createLanguage($attributes)
    {
        $model = new $this->model;
        return $model->fill($attributes)->save();
    }

    public function getOldLanguage( $id )
    {
        $result = [];
        $languages = $this->model->where( 'project_id', $id )->pluck( 'language' );
        if ( $languages->isNotEmpty() )
        {
            $result = $languages->toArray();
        }
        
        return $result;
    }

    /**
     * 删除其他语言
     */
    public function deleteOtherLanguage( $languages, $project_id )
    {
        return $this->model->where( 'project_id', $project_id )->whereNotIn( 'language', $languages )->delete();
    }

    public function showLanguageList( $project_id )
    {
        return $this->model->where( 'project_id', $project_id )->get();
    }

    /**
     * 改变状态
     */
    public function changeStatus( $id, $data )
    {
        return $this->model->where( 'id', $id )->update( $data );
    }

    public function getStatus( $id )
    {
        return $this->model->where( 'id', $id )->value( 'status' );
    }

    /**
     * 根据 language_id 查找 project_id
     */
    public function findProjectId( $id )
    {
        return $this->model->where( 'id', $id )->value( 'project_id' );
    }

    /**
     * 根据 language_id 查找 language 简称
     */
    public function findLanguageCode( $id )
    {
        return $this->model->where( 'id', $id )->value( 'language' );
    }

    /**
     * 获取 language ID
     */
    public function getLanguageID( $project_id, $language_code )
    {
        $condition = ['project_id' => $project_id, 'language' => $language_code];
        return $this->model->where( $condition )->value( 'id' );
    }

    /**
     * 完成翻译，更新最后的提交时间
     */
    public function finshTranslate( $id )
    {
        $data = ['submit_at' => Carbon::now()];
        return $this->model->where( 'id', $id )->update( $data );
    }

    /**
     * 下载翻译内容，更新时间
     */
    public function downloadTranslate( $id )
    {
        $data = ['download_at' => Carbon::now()];
        return $this->model->where( 'id', $id )->update( $data );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
