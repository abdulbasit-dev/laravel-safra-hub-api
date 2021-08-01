<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categires = Category::select('id', 'name', 'image')->get();
        return response()->json([
            "status" => 200,
            "message" => "all the category",
            "total" => count($categires),
            'data' => $categires
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:categories,name',
            'image' => 'required|image|max:1000'
        ]);

        //Store image
        $file_name = '';
        if ($request->hasFile('image')) {
            $getFileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($getFileNameWithExt, PATHINFO_FILENAME);
            $file_name = $fileName . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads/categroy'), $file_name);
        } else {
            $file_name = 'no_image.png';
        }

        $category = Category::create([
            'name' => $request->name,
            'image' => '/uploads/category/' . $file_name,
        ]);

        return response()->json([
            "status" => 201,
            "message" => 'category created succefully',
            'data' => $category
        ], 201);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        // //delete image from public folder
        // if ($menu->image !== "/uploads/menu/no_image.png") {
        //     $imageArr = explode('/', $menu->image);
        //     $image = end($imageArr);
        //     $destinationPath = 'uploads/menu';
        //     File::delete($destinationPath . "/$image");
        // }

        return response()->json([
            "status" => 202,
            "message" => "category deleted successfuly",
        ], 202);
    }
}
