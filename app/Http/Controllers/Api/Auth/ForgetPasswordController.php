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
            return response()->error(422,$validator->errors()->all());
        }

        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return response()->error(404,__('api.user_not_found_email'));
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

           return response()->success(200,__('api.send_forget_password_code'));

        } catch (\Exception $e) {
            return response()->error(500,__('api.internal_server_error'));
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
            return response()->error(422,$validator->errors()->all());
        }

        //find user
        $user = User::whereEmail($request->email)->first();
        if (!$user) {
            return response()->error(404,__('api.user_not_found_email'));
        }


        //validate code
        if ($user->otp != $request->code) {
            return response()->error(422,__('api.code_invalid'));
        }

        //verify code
        $user->otp_verified = true;
        $user->save();
        return response()->success(200,__('api.code_correct'));
    }

    public function newPassword(Request $request): Json
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->error(422,$validator->errors()->all());
        }

        //find user
        $user = User::whereEmail($request->email)->orderByDesc('id')->first();
        if (!$user) {
             return response()->error(404,__('api.user_not_found_email'));
        }

        if (!$user->otp_verified) {
            return response()->error(404,__('api.code_not_verified'));
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->success(200,__('api.new_password'));
    }


}
