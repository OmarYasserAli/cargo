<?php

namespace App\Http\Controllers;

use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Models\BranchInfo;
use App\Models\Mantikqa;
use App\Models\Mohfza;
use App\Models\Shipment;
use App\Models\AllUser;
use App\Models\Shipment_status;
use App\Models\Commercial_name;
use App\Models\Archive;


use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class frou3Controller extends Controller
{
    
    //t7wel sho7nat
    public function frou3_t7wel_sho7nat_manual(){
        
    }
    public function frou3_t7wel_sho7nat_manual_save(Request $request){
        
    }
    public function frou3_t7wel_sho7nat_qr(Request $request){
        $branches=DB::table('branch_info_tb')
        ->select('serial_','name_')
        ->get();
        return view('frou3.t7wel_sho7nat.qr',compact('branches'));
    }
    public function frou3_t7wel_sho7nat_qr_save(Request $request){
        $status=array(1);

        $branch=DB::table('branch_info_tb')
        ->where('serial_',$request->status)
        ->select('serial_','name_')->first();
        $user = $user = auth()->user();
        
       
         DB::table('add_shipment_tb_')
         ->whereIn('add_shipment_tb_.code_', $request->code)
         ->where('add_shipment_tb_.transfere_1', '')
         ->where('add_shipment_tb_.status_', 1)
          
        ->join('transfer_prices_main_tb', function($join){
            $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
            $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id'); 
         })

            ->update(['add_shipment_tb_.transfere_1'=>$branch->name_,
            'add_shipment_tb_.transfer_coast_1' =>'transfer_prices_main_tb.price_' ,
            'TRANSFERE_ACCEPT_REFUSE'=>2,
            'tarikh_el7ala'=>Carbon::now()->format('Y-m-d'),
            'Ship_area_'=>$branch->name_]);
           

            DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_1','!=', '')
            ->where('add_shipment_tb_.status_', 1)
             
           ->join('transfer_prices_main_tb', function($join){
               $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
               $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id'); 
            })
   
               ->update(['add_shipment_tb_.transfere_2'=>$branch->name_,
               'add_shipment_tb_.transfer_coast_2' =>'transfer_prices_main_tb.price_' ,
            'TRANSFERE_ACCEPT_REFUSE'=>2,
            'tarikh_el7ala'=>Carbon::now()->format('Y-m-d'),
            'Ship_area_'=>$branch->name_

            ]);

        

              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
            ], 200);      
        }

    
    
    public function accept_frou3_t7wel(){
        
    }
    public function accept_frou3_t7wel_save(Request $request){
        
    }
    //end t7weel sho7nat

    //rag3
    public function frou3_t7wel_rag3_qr(Request $request){
        $branches=DB::table('branch_info_tb')
        ->select('serial_','name_')
        ->get();
        return view('frou3.t7wel_rag3.qr',compact('branches'));
    }
    public function frou3_t7wel_rag3_qr_save(Request $request){
        $status=array(1);

        $branch=DB::table('branch_info_tb')
        ->where('serial_',$request->status)
        ->select('serial_','name_')->first();
      
        $user = $user = auth()->user();
        
     
         DB::table('add_shipment_tb_')
         ->whereIn('add_shipment_tb_.code_', $request->code)
         ->where('add_shipment_tb_.transfere_2','!=' ,'')
         ->where('add_shipment_tb_.status_', 9)
          
        ->join('transfer_prices_main_tb', function($join){
            $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
            $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id'); 
         })

            ->update(['add_shipment_tb_.transfere_2'=>'', 'add_shipment_tb_.TRANSFERE_ACCEPT_REFUSE'=>3,
            'tarikh_el7ala'=>Carbon::now()->format('Y-m-d'),
            'Ship_area_'=>$branch->name_ ]);
           

            DB::table('add_shipment_tb_')
            ->whereIn('add_shipment_tb_.code_', $request->code)
            ->where('add_shipment_tb_.transfere_2' ,'')
            ->where('add_shipment_tb_.transfere_1' ,'!=','')
            ->where('add_shipment_tb_.status_', 9)
             
           ->join('transfer_prices_main_tb', function($join){
               $join->on('transfer_prices_main_tb.mantika_id', '=', 'add_shipment_tb_.mantika_id');
               $join->on('transfer_prices_main_tb.mo7afaza_id','=','add_shipment_tb_.mo7afaza_id'); 
            })
   
               ->update(['add_shipment_tb_.transfere_1'=>'', 'add_shipment_tb_.TRANSFERE_ACCEPT_REFUSE'=>3,
               'tarikh_el7ala'=>Carbon::now()->format('Y-m-d'),
               'Ship_area_'=>$branch->name_ ]);

        

              return response()->json([
                'status' => 200,
                'message' => 'تم التحويل',
            ], 200);      
        }


    //end rag3


}