<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\KeyRepositoryEloquent;
use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
* Key Service
*/
class KeyService extends BaseService
{

    protected $keyRepository;

    public function __construct( KeyRepositoryEloquent $keyRepository )
    {
        $this->keyRepository = $keyRepository;
    }

    /**
     * 获取 key 和 源语言 列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-10
     * @param  [param]
     * @param  [type]     $id [description]
     * @return [type]         [description]
     */
    public function getKeyList( $id )
    {
        return $this->keyRepository->getKeyList( $id );
    }

    /**
     * 存储 key + source
     */
    public function storeKey( $id, $data )
    {
        try
        {
            if ( isset( $data['key_id'] ) && $data['key_id'] > 0 )
            {
                /* Update */
                $res = $this->keyRepository->updateKey( $id, $data );
            }
            else
            {
                $data = [
                    'project_id' => $id,
                    'key'        => $data['key'],
                    'source'     => $data['source'],
                ];
                $res = $this->keyRepository->create( $data );
            }
            return $res;
        }
        catch ( Exception $e )
        {
            $this->sendSystemErrorMail( env('MAIL_SYSTEMERROR',''), $e );
            return false;
        }
    }

    /**
     * 删除翻译 key
     */
    public function deleteKey( $project_id, $key_id )
    {
        return $this->keyRepository->deleteKey( $project_id, $key_id );
    }

}