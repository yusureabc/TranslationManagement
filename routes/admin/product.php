<?php
$router->group(['prefix' => 'product'],function ($router)
{
	$router->get('ajaxIndex','ProductController@ajaxIndex')->name('product.ajaxIndex');
});
$router->resource('product','ProductController');