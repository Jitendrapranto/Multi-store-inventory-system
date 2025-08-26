<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function userRegistrationPage(){
        return view('pages.auth.registration-page');
    }

    public function userLoginPage(){
        return view('pages.auth.login-page');
    }

    public function resetPasswordPage(){
        return view('pages.auth.reset-pass-page');
    }

    public function sendOtpPage(){
        return view('pages.auth.send-otp-page');
    }

    public function verifyOtpPage(){
        return view('pages.auth.verify-otp-page');
    }

    public function profilePage(){
        return view('pages.dashboard.profile-page');
    }



    // controller function

    // User Registration
    function userRegistration(Request $request){

        try{
            $validator = Validator::make($request->all(), [
                'first_Name'=> 'required|string|max:55',
                'last_Name'=> 'required|string|max:55',
                'email'=> 'required|email|unique:users,email',
                'mobile'=> 'required|string|max:15',
                'password'=> 'required|min:3'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }


            User::create([
                'first_name'=> $request->first_Name,
                'last_name'=> $request->last_Name,
                'email'=> $request->email,
                'mobile'=> $request->mobile,
                'password'=> $request->password,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successful',
            ], 200);


        }catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }

    }

    // User Login
    function userLogin(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email'=> 'required|email',
                'password'=> 'required|min:3'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->first();

            // if($user && Hash::check($request->password, $user->password))
            if($request->password === $user->password){
                $token = JWTToken::createToken($user->email, $user->id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'User login Successful',
                ], 200)->cookie('token', $token, 60*24);
            } else{
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Invalid Email or Password',
                ], 401);
            }

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }

    // User Log Out
    function logOut(){
        return redirect('/userLogin')->withCookie(cookie('token', null, -1));
    }


    // Sent OTP
    function sendOTP(Request $request){

        try{
            $validator= Validator::make($request->all(), [
                'email' => 'required|email|exists:users,email',
            ]);
            if($validator->fails()){
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }
            $otp= rand(100000, 999999);
            $emailId= $request->email;

            // send Email
            Mail::to($emailId)->send(new OTPMail($otp));

            // save OTP to database
            User::where('email', $emailId)->update(['otp'=> $otp]);

            return response()->json([
                'status' => 'success',
                'message' => 'Six degit OTP code sended to your email Id',
            ], 200);

        }
        catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }

    }

    //Verify OTP
    function verifyOTP(Request $request){
        try{
            $validator= Validator::make($request->all(),[
                'otp'=> 'required|digits:6'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Validation Error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user= User::where([
                'email'=> $request->email,
                'otp'=> $request->otp
            ]);

            if($user){
                User::where('email', $request->email)->update(['otp'=>0]);

                $token= JWTToken::createTokenForResetPassword($request->email);

                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP verification successful',
                ], 200)->cookie('token', $token, 60*5 );


            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Invalid OTP or email'
                ], 401);
            }

        }catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }

    // Reset Password
    function resetPassword(Request $request){
        try{
            $validator= Validator::make($request->all(),[
                'password'=> 'required|min:3|confirmed'
            ]);

            if($validator->fails()){
                return response()->json([
                    'status'=> 'fail',
                    'message'=> 'Validation Error',
                    'error'=> $validator->errors()
                ],422);
            }

            $emailId= $request->header('email');
            $password= $request->password;

            User::where('email', $emailId)->update(['password'=> $password]); //update(['password' => Hash::make($password)]);

            return response()->json([
                'status'=> 'success',
                'message'=> 'Password reset successful'
            ], 200);


        }catch(\Throwable $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }


     //Get user profile
     function userProfile(Request $request){
        $emailId= $request->header('email');
        $userId= $request->header('user_id');
        $user= User::where('email', $emailId)
        ->where('id', $userId)
        ->first();

        return response()->json([
            'status'=> 'success',
            'message'=> 'user profile',
            'data'=> $user->only(['email', 'first_name', 'last_name', 'mobile', 'password'])
        ], 200);
     }


    // Update User
    function updateUserProfile(Request $request){
        try{
            $email= $request->header('email');
            $first_name= $request->first_name;
            $last_name= $request-> last_name;
            $mobile = $request-> mobile;
            $password= $request->password;

            User::where('email', $email)->update([
                'first_name'=> $first_name,
                'last_name'=> $last_name,
                'mobile'=> $mobile,
                'password'=> $password
            ]);

            return response()->json([
                'status'=> 'success',
                'message'=> 'user profile updated successfully',
            ], 200);


        }catch(\Throwable $e){
            return response()->json([
                'status'=> 'failed',
                'message' => 'Unable to reset password',
                'error' => app()->environment('production') ? 'Server Error' : $e->getMessage()
            ], 500);
        }
    }

}
