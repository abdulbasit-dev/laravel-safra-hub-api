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
        return response()->success(200,"user information",auth()->user());
    }

    public function userProfileById(User $user)
    {
        return response()->success(200,"user information",$user);
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
        return response()->success(200,__('api.user_updated'),$user);
    }


}
