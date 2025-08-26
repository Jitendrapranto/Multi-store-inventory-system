<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\invoiceProduct;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function reportPage(){
        return view('pages.dashboard.report-page');
    }


    
    
    public function salesReport(Request $request){      
        
        $user_id = $request->header('user_id');
        $from_date = date('Y-m-d', strtotime($request->FormDate));
        $to_date = date('Y-m-d', strtotime($request->ToDate));

        // dd($from_date);
    
        $invoices = Invoice::where('user_id', $user_id)
        ->whereDate('created_at', '>=', $from_date)
        ->whereDate('created_at', '<=', $to_date)
        ->with('customer')
        ->get();       

        // if ($invoices->isEmpty()) {
        //     return back()->with('error', 'No sales found in this date range.');
        // }       

        $total = $invoices->sum('total');
        $vat = $invoices->sum('vat');
        $payable = $invoices->sum('payable');
        $discount = $invoices->sum('discount');
        $total_profit = $invoices->sum('total_profit');
        
        $invoice_ids = $invoices->pluck('id')->toArray();

        $total_qty = InvoiceProduct::whereIn('invoice_id', $invoice_ids)
        ->where('user_id', $user_id)
        ->sum('qty');

        $data = [
            'total' => $total,
            'vat' => $vat,
            'payable' => $payable,
            'discount' => $discount,
            'total_qty' => $total_qty,
            'total_profit' => $total_profit,
            'list' => $invoices,
            'from_date' => $from_date,
            'to_date' => $to_date
        ];

        $pdf = Pdf::loadView('report.sales_report', $data);

        return $pdf->download('sales_report.pdf');
    }
}
