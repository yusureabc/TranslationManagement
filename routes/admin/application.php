<?php
/* 应用管理路由 */
$router->group( ['prefix' => 'application'], function ($router)
{
    $router->get( 'ajaxIndex', 'ApplicationController@ajaxIndex' )->name( 'application.ajaxIndex' );
});
$router->resource( 'application', 'ApplicationController' );