<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function productPage(){
        return view('pages.dashboard.product-page');
    }




    // Create Product
    function productCreate(Request $request){
        try{
            // $validator= Validator::make($request->all(),[
            //     'name'=> 'required|string|max:100',
            //     'buy_price'=> 'required|numeric',
            //     'price'=> 'required|numeric',
            //     'unit'=> 'required|string|max:50',
            //     'stock_qty'=> 'required|numeric',
            //     'category_id'=> 'required',
            //     'img'=> 'image'
            // ]);

            // if(!$validator->fails()){
            //     return response()->json([
            //         'status'=> 'fail',
            //         'message'=> 'validation Error',
            //         'error'=> $validator->errors()
            //     ],422);
            // }
           
            //  prepare Image name
            $user= $request->header('user_id');
            $img= $request->file('img');
            $time= time();
            $file_path= $img->getClientOriginalName();

            $image_name= "{$user}-{$time}-{$file_path}";

            $image_url= "uploads/{$image_name}";

            
            // Upload File
            $img->move(public_path('uploads'),$image_name);

            // Save to database
           $product= Product::create([
                'name'=> $request->input('name'),
                'buy_price'=> $request->input('buy_price'),
                'price'=> $request->input('price'),
                'unit'=> $request->input('unit'),
                'stock_qty'=> $request->input('stock_qty'),
                'category_id'=> $request->input('category_id'),
                'img_url'=> $image_url,
                'user_id'=> $user
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Product created successfully',
                'data' => $product
            ], 200);


        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // get all product
    function productList(Request $request){
        try{
            $user_id= $request->header('user_id');

           $product= Product::where('user_id', $user_id)->get();

            return response()->json([
                'status' => 'success',
                'message' => 'request successful',
                'data'=> $product
            ], 200);



        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // get product by id
    function productById(Request $request){
        try{
            $product_id= $request->input('id');
            $user_id= $request->header('user_id');

            $product= Product::where('id', $product_id)->where('user_id', $user_id)->first();

            return response()->json([
                'status' => 'success',
                'message' => 'request successful',
                'data'=> $product
            ], 200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Update Product
    function productUpdate(Request $request){
        try{
            $user_id= $request->header('user_id');

            $product_id= $request->input('id');

           $product= Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->first();

            if (!$product) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Product not found or access denied.'
                ], 404);
            }

            // $validator= Validator::make($request->all(),[
            //     'name' => 'required|string|max:100',
            //     'buy_price'=> 'required|string|max:100',
            //     'stock_qty'=> 'required|string|max:50',
            //     'price' => 'required|numeric',
            //     'unit' => 'required|string|max:50',
            //     'category_id' => 'required|integer',
            //     'img' => 'nullable|image|max:5120'                
            // ]);

            // if($validator->fails()){
            //     return response()->json([
            //         'status'=> 'fail',
            //         'message'=> 'validation Error',
            //         'errors'=> $validator->errors()
            //     ],422);
            // }

            // $data= $validator->validate();

            $updateData= [
                    'name'=> $request->input('name'),
                    'buy_price'=> $request->input('buy_price'),
                    'price'=> $request->input('price'),
                    'unit'=> $request->input('unit'),
                    'stock_qty'=> $request->input('stock_qty'),
                    'category_id'=> $request->input('category_id'),
                ];

            // Save image to folder.
            if($request->hasFile('img')){                
                $img= $request->file('img');
                $time= time();
                $file_path= $img->getClientOriginalName();

                $image_name= "{$user_id}-{$time}-{$file_path}";

                $image_url= "uploads/{$image_name}";
            
                // Upload File
                $img->move(public_path('uploads'),$image_name);
                
                // Delete old image
                if(!empty($product->img_url)){
                File::delete(public_path($product->img_url));
                }
                
                $updateData['img_url']= $image_url;           
            }

            // Update Product
            Product::where('id', $product_id)
            ->where('user_id', $user_id)
            ->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'Product update successful'
            ], 200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Delete Product
    function productDelete(Request $request){
        try{
            $user_id= $request->header('user_id');
            $product_id= $request->input('id');

            $product= Product::where('id', $product_id)->where('user_id', $user_id)->first();

            if (!$product){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Product not found'
                ], 404);
            }

            // delete product image from folder
            if(!empty($product->img_url)){
                File::delete(public_path($product->img_url));
            }
            
            // delete product from database 
            $product-> delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully'
            ], 200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'you Sold this product so delete is not possible',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Show product if has stock
    function hasStock(Request $request){
        try{
            $user_id= $request->header('user_id');

           $product= Product::where('user_id', $user_id)
           ->where('stock_qty', '>', 1)
           ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'request successful',
                'data'=> $product
            ], 200);



        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


}
