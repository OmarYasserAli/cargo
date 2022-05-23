<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Kutia\Larafirebase\Facades\Larafirebase;



Route::get('/clear-cache',function(){
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    // Artisan::call('jwt:secret');
    return "cache clear";
});
Route::post('register', 'Authentication\register@register');
Route::post('login', 'Authentication\authentication@authenticate')->name('all_user');
Route::post('logout', 'Authentication\authentication@logout');
Route::get('getAllbranch','general\BranchController@allbranch');
Route::get('getAllmantika','general\BranchController@allmantika');
Route::get('getCommerical','general\BranchController@commerical');
Route::post('search_qr','general\BranchController@search_qr');
Route::get('paidship','general\BranchController@paid');
Route::get('unpaidship','general\BranchController@unpaidship');
Route::post('updateship','general\BranchController@updateship');
//client
Route::prefix('client')->group(function () {
    
    Route::get('store_ship','Client\HomeController@Store_shipment');
    Route::post('search_ship','Client\HomeController@search_ship');
    Route::post('addreport','Client\ReportController@Addreport');
    Route::get('allreport','Client\ReportController@allreport');
   
});
Route::prefix('Receiving')->group(function () {
    Route::post('addreport','Receiving\ReportController@Addreport');
    Route::get('allreport','Receiving\ReportController@allreport');
    Route::get('myship','Receiving\HomeController@myShip');
    Route::post('shipToclient','Receiving\HomeController@shipToclient');
    Route::post('search_ship','Receiving\HomeController@search_ship');
    Route::get('all_commeric','Receiving\HomeController@all_commeric');
    Route::post('search_commeric','Receiving\HomeController@search_commeric');
 

});
Route::prefix('sending')->group(function () {
    Route::get('myship','Sending\HomeController@myShip');
    Route::post('search_qr','Sending\HomeController@search_qr');
    Route::post('search_ship','Sending\HomeController@search_ship');
    Route::post('addreport','Sending\ReportController@Addreport');
    Route::get('allreport','Sending\ReportController@allreport');


});

Route::get('home-page','general\HomeController@HomePage');
Route::get('shipments','general\HomeController@shipments');
Route::get('accounting','general\HomeController@accounting');
Route::get('accounting-shipments','general\HomeController@accounting_shipments');


Route::get('get-shipment-delevery','general\HomeController@get_shipment_delevery');
Route::get('getShipmentsByRecNum','general\HomeController@getShipmentsByRecNum');


Route::post('estlm-rag3','general\HomeController@estlm_rag3');
Route::post('tanfez-estlm-rag3','general\HomeController@tanfez_estlm_rag3');
Route::post('estlam','general\HomeController@estlam');
Route::post('estlam-check','general\HomeController@estlam_check');
Route::post('taslim','general\HomeController@taslim');
Route::post('taslim-check','general\HomeController@taslim_check');

Route::post('wasel-goz2e-mandob-taslem','general\HomeController@wasel_goz2e_mandob_taslem');
Route::post('taslem-mandob-taslem','general\HomeController@taslem_mandob_taslem');




//estlam
Route::get('estlam-shipments-count','general\HomeController@estlam_shipments_count');
Route::get('estlam-shipments','general\HomeController@estlam_shipments');
Route::get('estlam-commercial-names','general\HomeController@estlam_commercial_names');












