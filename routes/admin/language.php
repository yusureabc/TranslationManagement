<?php
/* 多语言路由 */
$router->group( ['prefix' => 'language'], function ($router)
{
    $router->get( 'ajaxIndex', 'LanguageController@ajaxIndex' )->name( 'language.ajaxIndex' );
});
$router->resource( 'language', 'LanguageController' );