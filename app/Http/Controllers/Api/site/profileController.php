<?php

namespace App\Http\Controllers\Api\site;

use App\CustomClass\response;
use App\Http\Resources\BidderResource;
use App\Http\Resources\employerResource;
use App\Models\Bidderrequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\bidders;
use App\Models\Vehicle;
use App\Models\Employer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\DB;

class profileController
{
    public function getProfile(Request $request){
        try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

            return response::suceess('success', 200, 'bidder',new BidderResource($bidder));

    }

    public function updateBidderProfile(Request $request){

        // return $request;
        try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);

        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone' ,
            'address' => 'nullable|string|max:250',
            'email' =>  'required|email|max:255|unique:users,email' ,
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

        //update data
        if($request->has('password')){
            if(Hash::check($request->password, $bidder->password)){
                $bidder->password  = Hash::make($request->get('password'));

            } else {
                return response::falid('old password is wrong', 400);
            }
        }

        if($request->has('name')){
            $bidder->name      = $request->get('name');
        }
        if($request->has('email')){
            $bidder->email         = $request->get('email');
        }

        if($request->has('country_id')){
            $bidder->country_id       = $request->get('country_id');
        }

        if($request->has('city_id')){
            $bidder->city_id          = $request->get('city_id');
        }

        if($request->has('address')){
            $bidder->address          = $request->get('address');
        }

        if($request->has('phone')){
            $bidder->phone          = $request->get('phone');
        }

        if($request->has('dob')){
            $bidder->dob          = $request->get('dob');
        }


        //updat cv
        if($request->has('avatar')){
            if($bidder->avatar == null){
                $path = rand(0,1000000) . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                $request->file('avatar')->move(base_path('public/uploads/bidder') , $path);
                $bidder->avatar   = $path;
            } else {
                $oldAvatar = $bidder->avatar;

                //updat cv
                $path = rand(0,1000000) . time() . '.' . $request->file('avatar')->getClientOriginalExtension();
                $request->file('avatar')->move(base_path('public/uploads/bidder') , $path);
                $bidder->avatar   = $path;

                //delet old cv
                if(file_exists(base_path('public/uploads/bidder/') . $oldAvatar)){
                    unlink(base_path('public/uploads/bidder/') . $oldAvatar);
                }
            }
        }
//updat id_file
            if($request->has('id_file')){
                if($bidder->id_file == null){
                    $path = rand(0,1000000) . time() . '.' . $request->file('id_file')->getClientOriginalExtension();
                    $request->file('id_file')->move(base_path('public/uploads/bidder') , $path);
                    $bidder->id_file   = $path;
                } else {
                    $oldAvatar = $bidder->id_file;

                    //updat cv
                    $path = rand(0,1000000) . time() . '.' . $request->file('id_file')->getClientOriginalExtension();
                    $request->file('id_file')->move(base_path('public/uploads/bidder') , $path);
                    $bidder->id_file   = $path;

                    //delet old cv
                    if(file_exists(base_path('public/uploads/bidder/') . $oldAvatar)){
                        unlink(base_path('public/uploads/bidder/') . $oldAvatar);
                    }
                }
            }
        if($bidder->save()){
            return response::suceess('update profile successfully', 200, 'bidder',new BidderResource($bidder));
        } else {
            return response::falid('update profile falid', 400);
        }
    }
    public function changebidderPassword(Request $request)
    {
          // return $request;
          try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 401);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 401);

        } catch (JWTException $e) {

            return response::falid('token_absent', 401);
        }
        $validator = Validator::make($request->all(), [
            'oldPassword'              => 'required|string|min:5',
            'password'                => 'required|string|min:6',
            'password_confirmation'   => 'required|string|same:password',
        ]);

        if($validator->fails()){
            return response()->json([
                "status"    => false,
                'message'   => $validator->errors()->toJson(),
            ], 422);
        }
        if(Hash::check($request->oldPassword, $bidder->password)){
            $bidder->password  = Hash::make($request->get('password'));
            $bidder->save();
            return response::suceess('password change Successfully', 400);

        } else {
            return response::falid('old password is wrong', 400);
        }

    }

}
