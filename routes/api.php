<?php


use App\Http\Controllers\Api\Auth\{
    AuthController,
    EmailVerificationController,
    ForgetPasswordController
};

use App\Http\Controllers\Api\{
    CategoryController,
    FriendController,
    PicnicController,
    UserFavItemController,
    UserProfileController
};

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


/*##################
  PROTECTED ROUTES
##################*/
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Auth
    Route::post('/register-step2', [AuthController::class, 'registerStep2'])->middleware('verified');
    Route::post('/logout', [AuthController::class, 'logout']);

    //Email Verify
    Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail']);
    Route::post('email/check-verification', [EmailVerificationController::class, 'checkVerification']);
    //reset password
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('errorbag');;

    //forget password
    Route::Post('validate-code',[ForgetPasswordController::class,'validateCode']);
    Route::Post('new-password',[ForgetPasswordController::class,'forgetPassword']);

    Route::resource('user-favorite-items', UserFavItemController::class)->except('create', 'edit', 'update');
    //User Profile
    Route::get('/user-profiles', [UserProfileController::class, 'index']);
    Route::get('/user-profiles/{user}', [UserProfileController::class, 'userProfileById'])->name('user.profile');
    Route::put('/user-profiles', [UserProfileController::class, 'update']);

    //Picnics
    Route::post('/picnics', [PicnicController::class,'store']);
    Route::post('/picnics/{picnic}/add-member',[PicnicController::class,'addMember']);
    Route::get('/picnics/{picnic}',[PicnicController::class,'picnicAdmin']);

    //friends
    Route::get('/user-friends',[FriendController::class,'userFriends']);
    //return both incoming and outgoing request by type parameter
    Route::get('/see-friends-requests',[FriendController::class,'seeFriendReq']);
    Route::get('/outgoing-friends-request',[FriendController::class,'outgoingFriendReq']);
    Route::post('/send-friends-request',[FriendController::class,'sendFriendReq']);
    Route::post('/remove-friends-request',[FriendController::class,'removeFriendReq']);
    Route::post('/response-friends-request',[FriendController::class,'friendReqAction']);
    Route::post('/remove-friends',[FriendController::class,'removeFriends']);
    Route::post('/create-fake-friend-req',[FriendController::class,'fakeFriendReq']);
});

/*##################
    PUBLIC ROUTES
##################*/
Route::post('/register-step1', [AuthController::class, 'registerStep1']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::resource('categories', CategoryController::class)->except('create', 'edit');

//Forget Password
Route::Post('forget-password',[ForgetPasswordController::class,'forgetPassword']);
Route::Post('validate-code',[ForgetPasswordController::class,'validateCode']);
Route::Post('new-password',[ForgetPasswordController::class,'newPassword']);


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

