<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\ApiAuthMiddleware;

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

// Test routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/tests/{name}', function ($name = null) {
    return view('tests', array(
        'text' => $name
    ));
});

Route::get('/animals', 'PruebasController@index');
Route::get('/test-orm', 'PruebasController@testOrm');


// =========================================
// ===             API routes            ===
// =========================================

/*
 * Common methods HTTP
 *
 * GET: Get data or resources
 * POST: Save data or resources
 * PUT: Update data or resources
 * DELETE: Remover data or resources
 *
 */

// UserController routes
Route::post('/api/register', 'UserController@register');
Route::post('/api/login', 'UserController@login');
Route::put('/api/user/update', 'UserController@update');
Route::post('/api/user/upload', 'UserController@upload')->middleware(ApiAuthMiddleware::class);
Route::get('/api/user/avatar/{filename}', 'UserController@getFile');
Route::get('/api/user/detail/{id}', 'UserController@detail');

// CategoryController routes
Route::resource('/api/category', 'CategoryController');

// PostController routes
Route::resource('/api/post', 'PostController');
Route::post('/api/post/upload', 'PostController@upload');
Route::get('/api/post/image/{filename}', 'PostController@getFile');
Route::get('/api/post/category/{id}', 'PostController@getPostByCategory');
Route::get('/api/post/user/{id}', 'PostController@getPostByUser');
