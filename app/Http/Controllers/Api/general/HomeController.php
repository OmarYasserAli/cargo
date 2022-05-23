<?php

namespace App\Http\Controllers\Api\general;

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

class HomeController extends Controller
{
    public function HomePage(Request $request)
    {
       
       
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        $statuses= Shipment_status::orderBy('sort_no')->where('code_' ,'!=',10)
        ->UserTypeFilter($user->type_,$user->code_)
        ->select('code_','name_')->get()->toArray();

        foreach($statuses as $key=> $status){
            
            $shipments = Shipment::where('Status_',$status['code_'])
            ->UserType($user->type_,$user->code_);
            if(isset(request()->commercial_name)){
                $shipments=$shipments->where('commercial_name_',request()->commercial_name);
            }
            $shipments=$shipments->select(DB::raw('count(*) as cnt'))
            ->groupBy( 'Status_')
            ->first();
            $statuses[$key]['cnt'] = $shipments ? $shipments->cnt : 0;
            
        }
        if($user->type_=='مندوب تسليم')
            $statuses[0]['name_']='شحناتى';
        if($user->type_=='مندوب استلام')
           {
             //  dd('d');
            $statuses[1]['name_']='شحناتى';
            $statuses[0]['cnt']=$shipment = Shipment::where('Status_',3)
            ->where('branch_',$user->branch)
            //->UserType($user->type_,$user->code_)
            ->count();
           }
        
       
      
        if(!isset(request()->commercial_name))
           { $cummercial_names = DB::table('commercial_name_for_main_comp')
            ->select('commercial_name_for_main_comp.name_','commercial_name_for_main_comp.code_')
            ->where('code_client', request()->user_id)
            ->groupBy( 'commercial_name_for_main_comp.name_' ,'commercial_name_for_main_comp.code_')
                ->get();
            if($user->type_=='مندوب استلام')
           {
              
             $cummercial_names= $this->get_estlam_commercial_names( $request,$user)->get();
          
           }
                $cummercial_names_count= $cummercial_names->count();

            }
        else{
            $cummercial_names =  request()->commercial_name;
            $cummercial_names_count  =1;
        }
        return response()->json([
            'status' => 200,
            'message' => 'success',
            "type"=> $user->type_,
            'commercial_name_count'=>$cummercial_names_count,
            'commercial_name'=>$cummercial_names,
            'all' => $statuses,
            
            
        ], 200);
       
    }

