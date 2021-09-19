<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse as Json;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AuthController extends Controller
{
    public function registerStep1(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'regex:/gmail|outlook|yahoo/', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            return response()->error(422, $validator->errors()->all());
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password),
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
            return response()->error(500, __('api.internal_server_error'), $e->getMessage());
        }
    }

    public function registerStep2(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => ['required','min:3', 'max:50'],
            'birthday' => ['required','date'],
            'image' => ['required','image', 'max:4000'],
            'gender' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->error(422, $validator->errors()->all());
        }

        //get user
        $user = auth()->user();

        try {
            DB::beginTransaction();

            //Store image
            $file_name = '';
            if ($request->hasFile('image')) {
                $getFileNameWithExt = $request->file('image')->getClientOriginalName();
//                $fileName = pathinfo($getFileNameWithExt, PATHINFO_FILENAME);
                $fileName = $request->name;
                $file_name = $fileName . '_' . time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads/profile'), $file_name);
            } else {
                $file_name = 'no_image.png';
            }

            //generate qrcode
            $name_slug = Str::slug($request->name);
            //PROD
//            $qr = QrCode::format('png');
            $qr = QrCode::format('svg');
            $qr->margin(1);
            $qr->size(300);
            $qr->errorCorrection('H');

            //only merge image with qrcode if user send its image
            if ($file_name !== 'no_image.png') {
                $qr->merge('../public/uploads/profile/' . $file_name, .3);
            }
            //PROD
//            $qr->generate('http://www.simplesoftware.io', '../public/uploads/qrcodes/user/' . $name_slug . '.png');
            $qr->generate('http://www.simplesoftware.io', '../public/uploads/qrcodes/user/' . $name_slug . '.svg');

            $user->update([
                    'name' => $request->name,
                    'gender' => $request->gender,
                    'birthday' => $request->birthday,
                    'image' => '/uploads/profile/' . $file_name,
                    'qrcode' => '/uploads/qrcodes/user/' . $name_slug . '.svg',
                ]);


            DB::commit();
            return response()->success(201,'User created successfully',$user);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->error(500,__('api.internal_server_error'),$e->getMessage());
        }
    }

    public function login(Request $request): Json
    {
        //validation
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->error(422,$validator->errors()->all());
        }

        $credentials = $request->all();

        //check email
        $user = User::where('email', $credentials['email'])->get()->first();

        //check password
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->error(401,__('api.wrong_credential'));
        }

        //create token
        $token = $user->createToken('myapitoken')->plainTextToken;
        $user["user_token"] = $token;

        return response()->success(200,__('api.login_sccess'),$user);
    }

    public function logout(): Json
    {
        auth()->user()->tokens()->delete();
        return response()->success(200,__('api.login_success'));
    }

    public static function resetPassword(Request $request): Json
    {
        $messages = [
            'old_password.required' => __('api.old_password_req'),
            'new_password.required' => __('api.new_password_req'),
            'old_password.min' => __('api.old_password_min'),
            'new_password.min' => __('api.new_password_min'),
        ];
        //validation
        $validator = Validator::make($request->all(), [
            'old_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8'],
        ], $messages);

        if ($validator->fails()) {
            return response()->error(422,$validator->errors()->all());
        }

        //get user
        $user = auth()->user();
        //check password
        if (!Hash::check($request->old_password, $user->password)){
            return response()->error(403,__('api.invalid_password'));
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->success(200,__('api.password_reset_success'));
    }
}


