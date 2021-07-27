<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserFavItemReuest;
use App\Models\Category;
use App\Models\User;
use App\Models\UserFavItem;
use Illuminate\Http\Request;

class UserFavItemController extends Controller
{

    private $total_item = 0;

    public function index()
    {
        //get user id
        $id = auth()->id();

        $item_categories = Category::select('id', 'name', 'image')->with([
            'items' => function ($q) use ($id) {
                $items =  $q->select('id as item_id', 'user_id', 'category_id', 'name', 'price')->where('user_id', $id)->get();
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
            'status' => 200,
            'total' => $this->total_item,
            'message' => 'all items acording to category',
            'data' => $item_categories
        ]);
    }

    public function store(StoreUserFavItemReuest $request)
    {
        $user_id = auth()->id();

        $favItem = UserFavItem::create([
            'user_id' => $user_id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            "status" => 201,
            "message" => 'item added user favorate list',
            'data' => $favItem
        ], 201);
    }

    public function show($item_id)
    {
        //get user id
        $id = auth()->id();

        $item_categories = Category::select('id', 'name', 'image')->where('id', $item_id)->with([
            'items' => function ($q) use ($id) {
                $items =  $q->select('id as item_id', 'user_id', 'category_id', 'name', 'price')->where('user_id', $id)->get();
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
            'status' => 200,
            'total' => $this->total_item,
            'message' => 'all items acording to category',
            'data' => $item_categories
        ]);
    }

    public function destroy(UserFavItem $user_favorate_item)
    {
        //get user id
        $user_id =  auth()->id();

        //check if item belongs to the user
        if ($user_id !== $user_favorate_item->user_id) {
            return response()->json([
                "status" => 403,
                "message" => "this item dosen't belongs to you",
            ], 403);
        }

        $user_favorate_item->delete();
        return response()->json([
            "status" => 202,
            "message" => "item removed from user favorate list",
        ], 202);
    }
}
