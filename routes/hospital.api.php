<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::prefix('hospital')->group(function () {
        Route::prefix('perawat')->group(function() {
            Route::get('data', 'PerawatController@index');
            Route::get('{id}', 'PerawatController@show');
            Route::delete('delete/{id}', 'PerawatController@delete');
            Route::post('update/{id}', 'PerawatController@update');
            Route::post('register', 'PerawatController@store');
        });
        Route::prefix('dokter')->group(function() {
            Route::get('data', 'DokterController@index');
            Route::get('{id}', 'DokterController@show');
            Route::delete('delete/{id}', 'DokterController@delete');
            Route::post('update/{id}', 'DokterController@update');
            Route::post('register', 'DokterController@store');
        });
        Route::prefix('spesialisasi')->group(function() {
            Route::get('data', 'SpesialisasiController@index');
            Route::get('{id}', 'SpesialisasiController@show');
            Route::delete('delete/{id}', 'SpesialisasiController@delete');
            Route::post('update/{id}', 'SpesialisasiController@update');
            Route::post('register', 'SpesialisasiController@store');
        });
        Route::prefix('pasien')->group(function() {
            Route::get('data', 'PasienController@index');
            Route::get('{id}', 'PasienController@show');
            Route::delete('delete/{id}', 'PasienController@delete');
            Route::post('update/{id}', 'PasienController@update');
            Route::post('register', 'PasienController@store');
        });
        Route::prefix('kelasrawatinap')->group(function() {
            Route::get('data', 'KelasrawatinapController@index');
            Route::get('{id}', 'KelasrawatinapController@show');
            Route::delete('delete/{id}', 'KelasrawatinapController@delete');
            Route::post('update/{id}', 'KelasrawatinapController@update');
            Route::post('register', 'KelasrawatinapController@store');
        });
        Route::prefix('bangsal')->group(function() {
            Route::get('data', 'BangsalController@index');
            Route::get('{id}', 'BangsalController@show');
            Route::delete('delete/{id}', 'BangsalController@delete');
            Route::post('update/{id}', 'BangsalController@update');
            Route::post('register', 'BangsalController@store');
        });
        Route::prefix('kamarrawatinap')->group(function() {
            Route::get('data', 'KamarrawatinapController@index');
            Route::get('{id}', 'KamarrawatinapController@show');
            Route::delete('delete/{id}', 'KamarrawatinapController@delete');
            Route::post('update/{id}', 'KamarrawatinapController@update');
            Route::post('register', 'KamarrawatinapController@store');
        });
        Route::prefix('rawatinap')->group(function() {
            Route::get('data', 'RawatinapController@index');
            Route::get('{id}', 'RawatinapController@show');
            Route::delete('delete/{id}', 'RawatinapController@delete');
            Route::post('update/{id}', 'RawatinapController@update');
            Route::post('register', 'RawatinapController@store');
        });
        Route::prefix('icd10')->group(function() {
            Route::get('data', 'IcdController@index');
            Route::get('{id}', 'IcdController@show');
            Route::delete('delete/{id}', 'IcdController@delete');
            Route::post('update/{id}', 'IcdController@update');
            Route::post('register', 'IcdController@store');
        });
        Route::prefix('diagnosisrawatinap')->group(function() {
            Route::get('data', 'DiagnosisrawatinapController@index');
            Route::get('{id}', 'DiagnosisrawatinapController@show');
            Route::delete('delete/{id}', 'DiagnosisrawatinapController@delete');
            Route::post('update/{id}', 'DiagnosisrawatinapController@update');
            Route::post('register', 'DiagnosisrawatinapController@store');
        });
        Route::prefix('wilayah')->group(function() {
            Route::get('data', 'WilayahController@index');
            Route::get('{id}', 'WilayahController@show');
            Route::delete('delete/{id}', 'WilayahController@delete');
            Route::post('update/{id}', 'WilayahController@update');
            Route::post('register', 'WilayahController@store');
        });
        Route::prefix('agama')->group(function() {
            Route::get('data', 'AgamaController@index');
            Route::get('{id}', 'AgamaController@show');
            Route::delete('delete/{id}', 'AgamaController@delete');
            Route::post('update/{id}', 'AgamaController@update');
            Route::post('register', 'AgamaController@store');
        });
        Route::prefix('pendidikan')->group(function() {
            Route::get('data', 'PendidikanController@index');
            Route::get('{id}', 'PendidikanController@show');
            Route::delete('delete/{id}', 'PendidikanController@delete');
            Route::post('update/{id}', 'PendidikanController@update');
            Route::post('register', 'PendidikanController@store');
        });
        Route::prefix('pekerjaan')->group(function() {
            Route::get('data', 'PekerjaanController@index');
            Route::get('{id}', 'PekerjaanController@show');
            Route::delete('delete/{id}', 'PekerjaanController@delete');
            Route::post('update/{id}', 'PekerjaanController@update');
            Route::post('register', 'PekerjaanController@store');
        });
    });
});
