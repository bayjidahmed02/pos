<?php

namespace App\Http\Controllers;

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
    public function categoryList(Request $request)
    {
        $user_id = $request->header('id');
        return Category::where('user_id', $user_id)->get();
    }
}
