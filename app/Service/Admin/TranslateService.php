<?php
namespace App\Service\Admin;

use App\Repositories\Eloquent\TranslatorRepositoryEloquent;

use App\Service\Admin\BaseService;
use Exception;
use DB;

/**
* Translate Service
*/
class TranslateService extends BaseService
{

    protected $translateRepository;

    public function __construct( TranslatorRepositoryEloquent $translateRepository )
    {
        $this->translateRepository = $translateRepository;
    }

    /**
     * datatables获取数据
     * @author Sheldon
     * @date   2017-04-18T15:54:46+0800
     * @return [type]                   [description]
     */
    public function ajaxIndex()
    {
        // datatables请求次数
        $draw = request('draw', 1);
        // 开始条数
        $start = request('start', config('admin.golbal.list.start'));
        // 每页显示数目
        $length = request('length', config('admin.golbal.list.length'));
        // datatables是否启用模糊搜索
        $search['regex'] = request('search.regex', false);
        // 搜索框中的值
        $search['value'] = request('search.value', '');
        // 排序
        $order['name'] = request('columns.' .request('order.0.column',0) . '.name');
        $order['dir'] = request('order.0.dir','asc');
        $search['user_id'] = getUser()->id;

        $result = $this->translateRepository->getTranslateList($start,$length,$search,$order);

        $translators = [];

        if ( $result['translators'] )
        {
            foreach ( $result['translators'] as $v )
            {
                $v->status = $v->language->status == 0 ? trans( 'admin/action.lock' ) : trans( 'admin/action.open' );
                $v->language_name = trans( 'languages.'.$v->language_code );
                $v->actionButton = $v->getActionButtonAttribute();
                $translators[] = $v;
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['count'],
            'recordsFiltered' => $result['count'],
            'data' => $translators,
        ];
    }

    /**
     * 获取待翻译列表
     * @author Yusure  http://yusure.cn
     * @date   2017-11-13
     * @param  [param]
     * @param  [type]     $user_id [description]
     * @return [type]              [description]
     */
    public function getTranslateList( $user_id )
    {
        return $this->translateRepository->getTranslateList( $user_id );
    }

}