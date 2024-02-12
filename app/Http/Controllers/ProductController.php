<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
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
        try {
            $request->validate([
                'name' => 'required|string',
                'price' => 'required|numeric',
                'unit' => 'required|numeric',
                'img' => 'required|mimes:png,jpg,webp,gif,svg,jpeg'
            ]);

            $user_id = $request->header('id');
            if ($request->hasFile('img')) {
                $img = $request->file('img');
                $img_name = $user_id . '_' . $img->getClientOriginalName();
                $img->move(public_path('uploads'), $img_name);
                $img_url = 'uploads/' . $img_name;
            }

            Product::create([
                'user_id' => $request->header('id'),
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
                'img_url' => $img_url
            ]);
            return response()->json([
                'status' => 'success',
                'msg' => 'added'
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'something went wrong'
            ]);
        }
    }
    public function list(Request $request)
    {
        $user_id = $request->header('id');
        return Product::where('user_id', $user_id)->get();
    }
    public function update(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');

        if ($request->hasFile('img')) {
            $img = $request->file('img');
            $img_name = $user_id . '_' . $img->getClientOriginalName();
            $img->move(public_path('uploads'), $img_name);
            $img_url = 'uploads/' . $img_name;

            $old_img = $request->input('old_img');
            if (File::exists($old_img)) {
                File::delete($old_img);
                return Product::where('id', $product_id)->where('user_id', $user_id)->update([
                    'category_id' => $request->input('category_id'),
                    'name' => $request->input('name'),
                    'price' => $request->input('price'),
                    'unit' => $request->input('unit'),
                    'img_url' => $img_url
                ]);
            } else {
                return 'Product Image Cannot find';
            }
        } else {
            return Product::where('id', $product_id)->where('user_id', $user_id)->update([
                'category_id' => $request->input('category_id'),
                'name' => $request->input('name'),
                'price' => $request->input('price'),
                'unit' => $request->input('unit'),
            ]);
        }
    }
    public function delete(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        $img_url = $request->input('img_url');
        File::delete($img_url);
        return Product::where('user_id', $user_id)->where('id', $product_id)->delete();
    }
    public function details(Request $request)
    {
        $user_id = $request->header('id');
        $product_id = $request->input('id');
        return Product::where('user_id', $user_id)->where('id', $product_id)->first();
    }
}
