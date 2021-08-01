<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
    {
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'gender' => $request->gender,
            'birthday' => $request->birthday,
            'image' => '/uploads/profile/' . $file_name,
        ]);

        $token = $user->createToken('myapitoken')->plainTextToken;
        $user["user_token"] = $token;

        return response()->json([
            "status" => 201,
            "message" => 'user created succefully',
            'data' => $user
        ], 201);
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
            return [
                'message' => "wronge credential"
            ];
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
