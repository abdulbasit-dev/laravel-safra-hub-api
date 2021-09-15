<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        return response()->success(
            200,
            count(
                $user_friends
            ) == 0 ? "This user has no friend" : "All User Friends",
            $user_friends,
            true
        );
    }

    public function seeFriendReq(Request $request)
    {
        $query = DB::table('friend_req as R');
        if($request->type=="incoming"){
            //incoming request
            $query = $query->select('R.id',"R.user_id",'U.name','R.created_at as send_request_time');
            $query = $query->join('users as U','U.id','R.user_id');
            $friend_requests  = $query->where('friend_req_id',auth()->id())->get();
        }else{
            //outgoing request
            $query = $query->select('R.id',"R.friend_req_id as user_id",'U.name','R.created_at as send_request_time');
            $query = $query->join('users as U','U.id','R.friend_req_id');
            $friend_requests  = $query->where("user_id",auth()->id())->get();
        }

        foreach ($friend_requests as $user){
            $user->profile_link = route('user.profile',$user->user_id);
        }

        return response()->success(
            200,
            count($friend_requests) ?
                ($request->type == 'incoming'
                    ? __('api.incoming_friend_req')
                    : __('api.outgoing_friend_req'))
                : __('api.no_friend_req_found'),
            $friend_requests,
            true
        );
    }

    public function sendFriendReq(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'user_id' => ['required','integer'],
        ]);

        if($validator->fails()){
            return response()->error(422,$validator->errors()->all());
        }

        //check if already fiend of user
        $friend_exist = auth()->user()->friends->contains($request->user_id);
        if($friend_exist){
            //delete record
            DB::table('friend_req')->whereId($request->id)->delete();
            return response()->error(409,__('api.friend_already_there',['user'=>user($request->user_id)]));
        }

        //check if  already request send to user
        $req_exist = DB::table('friend_req')->where('user_id', auth()->id())->where('friend_req_id', $request->user_id)->count();

        if ($req_exist) {
            return response()->error(
                409,
                __(
                    'api.request_already_there',
                    ['user' => user($request->user_id)]
                )
            );
        }

        //add friends request
        DB::table('friend_req')->insert([
                                            'user_id' => auth()->id(),
                                            'friend_req_id' => $request->user_id,
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now()
                                        ]);

        //send Notification to user that get friend request
        return response()->success(
            201,
            __('api.send_friend_req', ['user' => user($request->user_id)])
        );
    }

    public function removeFriendReq(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'request_id' => ['required','integer'],
        ]);

        if($validator->fails()){
            return response()->error(422,$validator->errors()->all());
        }

        //friend_req_id the person the get request to be friended of user_id person
        $friendReq = DB::table('friend_req')->whereId($request->request_id)->first();

        if(!$friendReq){
            return response()->error(404,__('api.not_found'), "the friend_request with id $request->request_id not found in database");
        }

        //delete record
        DB::table('friend_req')->whereId($request->request_id)->delete();

        return response()->success(
            200,
            __('api.remove_friend_req')
        );
    }

    public function friendReqAction(Request $request)
    {
        //validation
        $validator = Validator::make($request->all(), [
            'id' => ['required','integer'],
            'action' => ['required','integer'],
        ]);

        if($validator->fails()){
            return response()->error(422,$validator->errors()->all());
        }

        //friend_req_id the person the get request to be friended of user_id person
        $friendReq = DB::table('friend_req')->whereId($request->id)->first();
        if(!$friendReq){
            return response()->error(404,__('api.not_found'), "the friend_request with id $request->id not found in database");
        }

        if($request->action){

            //check for duplicated
            $friend_exist = auth()->user()->friends->contains($friendReq->user_id);
            if($friend_exist){
                //delete record
                DB::table('friend_req')->whereId($request->id)->delete();
                return response()->error(409,__('api.friend_already_there',['user'=>user($friendReq->user_id)]),'the record is deleted, because its duplicated ');
            }

            //add to friend list
            auth()->user()->friends()->attach([$friendReq->user_id]);

            //delete record
            DB::table('friend_req')->whereId($request->id)->delete();

            //send FCM notification to user_id that friendship accepted
            //NOT IMPLEMENTED
            return response()->success(200,__('api.friend_req_accepted'));
        }

        //delete record
        DB::table('friend_req')->whereId($request->id)->delete();
        return response()->success(200,__('api.friend_req_rejected'));
    }
    
    public function removeFriends(Request $request)
    {
        //get user
        $user = auth()->user();

        //check if this user is friend of user
        $exists = $user->friends->contains($request->friend_id);
        if (!$exists) {
            return response()->error(404, "This user is not a friend of $user->name");
        }

        $user->friends()->toggle([$request->friend_id]);
        return response()->success(200, __('api.remove_friend'));
    }

    public function fakeFriendReq(Request $request)
    {
        //check if  already send request send to user
        $req_exist = DB::table('friend_req')->where('user_id', $request->user_id)->where('friend_req_id', auth()->id())->count();

        if ($req_exist) {
            return response()->error(
                409,
                __(
                    'api.request_already_there',
                    ['user' => user(auth()->id())]
                )
            );
        }

        //add friends request
        DB::table('friend_req')->insert([
                                            'user_id' => $request->user_id,
                                            'friend_req_id' =>auth()->id(),
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now()
                                        ]);

        return response()->success(
            201,
            __('api.send_friend_req', ['user' => user(auth()->id())])
        );

    }
}
