<?php
/* 开始翻译路由 */
$router->group( ['prefix' => 'translate'], function ($router)
{
    $router->get( 'ajaxIndex', 'TranslateController@ajaxIndex' )->name( 'translate.ajaxIndex' );
    $router->get( '{id}/start', 'TranslateController@start' );
    $router->post( '{id}/start', 'TranslateController@finish' );
});
$router->resource( 'translate', 'TranslateController' );