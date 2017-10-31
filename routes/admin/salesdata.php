<?php
$router->group(['prefix' => 'salesdata'],function ($router)
{
	$router->get('ajaxIndex','SalesdataController@ajaxIndex')->name('salesdata.ajaxIndex');
    $router->get('charts','SalesdataController@charts')->name('salesdata.charts');
    $router->get('transexcel','TransexcelController@import')->name('salesdata.transexcel');
    $router->post('transexcel-export','TransexcelController@export')->name('salesdata.transexcel-export');
});
$router->resource('salesdata','SalesdataController');