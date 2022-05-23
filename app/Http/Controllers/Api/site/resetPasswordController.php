<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ForgetPassword;
use App\Models\Bidder;
use App\Models\BidderForgetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
class resetPasswordController
{
  
     public function bidderForgetPassword(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'email' =>'required|email|exists:bidders,email',
         ]);
         if($validator->fails())
         {
             return response()->json([
                 'status'=>false,
                 'message'=>$validator->errors()->tojson(),
             ]);
         }
         $bidder=Bidder::where('email', $request->email)->where('verified_email',true)->first();
         if(empty($bidder))
         {
            return response()->json([
                'status'=>false,
                'message'=>'Please Verify Your Account First',
            ]);
         }
         $token=$this->createToken(new BidderForgetPassword());
         
         $verifies=BidderForgetPassword::where('email',$request->email)->delete();
 
         $verify=BidderForgetPassword::create([
             'email' =>$request->email,
             'token' =>$token, 
         ]);
 
         if(!empty($bidder))
         {
             Mail::to($request->email)->send(new ForgetPassword($bidder->name,$token));
             return response()->json([
                 'status'=>true,
                 'message'=>'Code Send To Your Email Please Check Your Mail To Reset Your Password'
             ]);
         }
     }
     public function bidderResetPassword(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'token' =>'required|exists:bidder_forget_passwords,token',
             'password'  =>'required | min:6 |max : 20 | confirmed',
             'password_confirmation'=>"same:password",
         ]);
         if($validator->fails())
         {
             return response()->json([
                 'status'=>false,
                 'message'=>$validator->errors()->tojson(),
             ]);
         }
        //  $arr=explode('-',$request->token);
         $tokenRow=BidderForgetPassword::where('token', $request->token)->first();
         if(empty($tokenRow)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         $bidder=Bidder::where('email', $tokenRow->email)->first();
         if(empty($bidder)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         $bidder->update([
             'password'=>Hash::make($request->password),
         ]);
         $tokenRow->delete();
        
             return response()->json([
                 'status'=>true,
                 'message'=>'Your Password Reset Successfully',
             ]);
     }
     public function createToken($model)
    {
        $token=Str::random(6);
        if($model->where('token',$token)->get()->count()>0)
        {
            $this->createToken($model);
        }
        return $token;
    }
}
