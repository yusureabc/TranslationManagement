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
    /* 导入译文 */
    $router->get( '{id}/import', 'TranslateController@import' );
    $router->any( 'import_excel/{id}/{force}', 'TranslateController@importExcel' )->name( 'import.translated' );
    /* 评论 */
    $router->get( '{comment_id}/comment', 'TranslateController@comment' );
    $router->post( '{comment_id}/comment', 'TranslateController@commentStore' );
    /* Flag */
    $router->get( '{comment_id}/flag/{flag}', 'TranslateController@flag' );

});
$router->resource( 'translate', 'TranslateController' );