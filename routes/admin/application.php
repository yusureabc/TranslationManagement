<?php
/* 应用管理路由 */
$router->group( ['prefix' => 'application'], function ($router)
{
    $router->get( 'ajaxIndex', 'ApplicationController@ajaxIndex' )->name( 'application.ajaxIndex' );
    /* 下载翻译页面 */
    $router->get( '{id}/download', 'ApplicationController@download' )->name( 'application.download' );
    $router->get( '{id}/download/{type}', 'ApplicationController@downloadFile' )->name( 'application.downloadFile' );
});
$router->resource( 'application', 'ApplicationController' );