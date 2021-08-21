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
            return response()->json([
                'status' => 200,
                'message' => __('api.email_verified')
            ], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status' => 200,
            'message' => __('api.verification_link_sent')
        ], 200);
    }

    public function verify(Request $request): Json
    {
        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 200,
                'message' => __('api.email_verified')
            ], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'status' => 200,
            'message' => __('api.email_has_verified')
        ], 200);

    }

    public function checkVerification(): Json
    {
        if (!auth()->user()->hasVerifiedEmail()) {
            return response()->json([
                'status' => 401,
                'message' => __('api.email_not_verified')
            ], 401);
        }

        return response()->json([
            'status' => 200,
            'message' => __('api.email_verified')
        ], 200);
    }
}
