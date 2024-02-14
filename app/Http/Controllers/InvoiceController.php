<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.invoice-page');
    }
    public function salePage()
    {
        return view('pages.dashboard.sale-page');
    }
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $total = $request->input('total');
            $discount = $request->input('discount');
            $vat = $request->input('vat');
            $payable = $request->input('payable');

            $customer_id  = $request->input('customer_id');
            $invoice = Invoice::create([
                'total' => $total,
                'discount' => $discount,
                'vat' => $vat,
                'payable' => $payable,
                'user_id' => $user_id,
                'customer_id' => $customer_id,
            ]);
            $invoice_id = $invoice->id;
            $products = $request->input('products');

            foreach ($products as $eachProduct) {
                InvoiceProduct::create([
                    'invoice_id' => $invoice_id,
                    'user_id' => $user_id,
                    'product_id' => $eachProduct['product_id'],
                    'qty' => $eachProduct['qty'],
                    'sale_price' => $eachProduct['sale_price'],
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'msg' => 'Successfully Created'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'msg' => $e->getMessage()
            ]);
        }
    }
    public function list(Request $request)
    {
        $user_id = $request->header('id');
        return Invoice::where('user_id', $user_id)->with('customer')->get();
    }
    public function details(Request $request)
    {
        $user_id = $request->header('id');
        $customer_id = $request->input('customer_id');
        $invoice_id = $request->input('invoice_id');

        $customer_details = Customer::where('user_id', $user_id)->where('id', $customer_id)->first();
        $invoice_total = Invoice::where('user_id', $user_id)->where('id', $invoice_id)->first();
        $invoice_product = InvoiceProduct::where('invoice_id', $invoice_id)->where('user_id', $user_id)->with('product')->get();
        return array(
            'customer' => $customer_details,
            'invoice' => $invoice_total,
            'product' => $invoice_product
        );
    }
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = $request->header('id');
            $id = $request->input('invoice_id');
            InvoiceProduct::where('invoice_id',  $id)->where('user_id', $user_id)->delete();
            Invoice::where('id', $id)->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'msg' => 'Deleted'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ]);
        }
    }
}
