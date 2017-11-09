<?php
/* 多语言路由 */
$router->group( ['prefix' => 'language'], function ($router)
{
    $router->get( 'ajaxIndex', 'LanguageController@ajaxIndex' )->name( 'language.ajaxIndex' );
    $router->get( '{id}/status/{status}', 'LanguageController@status' );
});
$router->resource( 'language', 'LanguageController' );