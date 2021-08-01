<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserProfileController;
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



//PROTECTED ROUTES
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::group(['namespace' => 'Api'], function () {
        Route::resource('user-favorate-items', UserFavItemController::class)->except('create', 'edit', 'update');
        Route::get('/user-profiles', [UserProfileController::class, 'index']);
        Route::put('/user-profiles', [UserProfileController::class, 'update']);
        Route::put('/user-profiles/update-image', [UserProfileController::class, 'updateImage']);
    });
});

//PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['namespace' => 'Api'], function () {
    Route::resource('picnics', PicnicController::class)->except('create', 'edit');
    Route::resource('categories', CategoryController::class)->except('create', 'edit');
});