    public function shipments(Request $request)
    { 

        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $offset=0; $limit=10;
            if(isset(request()->offset ))   $offset =request()->offset;
            if(isset(request()->limit ))   $limit =request()->limit;
            
            $shipments_null_date = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }])
            ->where('status_',request()->code)
            ->UserType($user->type_,$user->code_)
            ->where('tarikh_tasdid_el3amil' ,'')
            ->where('tarikh_el7ala' ,'');
            if(isset($request->commercial_name)){
                $shipments_null_date = $shipments_null_date->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }

            $shipments = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }])
            ->where('status_',request()->code);
            
           
            
            if($user->type_== 'مندوب استلام' && request()->code ==3){
                $shipments = $shipments->where('branch_',$user->branch);
                
            }else{
                $shipments = $shipments->UserType($user->type_,$user->code_);
            }
            if(isset($request->commercial_name)){
                $shipments = $shipments->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
           

            // $shipments_not_mosadad = Shipment::with(['Branch_user' => function ($query) {
            //     $query->select('code_','phone_');
            // }])
            // ->where('status_',request()->code)
            // ->UserType($user->type_,$user->code_)
            // ->where('el3amil_elmosadad', '!=' ,'مسدد' );
            // if($user->type_== 'مندوب استلام'){
            //     $shipments = $shipments->where('Status_',3)->where('branch_',$user->branch);
            // }
            // if(isset($request->commercial_name)){
            //     $shipments_not_mosadad = $shipments_not_mosadad->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            // }
            
      
           
             
            $all_shipments = $shipments;

            if($user->type_ =='عميل'){
               
                if(isset( request()->date_from))
                    $all_shipments= $all_shipments->where('date_' ,'>=',DATE($request->date_from) );
                if(isset( request()->date_to))
                    $all_shipments= $all_shipments->where('date_' ,'<=' ,DATE($request->date_to) );
                   
            }else{
                if(isset( request()->date_from))
                    $all_shipments= $all_shipments->where('tarikh_el7ala' ,'>=',DATE( request()->date_from) );
                if(isset( request()->date_to))
                    $all_shipments= $all_shipments->where('tarikh_el7ala' ,'<=',DATE( request()->date_to) );

            }
            

            $counter= $all_shipments->get();
            
            $totalCost = $counter->sum('shipment_coast_');
            if($user->type_=='عميل')
                $tawsilCost = $counter->sum('tawsil_coast_');
            if($user->type_=='مندوب استلام')
                $tawsilCost = $counter->sum('tas3ir_mandoub_estlam');
            if($user->type_=='مندوب تسليم')
                $tawsilCost = $counter->sum('tas3ir_mandoub_taslim');
            
            $netCost =  $totalCost-$tawsilCost;

            $count_all = $counter->count();
            if($request->page == -100)
                $request->limit=$count_all;
            $all = $all_shipments->paginate($request->limit ?? 10);
       
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

            
        
    }

    public function accounting(Request $request)
    {
            $data=[];
            $Offset=0; $limit=10;
            if(isset(request()->offset ))   $offset =request()->offset;
            if(isset(request()->limit ))   $limit =request()->limit;
            $user = DB::table("all_users")->where('code_' ,request()->user_id)->first();

           
            $query = DB::table('add_shipment_tb_')
            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
            
            //mosadada
            if($user->type_ =='عميل'){
                $all= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad",'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim",'مسدد'); 
            }
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if($user->type_=='عميل'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب استلام'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب تسليم'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'<=',DATE( $request->date_to) );
            }
            $countall = $all->count();
            
            
            $ar=[];
            $ar['name_']= 'مسدد';
            $ar['cnt']= $countall;
            $ar['code_']= 1;
            array_push($data,$ar);
          
            //3*er mosadada
            $query = DB::table('add_shipment_tb_')
            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
            $all= $query->where('add_shipment_tb_.Status_',"!=",8);
            if($user->type_ =='عميل'){
                $all= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
               $all= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('add_shipment_tb_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('add_shipment_tb_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all->count();
           
           
            $ar=[];
            $ar['name_']= 'غير مسدد';
            $ar['cnt']= $countall;
            $ar['code_']= 2;
            array_push($data,$ar);

            //mosta7aqa
            $query = DB::table('add_shipment_tb_')
           
            ->leftJoin('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
            $all_mosta7aqa= $query->where('add_shipment_tb_.Status_',7);
            
            if($user->type_ =='عميل'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all_mosta7aqa= $query->where('add_shipment_tb_.Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
            if(isset($request->commercial_name)){
                $all_mosta7aqa = $all_mosta7aqa->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('add_shipment_tb_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('add_shipment_tb_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all_mosta7aqa->count();
           
            $ar=[];
            $ar['name_']= 'مستحق';
            $ar['cnt']= $countall;
            $ar['code_']= 3;
            array_push($data,$ar);
            
            //archive
            $query = DB::table('archive_')
            
            ->leftJoin('add_branch_users_tb', 'archive_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
            ->select('archive_.*', 'add_branch_users_tb.phone_ as mabdob phone' );
           
            if($user->type_ =='عميل'){
                $all= $query->where('archive_.client_ID_',$user->code_);
                ///->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('archive_.Delivery_take_shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('archive_.Delivery_Delivered_Shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
            if(isset($request->commercial_name)){
                $all = $all->where('archive_.commercial_name_', $request->commercial_name);
            }
            if(isset( $request->date_from))
                $query= $query->where('archive_.date_' ,'>=',DATE($request->date_from) );
            if(isset( $request->date_to))
                $query= $query->where('archive_.date_' ,'<=',DATE( $request->date_to) );
            $countall = $all->count();
            if(isset(request()->offset ) && isset(request()->limit ))
                $all = $all->skip($offset*$limit)->take($limit);
            $all = $all ->get();
            $ar=[];
            $ar['name_']= 'ارشيف';
            $ar['cnt']= $countall;
            $ar['code_']= 4;
            array_push($data,$ar);


            
        if(!isset(request()->commercial_name))
        { $cummercial_names = DB::table('commercial_name_for_main_comp')
         ->select('commercial_name_for_main_comp.name_','commercial_name_for_main_comp.code_')
         ->where('code_client', request()->user_id)
         ->groupBy( 'commercial_name_for_main_comp.name_' ,'commercial_name_for_main_comp.code_')
             ->get();
             $cummercial_names_count= $cummercial_names->count();
         }
     else{
         $cummercial_names =  request()->commercial_name;
         $cummercial_names_count  =1;
     }

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'commercial_name_count'=>$cummercial_names_count,
                'commercial_name'=>$cummercial_names,
                "type"=> $user->type_,
                'all' => $data,
            ], 200);
            
        
    }
    public $accounting_types=[1=>'mosadada',2=>'not_mosadada',3=>'mosta7aqa',4=>'archive'];
    public function accounting_shipments(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $offset=0; $limit=10;
            if(isset(request()->offset ))   $offset =request()->offset;
            if(isset(request()->limit ))   $limit =request()->limit;
            
            $shipments_null_date = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);
            if(isset(request()->code))
                $shipments_null_date = $shipments_null_date->where('status_',request()->code);
            $shipments_null_date = $shipments_null_date->UserType($user->type_,$user->code_)
            ->where('tarikh_tasdid_el3amil' ,'');
            
            if(isset($request->commercial_name)){
                $shipments_null_date = $shipments_null_date->where('commercial_name_', $request->commercial_name);
            }

            $shipments = Shipment::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);
            
            $shipments = $shipments->UserType($user->type_,$user->code_);

            if(isset($request->commercial_name)){
                $shipments = $shipments->where('commercial_name_', $request->commercial_name);
            }
            // if(isset( request()->date_from))
            //     $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'>=',DATE( request()->date_from) );
            // if(isset( request()->date_to))
            //     $shipments= $shipments->where('tarikh_tasdid_el3amil' ,'<=',DATE( request()->date_to) );

           
                     
            $all_shipments = $shipments;
            //->union($shipments_null_date);
          
            $all_shipments=$this->filter_accounting_type($all_shipments, $this->accounting_types[request()->type],$user,$request);
            $counter= $all_shipments->get();
           
            $totalCost = $counter->sum('shipment_coast_');
            if($user->type_=='عميل')
                $tawsilCost = $counter->sum('tawsil_coast_');
            if($user->type_=='مندوب استلام')
                $tawsilCost = $counter->sum('tas3ir_mandoub_estlam');
            if($user->type_=='مندوب تسليم')
                $tawsilCost = $counter->sum('tas3ir_mandoub_taslim');
            $netCost =  $totalCost-$tawsilCost;
            $count_all = $counter->count();
            $all = $all_shipments->paginate($request->limit ?? 10);
       
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

          
        
    }

    public function estlam_shipments_count(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);

            $shipment = Shipment::where('Status_',3)
            ->where('branch_',$user->branch)
            ->UserType($user->type_,$user->code_)
            ->count();
            return response()->json([
                'status' => 200,
                'message' => 'success',
                // 'commercial_name_count'=>$cummercial_names_count,
                // 'commercial_name'=>$cummercial_names,
                "type"=> $user->type_,
                'count' => $shipment,
            ], 200);

    }
    public function estlam_shipments(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);

            $shipment = Shipment::with(['Branch_user' => function ( $query) {
                $query->select('code_','phone_');
             }])->where('Status_',3)
            ->where('branch_',$user->branch)
            ->UserType($user->type_,$user->code_);
            // if(isset($request->commercial_name)){
            //     $shipment = $shipment->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            // }
            $totalCost = $shipment->sum('shipment_coast_');
            //$tawsilCost = $shipment->sum('tawsil_coast_');
            $tawsilCost = $counter->sum('tas3ir_mandoub_estlam');
            $netCost =  $totalCost-$tawsilCost;
            $count_all = $shipment->count();
            $all = $shipment->paginate($request->limit ?? 10);
            if(!isset(request()->commercial_name))
        
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            // 'commercial_name_count'=>$cummercial_names_count,
            // 'commercial_name'=>$cummercial_names,
            'data' => $all->items(),
        ]);
    }
    public function estlam_commercial_names(Request $request){
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب استلام")
            return response::falid('user_not_estlam', 403);

        
            $cummercial_names = DB::table('add_commercial_names_tb')
            ->select('add_commercial_names_tb.name','add_commercial_names_tb.code')
            ->where('branch', $user->branch)
            ->groupBy( 'add_commercial_names_tb.name' ,'add_commercial_names_tb.code')
                 ->get();
                 $cummercial_names_count= $cummercial_names->count();
             
                 return response()->json([
                    'status' => 200,
                    'message' => 'success',
                    'commercial_name_count'=>$cummercial_names_count,
                    'commercial_name'=>$cummercial_names,
                    "type"=> $user->type_,
                    
                ], 200);
    }
    public function get_estlam_commercial_names(Request $request,$user){
            return DB::table('add_commercial_names_tb')
            ->select('add_commercial_names_tb.name','add_commercial_names_tb.code')
            ->where('branch', $user->branch)
            ->distinct()
                ;
    }

    public function get_shipment_delevery(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        if($user->type_ != "مندوب تسليم")
            return response::falid('user_not_delevery', 403);

            $shipment = Shipment::where('Status_',4)
            ->where('code_',request()->shipment_code)
            ->where('Delivery_Delivered_Shipment_ID',request()->user_id)
            //->UserType($user->type_,$user->code_)
            ;

            return  $this->handleMultiShipmentelequent( $shipment, $user, $request);
        

    }
    public function estlm_rag3(Request $request)
    {
        
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $shipments = Shipment::where('Status_',9)
            ->whereIn('code_',$request->shipment_code)
            ->where('client_ID_',$request->user_id);

           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function tanfez_estlm_rag3(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            try {
                foreach($shipments as $shipment)
                { 
                    $shipment->Status_= 8 ; 
                    $shipment->shipment_coast_=0 ; 
                    $shipment->total_=0; 
                    $shipment->tarikh_el7ala   = Carbon::now()->format('Y-m-d') ; 
                    $shipment->save();
                }
                
                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => 500,
                        'message' => 'DB error'.$e,
                    ]);
                }
            
            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }
    public function estlam(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            
            $all = Shipment::where('Status_',3)
            ->whereIn( 'code_' ,$request->shipment_code);
            $ret_all=$all;
            $shipments=$all->get();
            
            try {
                foreach($shipments as $shipment)
            {    
                $shipment->Status_=2;
                $shipment->tarikh_el7ala  = Carbon::now()->format('Y-m-d');
                $shipment->mandoub_estlam = $user->name_;
                $shipment->Delivery_take_shipment_ID = request()->user_id;

                $tas3ir=DB::table('mandoub_estlam_tas3irtb')->select('price_')
                ->where('mandoub_ID',request()->user_id)
                ->where('area_name_',$shipment->mantqa_)
                ->where('city_name_',$shipment->mo7afza_)
                ->first();
                $price=0;
                if(isset($tas3ir)) $price=$tas3ir->price_;
                $shipment->tas3ir_mandoub_taslim = $price;
                $shipment->save();
               
            }
                
                    DB::commit();
                    // all good
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'status' => 500,
                        'message' => 'DB error'.$e,
                    ]);
                }
            
           
            if(($shipments->count()  ))
                $ret_all=Shipment::where( 'code_' ,($request->shipment_code)[0]);
            return  $this->handleMultiShipmentelequent( $ret_all, $user, $request); 
    }
    public function estlam_check(Request $request)
    {
        
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $shipments = Shipment::where('Status_',3)
            ->where('code_',$request->shipment_code);
            //->where('client_ID_',$request->user_id);

           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function taslim(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_' ,$request->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
       
            $all = Shipment::where('Status_',1)
            ->whereIn( 'code_' ,$request->shipment_code);
            $ret_all=$all;
            $shipments=$all->get();
            DB::beginTransaction();

            try {
            foreach($shipments as $shipment)
            {
                $shipment->Status_=4;
                $shipment->tarikh_el7ala  = Carbon::now()->format('Y-m-d');
                $shipment->mandoub_taslim = $user->name_;
                $shipment->Delivery_Delivered_Shipment_ID = $request->user_id;
                 
                $tas3ir=DB::table('mandoub_taslim_tas3irtb') ->select('price_')
                ->where('mandoub_ID',$request->user_id)
                ->where('area_name_',$shipment->mantqa_)
                ->where('city_name_',$shipment->mo7afza_)  
                ->first();
                $price=0;
                if(isset($tas3ir)) $price=$tas3ir->price_;
                $shipment->tas3ir_mandoub_taslim = $price;
               
                $shipment->save();
               
            }
            
                DB::commit();
                // all good
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'status' => 500,
                    'message' => 'DB error'.$e,
                ]);
            }
            
           
            if(!empty($shipments->count()  ))
                $ret_all=Shipment::where( 'code_' ,($request->shipment_code)[0]);
            return  $this->handleMultiShipmentelequent( $ret_all, $user, $request);
    }
    public function taslim_check(Request $request)
    {
       
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
       
            $shipments = Shipment::where('Status_',1)
            ->where('code_',$request->shipment_code);
            //->where('client_ID_',$request->user_id);
           return  $this->handleMultiShipmentelequent( $shipments, $user, $request);
    }
    public function taslem_mandob_taslem(Request $request)
    {
        /*UPDATE add_shipment_tb_ SET Status_= 7 ,tarikh_el7ala='${intl.DateFormat('yyyy-MM-dd')}' WHERE code_ = (?);*/
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            foreach($shipments as $shipment)
            { 
                $shipment->Status_= 7 ; 
                $shipment->tarikh_el7ala   = Carbon::now()->format('Y-m-d') ; 
                $shipment->save();
            }
            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }
    public function wasel_goz2e_mandob_taslem(Request $request)
    {
        /*UPDATE add_shipment_tb_ SET Status_= 7 ,tarikh_el7ala='${intl.DateFormat('yyyy-MM-dd')}' WHERE code_ = (?);*/
        if (!$user = DB::table("all_users")->where('code_' ,request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
            $all = Shipment::whereIn('code_',request()->shipment_code);
            $shipments=$all->get();
            foreach($shipments as $shipment)
            { 
                $shipment->Status_= 6 ; 
                $shipment->tarikh_el7ala   = Carbon::now()->format('Y-m-d') ; 
                $shipment->save();
            }
            return  $this->handleMultiShipmentelequent( $all, $user, $request);
    }


    public function getShipmentsByRecNum(Request $request)
    {
        if (!$user = DB::table("all_users")->where('code_', request()->user_id)->first()) {
            return response::falid('user_not_found', 404);
        }
        $all =Shipment::where('reciver_phone_',$request->reciver_phone);

        if ($user->type_ == 'عميل') {
            $all = $all->where(
                [
                    ['add_shipment_tb_.client_ID_', $request->user_id],
                ]
            );
        }
        if ($user->type_ == 'مندوب استلام') {
            $all = $all->where([
                ['add_shipment_tb_.Delivery_take_shipment_ID', $request->user_id],

            ]);
        }
        if ($user->type_ == 'مندوب تسليم') {
            $all = $all->where([
                ['add_shipment_tb_.Delivery_Delivered_Shipment_ID', $request->user_id],
            ]);
        }




        return $this->handleMultiShipmentelequent($all, $user, $request);

    }
    public function handleMultiShipmentelequent( $all, $user, Request $request )
    {
        $totalCost = $all->sum('shipment_coast_');
        if($user->type_=='عميل')
            $tawsilCost = $all->sum('tawsil_coast_');
        if($user->type_=='مندوب استلام')
            $tawsilCost = $all->sum('tas3ir_mandoub_estlam');
        if($user->type_=='مندوب تسليم')
            $tawsilCost = $all->sum('tas3ir_mandoub_taslim');
       $netCost =  $totalCost-$tawsilCost;
        $count_all = $all->count();
        if ($user->type_ == 'عميل') {
            $all = $all->with(['Branch_user' => function ( $query) {
               $query->select('code_','phone_');
            }]);
        }
        
        
        if(isset($request->commercial_name)){
            $all = $all->where('commercial_name_', $request->commercial_name);
        }
       
        $all = $all->paginate($request->limit ?? 10);
       
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => (double)$tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

    }
    public function handleMultiShipment(Builder $all, $user, Request $request)
    {
        $totalCost = $all->sum('shipment_coast_');
        $tawsilCost = $all->sum('tawsil_coast_');
        $netCost = $all->sum('total_');

        $count_all = $all->count();
        if ($user->type_ == 'عميل') {
            $all = $all->Join('add_branch_users_tb', 'add_shipment_tb_.Delivery_Delivered_Shipment_ID', '=', 'add_branch_users_tb.code_')
                ->select('add_shipment_tb_.*', 'add_branch_users_tb.phone_ as mabdob phone');
        }
        if(isset($request->commercial_name)){
            $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
        }
        
        $all = $all->paginate($request->limit ?? 10);
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'total-cost' => $totalCost,
            'tawsil-cost' => $tawsilCost,
            'net-cost' => $netCost,
            'count' => $all->count(),
            'count-all' => $count_all,
            "type" => $user->type_,
            "currentPage" => $all->currentPage(),
            'lastPage' => $all->lastPage(),
            'hasMorePages' => $all->hasMorePages(),
            'data' => $all->items(),
        ]);

    }
    

    public function filter_accounting_type($query,$type,$user,$request){
        if($type=='mosadada')
         {  
            if($user->type_=='عميل'){   
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_el3amil' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب استلام'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_elestlam' ,'<=',DATE( $request->date_to) );
            }
            if($user->type_=='مندوب تسليم'){
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_tasdid_mandoub_eltaslim' ,'<=',DATE( $request->date_to) );
            }
         }else{
            if($user->type_=='عميل'){   
                if(isset( $request->date_from))
                    $query= $query->where('date_' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('date_' ,'<=',DATE( $request->date_to) );
            }else{
                if(isset( $request->date_from))
                    $query= $query->where('tarikh_el7ala' ,'>=',DATE($request->date_from) );
                if(isset( $request->date_to))
                    $query= $query->where('tarikh_el7ala' ,'<=',DATE( $request->date_to) );
            }
         }
             
         //mosadada
         if($type=='mosadada')
         {  
            if($user->type_ =='عميل'){
                $all= $query ->where("el3amil_elmosadad",'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $query->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $query->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim",'مسدد'); 
            }
            

           
           
         }
         
 

        //3*er mosadada
        if($type=='not_mosadada')
        {
            $all= $query->where('Status_',"!=",8);
            if($user->type_ =='عميل'){
                $all= $query->where('client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
        }
       
       
        

        //mosta7aqa
        if($type=='mosta7aqa')
        {
            $all= $query->where('Status_',7);
            if($user->type_ =='عميل'){
                $all= $all->where('client_ID_',$user->code_)
                ->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_estlam","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_)
                ->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
           
        }
      
        
        //archive
        if($type=='archive')
        {
            $all=Archive::with(['Branch_user' => function ($query) {
                $query->select('code_','phone_');
            }]);
            if(isset(request()->code))
                $all = $all->where('status_',request()->code);
            $all = $all->UserType($user->type_,$user->code_);
            if(isset($request->commercial_name)){
                $all = $all->where('add_shipment_tb_.commercial_name_', $request->commercial_name);
            }
        
            if($user->type_ =='عميل'){
                $all= $all->where('client_ID_',$user->code_);
                ///->where("el3amil_elmosadad","!=",  'مسدد');
            }
            if($user->type_ =='مندوب استلام'){
                $all= $all->where('Delivery_take_shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
            if($user->type_ =='مندوب تسليم'){
                $all= $all->where('Delivery_Delivered_Shipment_ID',$user->code_);
                //->where("elmandoub_elmosadad_taslim","!=",'مسدد'); 
            }
        }
          
       return $all;
    }
}
//