<?php

namespace App\Service\Admin;

use App\Repositories\Eloquent\ApplicationRepositoryEloquent;
use App\Repositories\Eloquent\ProjectRepositoryEloquent;
use App\Service\Admin\LanguageService;
use App\Models\Project;
use App\Models\Language;
use App\Service\Admin\BaseService;
use Exception;
use DB;
use Illuminate\Support\Facades\Storage;

/**
 * ApplicationService Service
 */
class ApplicationService extends BaseService
{

    protected $applicationRepository;

    function __construct(
        ApplicationRepositoryEloquent $applicationRepository,
        ProjectRepositoryEloquent $projectRepository,
        LanguageService $languageService
    )
    {
        $this->applicationRepository = $applicationRepository;
        $this->projectRepository = $projectRepository;
        $this->languageService = $languageService;
    }

    /**
     * datatables获取数据
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

        $result = $this->applicationRepository->getApplicationList($start,$length,$search,$order);

        $apps = [];

        if ( $result['apps'] ) {
            foreach ( $result['apps'] as $v ) {
                $v->actionButton = $v->getActionButtonAttribute( false );
                $apps[] = $v;
            }
        }

        return [
            'draw' => $draw,
            'recordsTotal' => $result['count'],
            'recordsFiltered' => $result['count'],
            'data' => $apps,
        ];
    }

    /**
     * 添加应用
     */
    public function storeApplication( $attributes )
    {
        try {
            $result = $this->applicationRepository->create( $attributes );
            flash_info( $result, trans('admin/alert.common.create_success'), trans('admin/alert.common.create_error') );

            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.common.create_success'):trans('admin/alert.common.create_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 获取所有应用
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-17
     * @param  [param]
     * @return [type]     [description]
     */
    public function getApps()
    {
        return $this->applicationRepository->get();
    }

    /**
     * 根据ID查找数据
     */
    public function findApplicationById( $id )
    {
        $application = $this->applicationRepository->find( $id );
        /* 查找 language 数据 */
        if ( $application )
        {
            return $application;
        }

        abort(404);
    }

    /**
     * 修改数据
     */
    public function updateApplication( $attributes, $id )
    {
        // 防止用户恶意修改表单id，如果id不一致直接跳转500
        if ( $attributes['id'] != $id )
        {
            return [
                'status' => false,
                'message' => trans('admin/errors.user_error'),
            ];
        }
        try {
            $result = $this->applicationRepository->update( $attributes, $id );

            flash_info( $result, trans('admin/alert.common.edit_success'), trans('admin/alert.common.edit_error') );
            return [
                'status' => $result,
                'message' => $result ? trans('admin/alert.common.edit_success') : trans('admin/alert.common.edit_error'),
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 删除
     */
    public function destroy( $id )
    {
        try {
            $isDestroy = $this->applicationRepository->delete( $id );
            flash_info($isDestroy,trans('admin/alert.common.destroy_success'),trans('admin/alert.common.destroy_error'));
            return $isDestroy;
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    public function orderable($nestableData)
    {
        try {
            $dataArray = json_decode($nestableData,true);
            $bool = false;
            DB::beginTransaction();
            foreach ($dataArray as $k => $v) {
                $this->project->update(['sort' => $v['sort']],$v['id']);
                $bool = true;
            }
            DB::commit();
            if ($bool) {
                // 更新缓存
                $this->getProjectSetCache();
            }
            return [
                'status' => $bool,
                'message' => $bool ? trans('admin/alert.project.order_success'):trans('admin/alert.project.order_error')
            ];
        } catch (Exception $e) {
            // 错误信息发送邮件
            DB::rollBack();
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }

    /**
     * 下载导出的译文
     *
     * 需要 language_code 做 key，来生成 译文 文件
     */
    public function downloadFile( $id, $type )
    {
        /* 根据 app_id 查出相关的 project_id */
        $projects = Project::where( ['app_id' => $id] )->pluck( 'id' );
        if ( $projects->isEmpty() )  return false;

        /* 用 project_id 找到 languages */
        $language_code = Project::whereIn( 'id', $projects->toArray() )->pluck( 'languages', 'id' );

        /* project_id + language_code  获取 language_id */
        $result = [];
        foreach ( $language_code as $project_id => $item )
        {
            $item_codes = explode( ',', $item );
            $languages = Language::where( ['project_id' => $project_id] )->whereIn( 'language', $item_codes )->pluck( 'language', 'id' );
            foreach ( $languages as $language_id => $code )
            {
                /* language_id + type 获取译文 */
                $contents = $this->languageService->getTranslateData( $language_id, $type );
                if ( $contents->isNotEmpty() )
                {
                    $contents = $contents->toArray();

                    /* 转成 project_id 为 key 的二位数组 */
                    $trans_res = $this->_transformContents( $contents );
                    $result[ $code ] = isset( $result[ $code ] ) ? $result[ $code ] + $trans_res : $trans_res;
                }
            }
        }
        $zip_filename = $this->_generateCompressedFile( $id, $type, $result );        

        return $zip_filename;
    }

    /**
     * 转成 project_id 为 key 的二位数组
     */
    private function _transformContents( $contents )
    {
        $temp = [];
        foreach ( $contents as $content )
        {
            $temp[ $content['project_id'] ][] = $content;
        }

        return $temp;
    }

    /**
     * 生成文件路径
     * @author Sure Yu  http://yusure.cn
     * @date   2018-07-20
     * @param  [param]
     * @return [type]     [description]
     */
    private function _filePath( $id, $type, $code )
    {
        switch ( $type )
        {
            case 'xml':
                $files[] = 'values-' . $code . '/strings.xml';
            break;

            case 'iOS_strings':
                /* 转成 iOS 的项目目录，一个语言 code 可能有两个不同目录，但译文一样 */
                $name_mapping = [
                    'en'    => 'en,Base',
                    'zh'    => 'zh-Hans',
                    'zh-HK' => 'zh-Hant-HK',
                    'zh-TW' => 'zh-Hant,zh-Hant-TW',
                ];
                $name = $name_mapping[ $code ] ?? $code;
                $name = explode( ',', $name );
                foreach ( $name as $value )
                {
                    $files[] = $value . '.lproj' . '/Localizable.strings';
                }
            break;

            case 'iOS_js':
                $files[] = $code . '.js';
            break;
        }

        return $files;
    }

    /**
     * 生成压缩文件
     */
    private function _generateCompressedFile( $id, $type, $result )
    {
        if ( empty( $result ) )  return;
        $zip = new \ZipArchive();
        $zip_filename = storage_path( "app/{$type}_{$id}.zip" );
        if ( file_exists( $zip_filename ) )
        {
            unlink( $zip_filename );
        }
        $res = $zip->open( $zip_filename, \ZipArchive::CREATE );

        /* 将译文写入 zip 文件 */
        foreach ( $result as $code => $contents )
        {
            $format = $this->languageService->formatTranslation( $contents, $type );
            $files = $this->_filePath( $id, $type, $code );
            foreach ( $files as $file )
            {
                $zip->addFromString( $file, $format );
            }
        }
        $zip->close();
        
        return $zip_filename;
    }
}