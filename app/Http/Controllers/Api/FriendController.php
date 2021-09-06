<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FriendController extends Controller
{


    public function userFriends(Request $request)
    {
        //get user
        $user = auth()->user();
        $query = $user->friends()->select(['id', 'name', 'email', 'image']);

        //filter by name
        if ($request->has('name')) {
            $query = $query->where('name', 'like', "%$request->name%");
        }

        $user_friends = $query->get();

        return response()->data(
            200,
            count(
                $user_friends
            ) == 0 ? "This user has no friend" : "All User Friends",
            $user_friends
        );
    }

    public function addFriends(Request $request)
    {
        //get user
        $user = auth()->user();
        //add friends
        $user->friends()->sync($request->friends);

        return response()->success(200, __('api.add_friend'));
    }

    public function removeFriends(Request $request)
    {
        //get user
        $user = auth()->user();

        //check if this user is friend of user
        foreach ($request->friends as $friend) {
            $exists = $user->friends->contains($friend);
            if ($exists) {
                $user->friends()->toggle($request->friends);
            }else{
                return response()->error(403, "This user is not a friend of $user->name");
            }
        }

        return response()->success(200, __('api.remove_friend'));
    }
}
