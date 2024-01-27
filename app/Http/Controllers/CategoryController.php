<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.category-page');
    }
    public function create(Request $request)
    {
        $user_id = $request->header('id');
        return  Category::create([
            'name' => $request->name,
            'user_id' => $user_id
        ]);
    }
    public function list(Request $request)
    {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get();
    }

    public function details(Request $request)
    {
        try {
            $category_id = $request->input('id');
            $user_id = $request->header('id');
            $category =  Category::where('id', $category_id)->where('user_id', $user_id)->first();
            if ($category) {
                return response()->json([
                    'category' => $category
                ]);
            } else {
                return response()->json([
                    'msg' => 'category cannot find'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'msg' => 'Something went wrong. please try again'
            ]);
        }
    }
    public function update(Request $request)
    {
        $user_id = $request->header('id');
        $category_id = $request->input('id');
        $name = $request->input('name');
        return Category::where('id', $category_id)->where('user_id', $user_id)->update([
            'name' => $name
        ]);
    }
    public function delete(Request $request)
    {
        $user_id = $request->header('id');
        $category_id = $request->input('id');
        return Category::where('id', $category_id)->where('user_id', $user_id)->delete();
    }
}
