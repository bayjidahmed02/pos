<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.customer-page');
    }
    public function create(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'email|string',
                'mobile' => 'required|numeric',
            ]);
            Customer::create([
                'user_id' => $request->header('id'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
            ]);
            return response()->json([
                'status' => 'success',
                'msg' => 'Created'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Something went wrong'
            ]);
        }
    }
    public function list(Request $request)
    {
        $user_id = $request->header('id');
        return Customer::where('user_id', $user_id)->get();
    }
    public function details(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        return Customer::where('user_id', $user_id)->where('id', $customer_id)->first();
    }
    public function update(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        return Customer::where('user_id', $user_id)->where('id', $customer_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'mobile' => $request->input('mobile'),
        ]);
    }
    public function delete(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('id');
        return Customer::where('user_id', $user_id)->where('id', $customer_id)->delete();
    }
}
