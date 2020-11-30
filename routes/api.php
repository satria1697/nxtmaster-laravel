<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Create New User
        Route::post('register', 'App\Http\Controllers\Admin\AuthController@register');
        // Login User
        Route::post('login', 'App\Http\Controllers\Admin\AuthController@login');

        // Refresh the JWT Token
        Route::get('refresh', 'App\Http\Controllers\Admin\AuthController@refresh');

        Route::get('akses', 'App\Http\Controllers\Admin\AuthController@GetAkses');

        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', 'App\Http\Controllers\Admin\AuthController@user');
            // Logout user from application
            Route::post('logout', 'App\Http\Controllers\Admin\AuthController@logout');
            Route::post('loginconfirmation', 'App\Http\Controllers\Admin\AuthController@loginconfirm');
            Route::prefix('user')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\AuthController@index');
                Route::get('{id}', 'App\Http\Controllers\Admin\AuthController@show');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\AuthController@delete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\AuthController@update');
            });
            Route::prefix('structure')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\StructureController@Data');
                Route::get('{id}', 'App\Http\Controllers\Admin\StructureController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\StructureController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\StructureController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\StructureController@Register');
            });

            Route::prefix('application')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\ApplicationController@Data');
                Route::get('{id}', 'App\Http\Controllers\Admin\ApplicationController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\ApplicationController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\ApplicationController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\ApplicationController@Register');
            });
            Route::prefix('modul')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\ModulController@Data');
                Route::get('data/app', 'App\Http\Controllers\Admin\ModulController@DataApp');
                Route::get('{id}', 'App\Http\Controllers\Admin\ModulController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\ModulController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\ModulController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\ModulController@Register');
            });
            Route::prefix('aksesmanager')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\AksesManagerController@Data');
                Route::get('data/root', 'App\Http\Controllers\Admin\AksesManagerController@DataRoot');
                Route::get('data/root/{id}', 'App\Http\Controllers\Admin\AksesManagerController@DataRootId');
                Route::get('data/parent', 'App\Http\Controllers\Admin\AksesManagerController@DataParent');
                Route::get('data/child', 'App\Http\Controllers\Admin\AksesManagerController@DataChild');
                Route::get('{id}', 'App\Http\Controllers\Admin\AksesManagerController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\AksesManagerController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\AksesManagerController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\AksesManagerController@Register');
                Route::get('data/child/{id}', 'App\Http\Controllers\Admin\AksesManagerController@DataChildId');
            });
            Route::prefix('akses')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\AksesController@Data');
                Route::get('{id}', 'App\Http\Controllers\Admin\AksesController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\AksesController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\AksesController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\AksesController@Register');
            });
            Route::prefix('rolelevel')->group(function () {
                Route::get('data', 'App\Http\Controllers\Admin\RoleLevelController@Data');
            });
            Route::prefix('option')->group(function (){
                Route::get('data', 'App\Http\Controllers\Admin\OptionController@Data');
                Route::get('{id}', 'App\Http\Controllers\Admin\OptionController@DataId');
                Route::delete('delete/{id}', 'App\Http\Controllers\Admin\OptionController@DataDelete');
                Route::post('update/{id}', 'App\Http\Controllers\Admin\OptionController@Update');
                Route::post('register', 'App\Http\Controllers\Admin\OptionController@Register');
            });
        });
    });
});
Route::middleware('auth:api')->group(function () {
    Route::prefix('admin')->group(function() {
        Route::prefix('level')->group(function (){
            Route::get('data', 'App\Http\Controllers\Admin\LevelController@index');
            Route::get('{id}', 'App\Http\Controllers\Admin\LevelController@show');
            Route::delete('delete/{id}', 'App\Http\Controllers\Admin\LevelController@delete');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\LevelController@update');
            Route::post('register', 'App\Http\Controllers\Admin\LevelController@store');
        });
        Route::prefix('rank')->group(function () {
            Route::get('data', 'App\Http\Controllers\Admin\RankController@index');
            Route::get('{id}', 'App\Http\Controllers\Admin\RankController@show');
            Route::delete('delete/{id}', 'App\Http\Controllers\Admin\RankController@delete');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\RankController@update');
            Route::post('register', 'App\Http\Controllers\Admin\RankController@store');
        });
        Route::prefix('structurelevel')->group(function (){
            Route::get('data', 'App\Http\Controllers\Admin\StructureLevelController@index');
            Route::get('{id}', 'App\Http\Controllers\Admin\StructureLevelController@show');
            Route::delete('delete/{id}', 'App\Http\Controllers\Admin\StructureLevelController@delete');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\StructureLevelController@update');
            Route::post('register', 'App\Http\Controllers\Admin\StructureLevelController@store');
        });
        Route::prefix('aksesuser')->group(function () {
            Route::get('data', 'App\Http\Controllers\Admin\AksesUserController@index');
            Route::get('{id}', 'App\Http\Controllers\Admin\AksesUserController@show');
            Route::delete('delete/{id}', 'App\Http\Controllers\Admin\AksesUserController@delete');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\AksesUserController@update');
            Route::post('register', 'App\Http\Controllers\Admin\AksesUserController@store');
        });
        Route::prefix('akses')->group(function (){
            Route::get('data', 'App\Http\Controllers\Admin\AksesController@index');
            Route::get('{id}', 'App\Http\Controllers\Admin\AksesController@show');
            Route::delete('delete/{id}', 'App\Http\Controllers\Admin\AksesController@delete');
            Route::post('update/{id}', 'App\Http\Controllers\Admin\AksesController@update');
            Route::post('register', 'App\Http\Controllers\Admin\AksesController@register');
        });
    });
});

