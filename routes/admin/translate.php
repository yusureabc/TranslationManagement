<?php
/* 开始翻译路由 */
$router->group( ['prefix' => 'translate'], function ($router)
{
    $router->get( 'ajaxIndex', 'TranslateController@ajaxIndex' )->name( 'translate.ajaxIndex' );
    $router->get( '{id}/start', 'TranslateController@start' )->middleware( 'language.status' );
    /* 完成翻译 */
    $router->post( '{id}/finish', 'TranslateController@finish' )->name( 'translate.finish' );
    /* 存储译文 */
    $router->patch( '{id}/start', 'TranslateController@store' );
});
$router->resource( 'translate', 'TranslateController' );