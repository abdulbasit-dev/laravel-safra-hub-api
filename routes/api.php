<?php


use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\EmailVerificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PicnicController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\UserFavItemController;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
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
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    //Email Verify
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::post('email/check-verification', [EmailVerificationController::class, 'checkVerification']);

    Route::resource('user-favorate-items', UserFavItemController::class)->except('create', 'edit', 'update');
    //User Profile
    Route::get('/user-profiles', [UserProfileController::class, 'index']);
    Route::get('/user-profiles/{user}', [UserProfileController::class, 'userProfileById']);
    Route::put('/user-profiles', [UserProfileController::class, 'update']);
    Route::get('/user-profiles/friends', [UserProfileController::class, 'userFriends']);
});

//PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::resource('picnics', PicnicController::class)->except('create', 'edit');
Route::resource('categories', CategoryController::class)->except('create', 'edit');

//TEST ROUTES
Route::get('/all-user', function () {
    return User::orderByDesc('id')->get();
});
Route::get('test', function () {
    //generate uniq code
    // return str::upper(Str::random(6));
    return 'test';
});

//Clear all cache
Route::get('/clearallcache', function () {
    Artisan::call('view:clear');
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('clear-compiled');

    return "ok";
});

