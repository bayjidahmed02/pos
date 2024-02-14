<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Mail\OTPmail;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function registration(Request $request)
    {
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response([
                'status' => 'success',
                'message' => 'User has been created successfully'
            ]);
        } catch (Exception $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where('email', $email)->where('password', $password)->select('id')->first();

            if ($user !== null) {
                $token = JWTToken::createToken($email, $user->id);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login Success',
                ])->cookie('token', $token, 60 * 24 * 30);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Email and Password does not match'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'unauthorized'
            ]);
        }
    }
    public function sendOTP(Request $request)
    {
        try {
            $email = $request->input('email');
            $otp = rand(1000, 9999);
            $user = User::where('email', $email)->first();
            if ($user) {
                Mail::to($email)->send(new OTPmail($otp));
                User::where('email', $email)->update(['otp' => $otp]);
                return response()->json([
                    'status' => 'success',
                    'message' => 'OTP has been sent in your Email'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'cannot find user'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function verifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $user = User::where('email', $email)->where('otp', $otp)->first();
        if ($user) {
            User::where('email', $email)->update(['otp' => '0']);
            $token = JWTToken::setPassword($email);
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified'
            ])->cookie('token', $token, 30 * 24 * 30);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'invalid OTP'
            ]);
        }
    }
    public function resetPassword(Request $request)
    {
        try {
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email', $email)->update(['password' => $password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Password Reset Successful'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function logout(Request $request)
    {
        return redirect('/login')->cookie('token', -1);
    }

    public function profileDetails(Request $request)
    {
        $email = $request->header('email');
        $user = User::where('email', $email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'User info showing',
            'data' => $user
        ]);
    }
    public function updateProfile(Request $request)
    {
        try {
            $email = $request->header('email');
            $firstName = $request->input('firstName');
            $lastName = $request->input('lastName');
            $mobile = $request->input('mobile');
            User::where('email', $email)->update([
                'firstName' => $firstName,
                'lastName' => $lastName,
                'mobile' => $mobile,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Update Successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'success',
                'message' => $e->getMessage()
            ]);
        }
    }
}
