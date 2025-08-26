<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class InvoiceController extends Controller
{
   public function invoicePage(){
    return view('pages.dashboard.invoice-page');
   }


   
   
   
   
   // Create Invoice
   function invoiceCreate(Request $request){
      
      DB::beginTransaction();
      try {
         $user_id=$request->header('user_id');
         $total=$request->input('total');
         $discount=$request->input('discount');
         $vat=$request->input('vat');
         $payable=$request->input('payable');
         $customer_id=$request->input('customer_id');
         $total_profit= $request->input('total_profit');
         
         $invoice= Invoice::create([
            'total'=>$total,
            'discount'=>$discount,
            'vat'=>$vat,
            'payable'=>$payable,
            'user_id'=>$user_id,
            'customer_id'=>$customer_id,
            'total_profit'=>$total_profit,
         ]);
         
         $invoiceID=$invoice->id;
         
         $products= $request->input('products');

         $item_discount= $request->input('item_discount');
         
         $subtotal= $request->input('subtotal');
         
         $item_profit= $request->input('item_profit');
         
         
         foreach ($products as $item){
            $product= Product::where('id', $item['product_id'])->where('user_id', $user_id)->first();
            
            if(!$product || $product->stock_qty < intval($item['qty'])){
               throw new \Exception("Insufficient Stock for: {$item['product_name']}");
            }
            
            // Profit Calculation
            $profit= ($item['price_with_discount'] - $product->buy_price) * $item['qty'];
            
            
            // Create an Invoice
            InvoiceProduct::create([
               'invoice_id' => $invoiceID,
               'user_id'=> $user_id,
               'product_id' => $item['product_id'],
               'qty' => $item['qty'],
               'sale_price'=> $product->price,
               'buy_price'=> $product->buy_price,
               'discount'=> $item['item_discount'],
               'unit_price'=> $item['price_with_discount'],
               'subtotal'=> $item['subtotal'],
               'item_profit'=> $profit
            ]);
            
            
            // Update Stock
            $product->stock_qty -= $item['qty'];
            $product-> save();
         }
         DB::commit();
         
         return response()->json([
            'status'=> 'success',
            'massage'=> 'Invoice Created'
         ],200);
         

      }catch (\Throwable $e){
         DB::rollBack();
         return response()->json([
            'status' => 'fail',
            'message' => 'Something went wrong',
            'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
         ], 500);
      }
   }


   
   // Get All Invoice
   function invoiceSelect(Request $request){
      $user_id=$request->header('user_id');
      
      $invoice= Invoice::where('user_id',$user_id)
      ->with('customer')
      ->get();

      return response()->json([
            'status'=> 'success',
            'massage'=> 'Invoice Created',
            'data'=> $invoice
         ],200);
   }




   // Invoice Details
   function invoiceDetails(Request $request){
      $user_id=$request->header('user_id');
      
      $customerDetails= Customer::where('user_id',$user_id)
      ->where('id',$request->input('cus_id'))
      ->first();
      
      
      $invoiceTotal= Invoice::where('user_id', $user_id)
      ->where('id', $request->input('inv_id'))
      ->first();
      
      $invoiceProduct= InvoiceProduct::where('invoice_id',$request->input('inv_id'))
      ->where('user_id', $user_id)
      ->with('product')
      ->get();
      
      
      return array(
         'customer'=>$customerDetails,
         'invoice'=>$invoiceTotal,
         'product'=>$invoiceProduct,
      );
   }



   // Invoice Delete
   function invoiceDelete(Request $request){
      
      DB::beginTransaction();
      
      try {
         $user_id=$request->header('user_id');
         $inv_id = $request->input('inv_id');
         
         $invoice_products= InvoiceProduct::where('invoice_id', $inv_id)
         ->where('user_id',$user_id)
         ->get();
         
         foreach ($invoice_products as $item) {
            $product = Product::where('id', $item->product_id)
            ->where('user_id', $user_id)
            ->first();
            
            if ($product) {
               $product->stock_qty += $item->qty; // stock restore
               $product->save();
            }
         }
         
         InvoiceProduct::where('invoice_id', $inv_id)
         ->where('user_id', $user_id)
         ->delete();

         Invoice::where('id', $inv_id)
         ->where('user_id', $user_id)
         ->delete();
         
         DB::commit();

         return response()->json([
            'status' => 'success',
            'message' => 'Invoice deleted successfully'
         ], 200);
      
      
      }catch (\Throwable $e){
         DB::rollBack();
         return response()->json([
            'status' => 'fail',
            'message' => 'Something went wrong',
            'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
         ], 500);
      }
   }


}