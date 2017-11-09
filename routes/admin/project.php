<?php
/* 项目管理路由 */
$router->group( ['prefix' => 'project'], function ($router)
{
    $router->get( 'ajaxIndex', 'ProjectController@ajaxIndex' )->name( 'project.ajaxIndex' );
    $router->get( '{id}/input', 'ProjectController@input' );
});
$router->resource( 'project', 'ProjectController' );