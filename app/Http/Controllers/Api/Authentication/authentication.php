<?php

namespace App\Http\Controllers\Api\Authentication;
use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Http\Resources\all_userResource;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\PatientReource;
use App\Http\Resources\PatientResource;
use App\Models\AllUser;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class authentication extends Controller
{
    public function authenticate(Request $request){
        $credentials = ['USERNAME' => $request->username, 'password' => $request->password]; 
          
        try {
            if (! $token = auth('all_user')->attempt($credentials)) {
                return response()->json([
                    'status'  => false,
                    'message' =>'passwored or username is wrong' ,
                ], 404); 
            }
        } catch (JWTException $e) {
            return response()->json([
                'status'  => false,
                'message' =>'some thing is wrong' ,
            ], 404); 
        }
        $user = AllUser::where('USERNAME',$request->username)->first();

        return response()->json([
            'status'  => true,
            'message' => 'succeess',
            'user'=> new all_userResource($user),
            'token'   => $token,
        ], 200);



    }
    public function logout(Request $request){
        if (auth('doctor')->user() == null && auth('patient')->user() == null) {
            return response::falid('user_not_found', 404);
        }
        if(Auth::guard('patient')->check()){
            $user = auth('patient')->user();
            Patient::where('id',$user->id)->update(
                [
                    'login'=>false,
                    'token'=>null,
                ]
                );
            
        Auth::guard('patient')->logout();
        }elseif(Auth::guard('doctor')->check()){
            $user = auth('doctor')->user();
            Doctor::where('id',$user->id)->update(
                [
                    'login'=>false,
                    'token'=>null,

                ]
                );
            
        Auth::guard('doctor')->logout();

        }
        return response()->json([
            'status'  => true,
            'message' => 'Logout Successfully',
        ], 200);          
    }
}