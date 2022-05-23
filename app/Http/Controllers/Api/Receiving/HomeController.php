<?php
namespace App\Http\Controllers\Api\Receiving;

use App\CustomClass\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class HomeController
{
    public function myShip(Request $request)
    {
       
        try {
            if (!$user = auth('all_user')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        if ($user->type_ != 'مندوب استلام') {
            return response::falid('user_not_found', 400);

        }
        $all = DB::table('add_shipment_tb_')->where('Status_', 2)->where('Delivery_take_shipment_ID', $user->code_)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'all' => $all,
            'sum' => count($all),
        ], 200);

    }
    public function shipToclient(Request $request)
    {
        try {
            if (!$user = auth('all_user')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        if ($user->type_ != 'مندوب استلام') {
            return response::falid('user_not_found', 400);

        }
        
        $all = DB::table('add_shipment_tb_')->where('branch_', $user->branch)->where('Status_',3)->where('commercial_name_',$request->commercial_name_)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'all' => $all,
            'sum' => count($all),
        ], 200);

    }
    public function search_ship(Request $request)
    {
        try {
            if (! $user = auth('all_user')->user()) {
                return response::falid('user_not_found', 404);
            }
    
        } catch (TokenExpiredException $e) {
    
            return response::falid('token_expired', 400);
    
        } catch (TokenInvalidException $e) {
    
            return response::falid('token_invalid', 400);
            
        } catch (JWTException $e) {
    
            return response::falid('token_absent', 400);
        }
        if ($user->type_ != 'مندوب استلام') {
            return response::falid('user_not_found', 400);

        }
        $ship = DB::table('add_shipment_tb_')->where('Delivery_take_shipment_ID',$user->code_)->where('reciver_phone_',$request->reciver_phone_)->paginate(10);
       return response::suceess('success', 200,"ship",$ship);

    }
    public function all_commeric(Request $request)
    {
        try {
            if (! $user = auth('all_user')->user()) {
                return response::falid('user_not_found', 404);
            }
    
        } catch (TokenExpiredException $e) {
    
            return response::falid('token_expired', 400);
    
        } catch (TokenInvalidException $e) {
    
            return response::falid('token_invalid', 400);
            
        } catch (JWTException $e) {
    
            return response::falid('token_absent', 400);
        }
        if ($user->type_ != 'مندوب استلام') {
            return response::falid('user_not_found', 400);

        }
        $ship = DB::table('add_commercial_names_tb')->where('branch',$user->branch)->paginate(10);
       return response::suceess('success', 200,"ship",$ship);

    }
   
}
