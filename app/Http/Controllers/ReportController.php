<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        return view('pages.dashboard.report-page');
    }
    public function salesReport(Request $request)
    {
        $user_id = $request->header('id');
        $fromDate = date('Y-m-d', strtotime($request->fromDate));
        $toDate = date('Y-m-d', strtotime($request->toDate));
     

        $total = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->sum('total');

        $discount = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->sum('discount');
        $vat = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->sum('vat');
        $payable = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->sum('payable');

        $customers = Invoice::where('user_id', $user_id)
            ->whereDate('created_at', '>=', $fromDate)
            ->whereDate('created_at', '<=', $toDate)
            ->with('customer')->get();

        $data = [
            'fromDate' => $request->fromDate,
            'toDate' => $request->toDate,
            'total' => $total,
            'discount' => $discount,
            'vat' => $vat,
            'payable' => $payable,
            'customers' => $customers,
        ];
        // return response()->json($data);

        $pdf = Pdf::loadView('report.SalesReport', $data);
        return $pdf->download('invoice.pdf');
    }
}
