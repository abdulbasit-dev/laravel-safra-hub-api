<?php


use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PicnicController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Models\User;
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



Route::group(['middleware' => 'localization'], function () {
    //PROTECTED ROUTES
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
        Route::post('email/check-verification', [EmailVerificationController::class, 'checkVerification']);
        Route::group(['namespace' => 'Api'], function () {
            Route::resource('user-favorate-items', \App\Http\Controllers\Api\UserFavItemController::class)->except('create', 'edit', 'update');
            Route::get('/user-profiles', [UserProfileController::class, 'index']);
            Route::get('/user-profiles/{user}', [UserProfileController::class, 'userProfileById']);
            Route::put('/user-profiles', [UserProfileController::class, 'update']);
            Route::get('/user-profiles/friends', [UserProfileController::class, 'userFriends']);
        });
    });


    //PUBLIC ROUTES
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
    Route::get('/all-user', function () {
        return User::orderByDesc('id')->get();
    });
    Route::get('test', function () {
        //generate uniq code
        // return str::upper(Str::random(6));
        return 'test';
    });

    Route::group(['namespace' => 'Api'], function () {
        Route::resource('picnics', PicnicController::class)->except('create', 'edit');
        Route::resource('categories', CategoryController::class)->except('create', 'edit');
    });

});
