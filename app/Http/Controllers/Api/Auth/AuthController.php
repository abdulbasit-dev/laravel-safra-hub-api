<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse as Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request): Json
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
                "message" => __('api.verification_link_sent'),
                'user_token' => $token
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => true,
                'message' => __('api.internal_server_error'),
                'detail' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) :Json
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()->all()
            ], 422);
        }

        $credentials = $request->all();

        //check email
        $user = User::where('email', $credentials['email'])->get()->first();

        //check password
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
            "message" => __('api.login_success'),
            'data' => $user
        ], 200);
    }


    public function logout() : Json
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            "status" => 200,
            "message" => __('api.logout_success'),
        ], 200);
    }

    public static function resetPassword(Request $request) : Json
    {
        $messages = [
            'old_password.required' => __('api.old_password_req'),
            'new_password.required' => __('api.new_password_req'),
            'old_password.min' => __('api.old_password_min'),
            'new_password.min' => __('api.new_password_min'),
        ];
        //validation
        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => ['required', 'string', 'min:8'],
                'new_password' => ['required', 'string', 'min:8'],
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => 422,
                    'message' => $validator->errors()->all()
                ],
                422
            );
        }

        //get user
        $user = auth()->user();
        //check password
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'status' => 403,
                'message' => __('api.invalid_password')
            ], 403);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => __('api.api.password_reset_success')
        ], 200);

    }
}


