<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ApplicationRepository;
use App\Models\Application;

/**
 * Class ApplicationRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ApplicationRepositoryEloquent extends BaseRepository implements ApplicationRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Application::class;
    }

    /**
     * 查询平台并分页
     * @param  [type]  $start  [起始数目]
     * @param  [type]  $length [读取条数]
     * @param  [type]  $search [搜索数组数据]
     * @param  [type]  $order  [排序数组数据]
     * @return [type]          [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getApplicationList( $start, $length, $search, $order )
    {
        $application = $this->model;
        if ( $search['value'] ) {
            if ( $search['regex'] == 'true' ) {
                $application = $application->where('name', 'like', "%{$search['value']}%")
                    ->orWhere('description','like', "%{$search['value']}%");
            } else {
                $application = $application->where('name', $search['value'])
                    ->orWhere('description', $search['value']);
            }
        }

        $count = $application->count();

        $application = $application->orderBy($order['name'], $order['dir']);

        $apps = $application->offset($start)->limit($length)->get();

        return compact( 'count', 'apps' );
    }

    public function allProjects()
    {
        return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createProject($attributes)
    {
        $model = new $this->model;
        return $model->fill($attributes)->save();
    }

    public function getProjectLanguage( $id )
    {
        $result = [];
        $languages = $this->model->where( 'id', $id )->value( 'languages' );
        if ( $languages )
        {
            $result = explode( ',', $languages );
        }
        
        return $result;
    }

    public function getProjectName( $id )
    {
        return $this->model->where( 'id', $id )->value( 'name' );
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
