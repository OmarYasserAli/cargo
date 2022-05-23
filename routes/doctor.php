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
Route::get('getprofile','ProfileController@getProfile')->name('doctor');
Route::get('getavatar','ProfileController@getAvatar');
Route::post('uploadavatar','ProfileController@uploadAvatar');
Route::post('editprofile','ProfileController@updateDoctoProfile');
//patient history
Route::post('addpatient','PatientHistoryController@addPatient');
Route::get('viewpatient/{id}','PatientHistoryController@viewPatient');
Route::get('allpatient','PatientHistoryController@allPatients');
Route::post('contactus','ContactUSController@contactUs')->name('doctor');
//personal history CRUD
Route::post('addhistory','PersonalHistory@addHistory');
Route::post('deletehistory/{id}','PersonalHistory@deleteHistory');
Route::post('edithistory/{id}','PersonalHistory@editHistory');
Route::get('allhistory','PersonalHistory@allHistory');
//Family history CRUD
Route::post('addfamily','FamilyHistory@addFamily');
Route::post('editfamily/{id}','FamilyHistory@editFamily');
Route::post('deletefamily/{id}','FamilyHistory@deleteFamily');
Route::get('allfamily','FamilyHistory@allHistory');
//travel history CRUD
Route::post('addtravel','TravelHistory@addTravelHistory');
Route::post('edittravel/{id}','TravelHistory@editTravel');
Route::post('deletetravel/{id}','TravelHistory@deleteTravel');
Route::get('alltravel','TravelHistory@allHistory');
//Reports 
Route::post('uploadimages','MedicalReport@uploadImages');
Route::post('deleteimage','MedicalReport@deleteImage');
Route::get('allimages','MedicalReport@allImages');
//general
Route::middleware(['changelanguage'])->group(function () {
    Route::get('emergenc','General@showEmergenc');
    Route::get('firstaid','General@showFirstAid');
});



