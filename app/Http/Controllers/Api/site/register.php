<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\BidderResource;
use App\Models\Bidder;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use App\Models\BidderVerifyEmail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;


class register extends Controller
{
    public function register(Request $request){
        dd('here');
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255|unique:bidders,phone' ,
            'address' => 'nullable|string|max:250',
            'email' =>  'required|email|max:255|unique:bidders,email' ,
            'password' => 'required|confirmed|min:6|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,bmp,jpg,png|between:1,6000',
            'id_file' => 'nullable|image|mimes:jpeg,bmp,jpg,png|between:1,6000',
            'country_id' => 'required|exists:countries,id',
            'city_id' => "required|exists:cities,id",
            'dob' => 'required|date_format:d/m/Y'
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }

        $bidder = Bidder::create([
            'name'                  => $request->get('name'),
            'email'                     => $request->get('email'),
            'country_id'                   => $request->get('country_id'),
            'city_id'                      => $request->get('city_id'),
            'password'                  => Hash::make($request->get('password')),
            'address'                   => $request->get('address'),
            'phone'                     => $request->get('phone'),
            'dob'                       => $request->get('dob'),

        ]);



        //updat image
        if($request->has('avatar')){
            $path = rand(0,1000000) . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(base_path('public/uploads/bidder') , $path);
            $bidder->avatar   = $path;
        }
        if($request->has('id_file')){
            $path = rand(0,1000000) . time() . '.' . $request->file('id_file')->getClientOriginalExtension();
            $request->file('id_file')->move(base_path('public/uploads/bidder') , $path);
            $bidder->id_file   = $path;
        }
        if($request->has('device_token')){
            $bidder->device_token   = $request->device_token;
        }
        $bidder->save();

        $token = JWTAuth::fromUser($bidder);
        //send cerify email 
            $token=$this->createToken(new BidderVerifyEmail());            
            $verifies=BidderVerifyEmail::where('email',$bidder->email)->delete();
            BidderVerifyEmail::create([
                'email'=>$bidder->email,
                'token' =>$token, 
            ]);
            Mail::to($bidder->email)->send(new VerifyEmail($bidder->name,$token));
        //end send verify email
        return response()->json([
            "status" => true,
            'message'=> 'Check Your Mail To Verify Your Account',
            //'message'=> 'Your Account Successfully Register',
            // 'bidder'   =>new BidderResource($bidder),
            // 'token'  => $token,
        ], 200);
    }
     public function bidderVerifyAccount(Request $request)
     {
         $validator=Validator::make($request->all(),[
             'token' =>'required|exists:bidder_verify_emails,token',
         ]);
         if($validator->fails())
         {
             return response()->json([
                 'status'=>false,
                 'message'=>$validator->errors()->tojson(),
             ]);
         }
         $codeRow=BidderVerifyEmail::where('token', $request->token)->first();
         if(empty($codeRow)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         $bidder=Bidder::where('email', $codeRow->email)->first();
         if(empty($bidder)){
             return response()->json([
                 'status'=>false,
                 'message'=>'Please Write The Right Code',
             ]);
         }
         
         $codeRow->delete();
         $bidder->verified_email=true;
         $bidder->save();
         $token = JWTAuth::fromUser($bidder);
         return response()->json([
            "status" => true,
            'message'=> 'Your Account Successfully Register',
            'bidder'   =>new BidderResource($bidder),
            'token'  => $token,
        ], 200);
             
     }
     public function resendVerifyAccountCode(Request $request){
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
        $bidder=Bidder::where('email', $request->email)->where('verified_email',false)->first();
        if(empty($bidder))
        {
           return response()->json([
               'status'=>false,
               'message'=>'Login Direct You Do Not Verifiy Account',
           ]);
        }
         //send cerify email 
         $token=$this->createToken(new BidderVerifyEmail());            
         $verifies=BidderVerifyEmail::where('email',$bidder->email)->delete();
         BidderVerifyEmail::create([
             'email'=>$bidder->email,
             'token' =>$token, 
         ]);
         Mail::to($bidder->email)->send(new VerifyEmail($bidder->name,$token));
        //end send verify email
        return response()->json([
            "status" => true,
            'message'=> 'Check Your Mail To Verify Your Account',
        ], 200);
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
