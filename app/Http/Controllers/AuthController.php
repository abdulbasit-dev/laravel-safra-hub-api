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
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('myapitoken')->plainTextToken;

        return [
            "user" => $user,
            "token" => $token
        ];
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

        //login user
        Auth::attempt($credentials);

        //create token
        $token = $user->createToken('myapitoken')->plainTextToken;

        return [
            "user" => $user,
            "token" => $token
        ];
    }


    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => "logout"
        ];
    }
}
