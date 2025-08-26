<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboardPage(){
        return view ('pages.dashboard.dashboard-page');
    }



    public function summary(Request $request){
        $user_id = $request->header('user_id');

        $product = Product::where('user_id', $user_id)->count();
        $category = Category::where('user_id', $user_id)->count();
        $customer = Customer::where('user_id', $user_id)->count();
        $invoice = Invoice::where('user_id', $user_id)->count();

        $invoiceSummary = Invoice::where('user_id', $user_id)
        ->selectRaw('ROUND(SUM(total), 2) as total, ROUND(SUM(vat), 2) as vat, ROUND(SUM(payable), 2) as payable')
        ->first();

        $todaysSale=Invoice::where('user_id', $user_id)
        ->whereDate('created_at',Carbon::today())
        ->selectRaw('Round(SUM(total), 2) as total')
        ->first();

        $thisMonthSale=Invoice::where('user_id', $user_id)
        ->whereYear('created_at', Carbon::now()->year)
        ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw('Round(SUM(total), 2) as total')
        ->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'product' => $product,
                'category' => $category,
                'customer' => $customer,
                'invoice' => $invoice,
                'total' => $invoiceSummary->total ?? 0,
                'todaysSale' => $todaysSale->total ?? 0,
                'thisMonthSale' => $thisMonthSale->total ?? 0,
                'vat' => $invoiceSummary->vat ?? 0,
                'payable' => $invoiceSummary->payable ?? 0,
            ]
            ], 200);
    }
}
