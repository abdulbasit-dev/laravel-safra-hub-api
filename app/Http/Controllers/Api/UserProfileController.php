<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UserProfileController extends Controller
{

    public function index()
    {
        return response()->json([
            'status' => 200,
            'message' => "user information",
            'data' => auth()->user()
        ], 200);
    }

    public function update(Request $request)
    {
        //get user
        $user = auth()->user();

        if ($request->has('name') && $request->name != null) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $request->email != null) {
            $user->email = $request->email;
        }

        if ($request->has('bio') && $request->bio != null) {
            $user->bio = $request->bio;
        }

        if ($request->has('gender') && $request->gender != null) {
            $user->gender = $request->gender;
        }

        if ($request->has('birthday') && $request->birthday != null) {
            $user->birthday = $request->birthday;
        }

        $file_name = '';
        if ($request->hasFile('image')) {
            
            //delete old image
            if ($user->image !== "/uploads/profile/no_image.png") {
                $imageArr = explode('/', $user->image);
                $image = end($imageArr);
                $destinationPath = 'uploads/profile';
                File::delete($destinationPath . "/$image");
            }

            $getFileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($getFileNameWithExt, PATHINFO_FILENAME);
            $file_name = "/uploads/profile/" . $fileName . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/profile'), $file_name);
            $user->image = $file_name;
            // $user->image = '/uploads/profile/no_image.png';
        }

        $user->update();

        return response()->json([
            'status' => 202,
            'message' => 'user information updated',
            'data' => $user
        ],202);
    }

    public function updateImage(Request $request)
    {
        //get User
        $user = auth()->user();

        //check request if has image
        if ($request->hasFile('image')) {

            //get old image path and delete it 

            //store new image
            //Store image
            $file_name = '';

            $getFileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($getFileNameWithExt, PATHINFO_FILENAME);
            $file_name = $fileName . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categroy'), $file_name);
        } else {
            $file_name = 'no_image.png';
        }
    }
}
