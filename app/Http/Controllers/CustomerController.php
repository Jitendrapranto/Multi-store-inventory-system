<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function customerPage(){
        return view ('pages.dashboard.customer-page');
    }



    // Create Customer
    function createCustomer(Request $request){
        try{
            $validator= Validator::make($request->all(), [
                'name'=> 'required|string|max:50',
                'email'=> 'required|email',
                'mobile'=> 'required|string|max:15'
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'status'=> 'fail',
                    'message'=> 'Validation Fail',
                    'error'=> $validator->errors()
                ],422);
            }

            $data= $validator->validate();

          Customer::create([ 
                'name'=> $data['name'],
                'email'=> $data['email'],
                'mobile'=> $data['mobile'],                
                'user_id'=> $request->header('user_id')
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'request successful'
            ], 200);          


        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Get all customer
    function customerList(Request $request){
        try{
            $user_id= $request->header('user_id');

            $customer= Customer::where('user_id', $user_id)
            ->select(['id', 'name', 'email', 'mobile'])
            ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'request successful',
                'data'=> $customer
            ], 200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // get Customer By ID
    function customerById(Request $request){
        try{
            $user_id= $request->header('user_id');
            $id= $request->input('id');

            $customer= Customer::where('user_id', $user_id)->where('id', $id)->first();

            return response()->json([
                'status'=> 'success',
                'message'=> 'request successful',
                'data'=> $customer
            ],200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Customer Update
    function customerUpdate(Request $request){
        try{
            $validator= Validator::make($request->all(),[
                'name'=> 'required|string|max:50',
                'email'=> 'required|email',
                'mobile'=> 'required|string|max:15'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'=> 'fail',
                    'message'=> 'Validation Fail',
                    'error'=> $validator->errors()
                ], 422);
            }


            $user_id= $request->header('user_id');
            $id= $request->input('id');

            $data= $validator->validate();

            $customer= Customer::Where('id', $id)->where('user_id', $user_id)->first();

            if(!$customer){
                return response()->json([
                    'status'=> 'fail',
                    'message'=> 'Customer not fund.'
                ], 404);
            }

            $customer->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile']
            ]);

            return response()->json([
                'status'=> 'success',
                'message'=> 'request successful',
                'data'=> $customer->only(['id', 'name', 'email', 'mobile'])
            ],200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


    // Customer Delete
    function customerDelete(Request $request){
        try{
            $user_id= $request->header('user_id');
            $id= $request->input('id');

            $customer= Customer::where('id', $id)->where('user_id', $user_id)->first();

            if(!$customer){
                return response()->json([
                    'status'=> 'fail',
                    'message'=> 'Customer not fund.'
                ], 404);
            }

            $customer->delete();

            return response()->json([
                'status'=> 'success',
                'message'=> 'Customer delete successfully.'
            ],200);

        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }
}