<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordCode;
use App\Models\User;
use Illuminate\Http\JsonResponse as Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{

    public function forgetPassword(Request $request): Json
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->all()
            ], 422);
        }

        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('api.user_not_found_email')
            ], 404);
        }

        //generate code
        $code = rand(1000, 9999);

        try {
            //send code to user
            Mail::to($user->email)->send(new ForgetPasswordCode($code));

            //store code in data base
           $user->otp = $code;
           $user->otp_verified=false;
           $user->save();

            return response()->json([
                'status' => 200,
                'message' => __('api.send_forget_password_code')
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => __('api.internal_server_error')
            ], 500);

        }
    }


    public function validateCode(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'code' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->all()
            ], 422);
        }

        //find user
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('api.user_not_found_email')
            ], 404);
        }


        //validate code
        if ($user->otp != $request->code) {
            return response()->json([
                'status' => 422,
                'message' => __('api.code_invalid')
            ], 422);
        }

        //verify code
        $user->otp_verified = true;
        $user->save();
        return response()->json([
            'status' => 200,
            'message' => __('api.code_correct')
        ], 200);
    }

    public function newPassword(Request $request): Json
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->all()
            ], 422);
        }


        //find user
        $user = User::whereEmail($request->email)->orderByDesc('id')->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => __('api.user_not_found_email')
            ], 404);
        }

        if (!$user->otp_verified) {
            return response()->json([
                'status' => 404,
                'message' => __('api.code_not_verified')
            ], 404);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => __('api.new_password')
        ], 200);

    }


}
