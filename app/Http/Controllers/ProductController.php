<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.product-page');
    }
    public function create(Request $request)
    {
        $user_id = $request->header('id');
        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = $user_id . '_' . md5(uniqid()) . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads'), $img_name);
            $img_url = asset('uploads/' . $img_name);
        }
        return Product::create([
            'user_id' => $request->header('id'),
            'category_id' => $request->input('category_id'),
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'unit' => $request->input('unit'),
            'img_url' => $img_url
        ]);
    }
    public function list(Request $request)
    {
        $user_id = $request->header('id');
        $category_id = $request->input('category_id');
        return Product::where('user_id', $user_id)->where('category_id', $category_id)->get();
    }
    public function update(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = $user_id . '_' . md5(uniqid()) . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads'), $img_name);
            $img_url = asset('uploads/') . $img;

            $old_img_url = $request->input('img_url');
            File::delete($old_img_url);

            return Product::where('id', $product_id)->where('user_id', $user_id)->update([
                // 'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'img_url' => $img_url
            ]);
        } else {
            return Product::where('id', $product_id)->where('user_id', $user_id)->update([
                // 'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
            ]);
        }
    }
    public function delete(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        $img_url = $request->input('img_url');
        File::delete($img_url);
        return Product::where('user_id', $user_id)->where('id', $product_id)->delete();
    }
    public function details(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('product_id');
        return Product::where('user_id', $user_id)->where('id', $product_id)->first();
    }
}
