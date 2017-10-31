<?php
namespace App\Service\Admin;

use App\Jobs\SendEmail;
use Route;
use Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
* 基类service
*/
class BaseService
{
	
	public function sendSystemErrorMail($mail,$e)
	{
		$exceptionData = [
			'method' => Route::current()->getActionName(),
			'info' => $e->getMessage(),
			'trace' => $e->getTraceAsString()
		];
		dispatch(new SendEmail($mail,$exceptionData));
	}

    public function uploadImage ($image)
    {
        try {
            $fileName = md5(time().rand(0,10000)). '.' . getUerId() . '.' . $image->getClientOriginalExtension(); //随机名称+用户ID+获取客户的原始名称
            $savePath = 'images/'. date('Ymd'); //存储到指定文件，例如image/.filename public/.filename

            $filePath = $image->storeAs(
                $savePath,  //路径
                $fileName //文件名
            );
            return $filePath;
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
	}

    public function uploadExcel ($excel)
    {
        try {
            $fileName = md5(time().rand(0,10000)). '.' . getUerId() . '.' . $excel->getClientOriginalExtension(); //随机名称+用户ID+获取客户的原始名称
            $savePath = 'excels/'. date('Ymd'); //存储到指定文件，例如image/.filename public/.filename

            $filePath = $excel->storeAs(
                $savePath,  //路径
                $fileName //文件名
            );
            return $filePath;
        } catch (Exception $e) {
            // 错误信息发送邮件
            $this->sendSystemErrorMail(env('MAIL_SYSTEMERROR',''),$e);
            return false;
        }
    }
}