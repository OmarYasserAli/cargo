<?php
namespace App\Http\Controllers\Api\Client;
use Illuminate\Http\Request;
use App\CustomClass\response;
use Dotenv\Result\Success;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

Class HomeController 
{
    // use AuthTrait;
    public function Store_shipment(Request $request)
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
        if($user -> type_ != 'عميل')  
        {
            return response::falid('user_not_found', 400);

        }
        $store = DB::table('add_shipment_tb_')->where('client_ID_',$user->code_)->where('status_',$request->param)->get();
        return response()->json([
            'status'=>200,
            'message'=>'success',
            'store'=>$store,
            'sum'=>count($store)
        ],200);
        // return response::suceess('success', 200,"store",$store);
     
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
        if($user -> type_ != 'عميل')  
        {
            return response::falid('user_not_found', 400);

        }
        $ship = DB::table('add_shipment_tb_')->where('client_ID_',$user->code_)->where('reciver_phone_',$request->reciver_phone_)->paginate(10);
       return response::suceess('success', 200,"ship",$ship);

    }
 
}