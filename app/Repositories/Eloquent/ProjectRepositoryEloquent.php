<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\ProjectRepository;
use App\Models\Project;
use App\Repositories\Validators\ProjectValidator;

/**
 * Class ProjectRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class ProjectRepositoryEloquent extends BaseRepository implements ProjectRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
    * Specify Validator class name
    *
    * @return mixed
    */
    /*public function validator()
    {

        return ProjectValidator::class;
    }*/

    /**
     * 查询平台并分页
     * @author Sheldon
     * @date   2017-04-18T12:56:28+0800
     * @param  [type]                   $start  [起始数目]
     * @param  [type]                   $length [读取条数]
     * @param  [type]                   $search [搜索数组数据]
     * @param  [type]                   $order  [排序数组数据]
     * @return [type]                           [查询结果集，包含查询的数量及查询的结果对象]
     */
    public function getProjectList($start,$length,$search,$order)
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

    public function allProjects()
    {
        return $this->model->orderBy('sort','desc')->get()->toArray();
    }

    public function createProject($attributes)
    {
        $model = new $this->model;
        return $model->fill($attributes)->save();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
