<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', [
    'uses' => 'HomeController@index'
])->name('main_listing');

Route::get('/product/{id}', [
    'uses' => 'HomeController@get'
])->name('get_product');

Route::post('/search', [
    'uses' => 'HomeController@find'
])->name('find');
