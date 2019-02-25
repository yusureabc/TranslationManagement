<?php

namespace App\Repositories\Eloquent;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\KeyRepository;
use App\Models\Key;
use DB;

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
        return $this->model->where( 'project_id', $project_id )->orderBy( 'sort', 'asc')->orderBy( 'id', 'asc')->get();
    }

    /**
     * 更新 key + source
     */
    public function updateKey( $project_id, $data )
    {
        $condition = ['id' => $data['key_id'], 'project_id' => $project_id ];
        $update = ['key' => trim( $data['key'] ), 'source' => $data['source'], 'tag' => $data['tag']];
        return $this->model->where( $condition )->update( $update );
    }

    /**
     * 更新 tag
     */
    public function updateTag( $key_id, $tag )
    {
        $condition = ['id' => $key_id];
        $update = ['tag' => $tag];
        return $this->model->where( $condition )->update( $update );
    }

    /**
     * 更新 LengthType
     */
    public function updateLength( $key_id, $length )
    {
        $condition = ['id' => $key_id];
        $update = ['length' => $length];

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
     * 批量更新表的值，防止阻塞
     * @note 生成的SQL语句如下：
     * update `keys` set sort = case id
     *      when 13 then 1
     *      when 1 then 4
     *      when 7 then 5
     *      when 8 then 6
     *      when 9 then 7
     *      when 10 then 8
     *      when 11 then 9
     *      when 12 then 10
     * end where id in (13,1,7,8,9,10,11,12)
     * @param $conditions_field 条件字段
     * @param $values_field  需要被更新的字段
     * @param $conditions
     * @param $values
     * @return int
     */
    public function batchUpdate($conditions_field, $values_field, $conditions, $values)
    {
        $table = $this->model->getFullTableName(); // 返回完整表名
        $sql   = 'update ' . '`' . $table . '`' . ' set '. $values_field .' = case ' .$conditions_field;
        foreach ($conditions as $key => $condition) {
            $sql .= ' when ' . $condition . ' then ?';
        }
        $sql .= ' end where id in (' . implode(',', $conditions) . ')';
        return DB::update($sql, $values);//项目中需要引入DB  facade
    }

    /**
     * 获取源内容
     */
    public function getSourceContents( $project_id )
    {
        return $this->model->where( 'project_id', $project_id )
               ->select( 'id as key_id', 'source as content', 'key', 'length' )
               ->orderBy( 'sort', 'asc' )
               ->orderBy( 'id', 'asc' )
               ->get();
    }

    /**
     * 获取 key 和 译文
     */
    public function getTranslatedList( $project_id, $language_id, $method )
    {
        switch ( $method )
        {
            case 'xml':
                $tag = [1, 2];
            break;

            case 'iOS_strings':
            case 'iOS_js':
                $tag = [1, 3];
            break;

            default:
                $tag = [1];
            break;
        }
        $condition = ['keys.project_id' => $project_id, 'contents.language_id' => $language_id];
        return $this->model->where( $condition )->whereIn( 'keys.tag', $tag )
                ->join( 'contents', 'keys.id', '=', 'contents.key_id' )
                ->orderBy( 'keys.sort', 'asc' )->orderBy( 'keys.id', 'asc' )->get();
    }

    /**
     * 获取 基础语言
     * @author Yusure  http://yusure.cn
     * @date   2017-12-08
     * @param  [param]
     * @return [type]     [description]
     */
    public function getBaseList( $project_id, $language_id, $method )
    {
        $condition['project_id'] = $project_id;
        switch ( $method )
        {
            case 'xml':
                $tag = [1, 2];
            break;

            case 'iOS_strings':
            case 'iOS_js':
                $tag = [1, 3];
            break;

            default:
                $tag = [1];
            break;
        }
        return $this->model->where( 'project_id', $project_id )->whereIn( 'tag', $tag )
        ->orderBy( 'sort', 'asc' )->orderBy( 'id', 'asc' )->select( 'project_id', 'key', 'source as content' )->get();
    }

    /**
     * 检查存在的key
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @param  [type]     $project_id [description]
     * @param  array      $keys       [description]
     * @return [type]                 [description]
     */
    public function keyExist( $project_id, $keys = [] )
    {
        return $this->model->where( 'project_id', $project_id )->whereIn( 'key', $keys )->get();
    }

    /**
     * 批量写入key
     * @author Yusure  http://yusure.cn
     * @date   2017-11-20
     * @param  [param]
     * @return [type]     [description]
     */
    public function batchInsertKey( $data )
    {
        return $this->model->insert( $data );
    }

    /**
     * 获取一行信息
     */
    public function getInfo( $condition )
    {
        return $this->model->where( $condition )->first();
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
