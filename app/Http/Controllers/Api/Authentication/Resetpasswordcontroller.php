<?php

namespace App\Http\Controllers\Api\Authentication;
use App\Http\Controllers\Controller;

use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ForgetPassword;
use App\Models\Doctor;
use App\Models\Doctor_forget_email;
use App\Models\Doctor_verify_email;
use App\Models\Patient;
use App\Models\Teacher;
use App\Models\TeacherForgetPassword;
use App\Notifications\SendKey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class Resetpasswordcontroller
{
  
     public function doctorForgetPassword(Request $request)
     {
         
         $doctor=Doctor::where('email', $request->email)->where('verified_email',true)->first();
         $patient = Patient::where('email',$request->email)->where('verified_email',true)->first();
         
         if(empty($doctor) && empty($patient))
         {
            return response()->json([
                'status'=>false,
                'message'=>'Please Verify Your Account First',
            ]);
         }
         if($doctor || $patient){
         $token=Str::random(6);
         
         $verifies=Doctor_forget_email::where('email',$request->email)->delete();
 
         $verify=Doctor_forget_email::create([
             'email'=>$request->email,
             'token' =>$token, 
         ]);
 
         if(!empty($doctor))
         {
            //  Mail::to($request->email)->send(new ForgetPassword($doctor->full_name,$token));
            Notification::send($doctor,new SendKey($token));
             return response()->json([
                 'status'=>true,
                 'message'=>'Code Send To Your Email Please Check Your Mail To Reset Your Password'
             ]);
         }else if(!empty($patient)){
            // Mail::to($request->email)->send(new ForgetPassword($patient->name,$token));
            Notification::send($patient,new SendKey($token));
             return response()->json([
                 'status'=>true,
                 'message'=>'Code Send To Your Email Please Check Your Mail To Reset Your Password'
             ]);
         }
        }
     }
     public function doctorResetPassword(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'token' =>'required|exists:doctor_forget_emails,token',
             'password'  =>'required | min:6| confirmed',
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
         $tokenRow=Doctor_forget_email::where('token', $request->token)->where('verified',true)->first();
         if(empty($tokenRow)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         ///////////////////
         $doctor=Doctor::where('email', $tokenRow->email)->first();
         $patient=Patient::where('email', $tokenRow->email)->first();
         if(empty($doctor) && empty($patient)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         if($doctor != null){
            if($doctor->token != null){

            JWTAuth::setToken($doctor->token);
            JWTAuth::invalidate();
            }

         $doctor->update([
             'password'=>Hash::make($request->password),
             'login'=>false,
             'token'=>null,
         ]);

        }else if($patient != null){
            if($patient->token != null){
               JWTAuth::setToken($patient->token);
               JWTAuth::invalidate();
            }
            $patient->update([
                'password'=>Hash::make($request->password),
                'login'=>false,
                'token'=>null,


            ]); 
        }
         $tokenRow->delete();
      
             return response()->json([
                 'status'=>true,
                 'message'=>'Your Password Reset Successfully',
             ]);
     }
     public function verifiedToken(Request $request){
        $token = Doctor_forget_email::where('token',$request->token)->where('verified',false)->first();
        if($token)
        {
            $token->update([
                'verified'=>true,
            ]);
           return response()->json([
               'status'=>true,
               'message'=>'verified succefully',
           ]);

        }else{
           return response()->json([
               'status'=>false,
               'message'=>'Please Write the right code',
           ]);
        }
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
