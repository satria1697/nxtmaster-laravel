<?php

use Illuminate\Support\Facades\Route;
Route::middleware('auth:api')->group(function () {
    Route::prefix('siska')->group(function () {
        Route::prefix('status')->group(function() {
            Route::get('data', 'StatusController@index');
            Route::get('{id}', 'StatusController@show');
            Route::delete('delete/{id}', 'StatusController@delete');
            Route::post('update/{id}', 'StatusController@update');
            Route::post('register', 'StatusController@store');
        });
        Route::prefix('formulir')->group(function() {
            Route::get('data', 'FormulirController@index');
            Route::get('{id}', 'FormulirController@show');
            Route::delete('delete/{id}', 'FormulirController@delete');
            Route::post('update/{id}', 'FormulirController@update');
            Route::post('register', 'FormulirController@store');
            Route::get('filterdata/data', 'FormulirController@filterdata');
        });
        Route::prefix('analisisoperasi')->group(function() {
            Route::get('data', 'AnalisisoperasiController@index');
            Route::get('{id}', 'AnalisisoperasiController@show');
            Route::delete('delete/{id}', 'AnalisisoperasiController@delete');
            Route::post('update/{id}', 'AnalisisoperasiController@update');
            Route::post('register', 'AnalisisoperasiController@store');
        });
        Route::prefix('analisisrawatinap')->group(function() {
            Route::get('data', 'AnalisisrawatinapController@index');
            Route::get('{id}', 'AnalisisrawatinapController@show');
            Route::delete('delete/{id}', 'AnalisisrawatinapController@delete');
            Route::post('update/{id}', 'AnalisisrawatinapController@update');
            Route::post('register', 'AnalisisrawatinapController@store');
        });
        Route::prefix('formulirdata')->group(function() {
            Route::get('data', 'FormulirDataController@index');
            Route::get('{id}', 'FormulirDataController@show');
            Route::delete('delete/{id}', 'FormulirDataController@delete');
            Route::post('update/{id}', 'FormulirDataController@update');
            Route::post('register', 'FormulirDataController@store');
        });
        Route::prefix('analisisformulir')->group(function() {
            Route::get('data', 'AnalisisFormulirController@index');
            Route::get('{id}', 'AnalisisFormulirController@show');
            Route::delete('delete/{id}', 'AnalisisFormulirController@delete');
            Route::post('update/{id}', 'AnalisisFormulirController@update');
            Route::post('register', 'AnalisisFormulirController@store');
        });
        Route::prefix('analisisdata')->group(function() {
            Route::get('data', 'AnalisisDataController@index');
            Route::get('{id}', 'AnalisisDataController@show');
            Route::delete('delete/{id}', 'AnalisisDataController@delete');
            Route::post('update/{id}', 'AnalisisDataController@update');
            Route::post('register', 'AnalisisDataController@store');
        });
    });
});
