<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
// use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();
            //Store image
            $file_name = '';
            if ($request->hasFile('image')) {
                $getFileNameWithExt = $request->file('image')->getClientOriginalName();
                $fileName = pathinfo($getFileNameWithExt, PATHINFO_FILENAME);
                $file_name = $fileName . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/profile'), $file_name);
            } else {
                $file_name = 'no_image.png';
            }

            // //generate qrcode
            // $name_slug = Str::slug($request->name);
            // $qr = QrCode::format('png');
            // $qr->margin(1);
            // $qr->size(300);
            // $qr->errorCorrection('H');

            // //only merge image with qrcode if user send its image
            // if ($file_name !== 'no_image.png') {
            //     $qr->merge('../public/uploads/profile/' . $file_name, .3);
            // }

            // $qr->generate('http://www.simplesoftware.io', '../public/uploads/qrcodes/user/' . $name_slug . '.png');

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'gender' => $request->gender,
                'birthday' => $request->birthday,
                'image' => '/uploads/profile/' . $file_name,
                // 'qrcode' => '/uploads/qrcodes/user/' . $name_slug . '.png',
                'qrcode' => '/uploads/qrcodes/user/abdulbasit-ssds.png',
            ]);



            $token = $user->createToken('myapitoken')->plainTextToken;

            $user->sendEmailVerificationNotification();

            DB::commit();
            return response()->json([
                "status" => 201,
                "message" => 'verification link sent',
                'user_token' =>  $token
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => 'User not created',
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        //check email
        $user = User::where('email', $credentials['email'])->get()->first();

        //check passwors
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "status" => 401,
                "message" => __('api.wrong_credential'),
            ], 401);
        }

        //create token
        $token = $user->createToken('myapitoken')->plainTextToken;
        $user["user_token"] = $token;

        return response()->json([
            "status" => 200,
            "message" => 'user logged in succefully',
            'data' => $user
        ], 200);
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 200,
            "message" => 'user logout succefully',
        ], 200);
    }
}
