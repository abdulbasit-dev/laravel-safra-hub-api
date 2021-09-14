<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name', 'image')->get();
        return response()->success(200,"all the category",$categories,true);

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
            $request->image->move(public_path('uploads/category'), $file_name);
        } else {
            $file_name = 'no_image.png';
        }

        $category = Category::create([
            'name' => $request->name,
            'image' => '/uploads/category/' . $file_name,
        ]);

        return response()->success(201,'category created successfully',$category);
    }

    public function destroy(Category $category)
    {
         //delete image from public folder
         if ($category->image !== "/uploads/menu/no_image.png") {
             $imageArr = explode('/', $category->image);
             $image = end($imageArr);
             $destinationPath = 'uploads/category';
             File::delete($destinationPath . "/$image");
         }
        $category->delete();

        return response()->success(202,'category deleted successfully');
    }
}
