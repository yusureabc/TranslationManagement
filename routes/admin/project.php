<?php
/* 项目管理路由 */
$router->group( ['prefix' => 'project'], function ($router)
{
    $router->get( 'ajaxIndex', 'ProjectController@ajaxIndex' )->name( 'project.ajaxIndex' );
    $router->get( '{id}/input', 'ProjectController@input' );
    $router->post( '{id}/input', 'ProjectController@storeKey' );
    $router->delete( '{id}/input', 'ProjectController@deleteKey' );
});
$router->resource( 'project', 'ProjectController' );