<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\BidderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authentication extends Controller
{
        public function authenticate(Request $request){

            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
            ]);

            if($validator->fails()){
                return response::falid($validator->errors(), 422);
            }
            
            $credentials = ['email' => $request->email, 'password' => $request->password];
           
            
            try {
                if (! $token = auth('bidder')->attempt($credentials)) {
                    return response::falid('passwored or email is wrong', 400);
                }
            } catch (JWTException $e) {
                return response::falid('some thing is wrong', 500);
            }

            $bidder= auth('bidder')->user();
            if($bidder->verified_email==false)
            {
                return response()->json([
                    'status'  => false,
                    'message' => 'You Need To Verify Your Account',
                ], 200);   
            }
            if($request->has('device_token')){
                    $bidder->device_token   = $request->device_token;
                    $bidder->save();
                }

            return response()->json([
                    'status'  => true,
                    'message' => 'succeess',
                    'Bidder'=> new BidderResource($bidder),
                    'token'   => $token,
                ], 200);
    }
        
        public function logout(Request $request){

            Auth::guard('bidder')->logout();

            return response::suceess('logout success', 200);
        }
}
