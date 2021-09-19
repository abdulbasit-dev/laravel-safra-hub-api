<?php


namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse as Json;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{

    public function sendVerificationEmail(Request $request): Json
    {

        if ($request->user()->hasVerifiedEmail()) {
            return response()->success(200,__('api.email_verified'));
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->success(200,__('api.verification_link_sent'));
    }

    public function verify(Request $request): Json
    {
        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()) {
            return response()->success(200, __('api.email_verified'));
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->success(200, __('api.email_has_verified'));
    }

    public function checkVerification(): Json
    {
        if (!auth()->user()->hasVerifiedEmail()) {
            return response()->error(401,__('api.email_not_verified'));
        }

        return response()->success(200,__('api.email_verified'));
    }
}
