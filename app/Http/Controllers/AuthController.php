<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Helpers\AppHelper;

class AuthController extends Controller
{
    //

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
                ], 401);
            }

        $user = User::where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user'=> $user,
                'googlemapapi'=> env('GOOGLEMAPAPI')
        ]);
    }

    public function verifyOtp(Request $request){

        $user = User::where('mobile', $request['mobile'])
                 ->where('otp', $request['otp'])->first();
        if(!$user){
            return response()->json([
                'status' => 0,
                'message' => 'Invalid OTP'
                ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
                'status' => 1,
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user'=>$user
        ]);
    }

    public function getOtp(Request $request){
        
        $user = User::where('mobile', $request['mobile'])
            ->where('imei', $request['imei'])
            ->first();
        if(!$user){
            return response()->json([
                'status' => 0,
                'message' => 'Mobile number not registered'
            ]);
        }

        $randomOtp = rand(1000, 9999); 
        User::where('mobile',  $request['mobile'])
                    ->update(['otp' => $randomOtp]);
        
        AppHelper::sendLoginOtp($request['mobile'], $randomOtp);
        return response()->json([
            'status' => 1,
            'otp' => $randomOtp,
        ]);
    }
}
