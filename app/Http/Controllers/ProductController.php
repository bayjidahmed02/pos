<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.product-page');
    }
    public function create(Request $request)
    {
    }
    public function list(Request $request)
    {
    }
    public function update(Request $request)
    {
    }
    public function delete(Request $request)
    {
    }
    public function details(Request $request)
    {
    }
}
