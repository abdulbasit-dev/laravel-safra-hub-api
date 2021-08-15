<?php


namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{

    public function sendVerificationEmail(Request $request)
    {

        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'status'=>200,
                'message' => 'Already Verified'
            ],200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'status'=>200,
            'message' => 'verification link sent'
        ],200);
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status'=>200,
                'message' => 'Email already verified'
            ],200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'status'=>200,
            'message' => 'Email has been verified'
        ],200);

    }

    public function checkVerification()
    {
        if (!auth()->user()->hasVerifiedEmail()) {
            return response()->json([
                'status'=>401,
                'message' => 'Email not Verified'
            ],401);
        }

        return response()->json([
            'status'=>200,
            'message' => 'Email Verified'
        ],200);
    }
}
