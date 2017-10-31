<?php
$router->group(['prefix' => 'platform'],function ($router)
{
	$router->get('ajaxIndex','PlatformController@ajaxIndex')->name('platform.ajaxIndex');
});
$router->resource('platform','PlatformController');