<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\LanguageRepository;
use App\Models\Language;
use App\Repositories\Validators\LanguageValidator;
use DB;

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
        $project = $this->model;
        if ($search['value']) {
            if($search['regex'] == 'true'){
                $project = $project->where('name', 'like', "%{$search['value']}%")
                    ->orWhere('slug','like', "%{$search['value']}%")
                    ->orWhere('url','like', "%{$search['value']}%");
            }else{
                $project = $project->where('name', $search['value'])
                    ->orWhere('slug', $search['value'])
                    ->orWhere('url', $search['value']);
            }
        }

        $count = $project->count();

        $project = $project->orderBy($order['name'], $order['dir']);

        $projects = $project->offset($start)->limit($length)->get();

        return compact('count','projects');
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
    public function deleteOtherLanguage( $languages, $id )
    {
        $result = $this->model->where( 'project_id', $id )->whereNotIn( 'language', $languages )->delete();
        return $result;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
