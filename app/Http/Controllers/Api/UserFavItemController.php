<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\UserFavItem;
use \Illuminate\Http\JsonResponse as Json;
use Illuminate\Support\Facades\Validator;

class UserFavItemController extends Controller
{

    private $total_item = 0;

    public function index() : Json
    {
        //get user id
        $id = auth()->id();

        $item_categories = Category::select('id', 'name', 'image')->with([
            'items' => function ($q) use ($id) {
                $items =  $q->select('id as item_id', 'user_id', 'category_id', 'name')->where('user_id', $id)->orderBy('created_at','desc')->get();
                $this->total_item = count($items);
                return $items;
            }
        ])->whereHas(
            'items',
            function ($q) use ($id) {
                return $q->where('user_id', $id);
            }
        )->get();

        foreach ($item_categories as $item_category) {
            $item_category['total_item_per_category'] =  count($item_category['items']);
        }

//        return response()->success(200,'all items according to category', $this->total_item, true);
        return response()->json([
            'success'=>true,
            'status' => 200,
            'total' => $this->total_item,
            'message' => 'all items according to category',
            'data' => $item_categories
        ]);
    }

    public function store(Request $request) : Json
    {
        //validation
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'category_id' => ['required'],
        ]);

        if($validator->fails()){
            return response()->error(422,$validator->errors()->all());
        }


        $user_id = auth()->id();

        $favItem = UserFavItem::create([
            'user_id' => $user_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return response()->success(201,__('api.item_added'),$favItem);
    }

    public function show($item_id): Json
    {
        //get user id
        $id = auth()->id();

        $item_categories = Category::select('id', 'name', 'image')->where('id', $item_id)->with([
            'items' => function ($q) use ($id) {
                $items =  $q->select('id as item_id', 'user_id', 'category_id', 'name')->where('user_id', $id)->orderBy('created_at','desc')->get();
                $this->total_item = count($items);
                return $items;
            }
        ])->whereHas(
            'items',
            function ($q) use ($id) {
                return $q->where('user_id', $id);
            }
        )->get();

        foreach ($item_categories as $item_categorie) {
            $item_categorie['total_item_per_category'] =  count($item_categorie['items']);
        }

        return response()->json([
            'success'=>true,
            'status' => 200,
            'total' => $this->total_item,
            'message' => 'all items according to category',
            'data' => $item_categories
        ],200);
    }

    public function destroy(UserFavItem $user_favorite_item): Json
    {
        //get user id
        $user_id =  auth()->id();

        //check if item belongs to the user
        if ($user_id !== $user_favorite_item->user_id) {
            return response()->error(403,__('api.item_not_belong_to_user'));
        }

        $user_favorite_item->delete();
        return response()->success(202,__('api.item_removed'));
    }
}
