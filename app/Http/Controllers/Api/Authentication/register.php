<?php

namespace App\Http\Controllers\Api\Authentication;

use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Models\AllUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\DB;

class register extends Controller
{
    public function register(Request $request){
        if($request->type == 'عميل')
        {
        $validator = Validator::make($request->all(), [
            'type' => ['required',Rule::in('عميل','موظف','مندوب استلام')],
            'branch' => 'required|exists:branch_info_tb,name_',
            'mohfza' => 'required|exists:edaft_mo7afzat_iraq_tb,name',
            'name_'=>'required|string',
            'username'=>'required|string|unique:all_users',
            'password'=>'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            'phone_'=>'required|string',
            'commercial_name'=>'required|string',
            'mantqa'=>'required|exists:edaft_manateq_iraq_tb,name',
            'ID'=>'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        DB::beginTransaction();

        try {
             AllUser::create([
                'name_'=>$request->name_,
                'type_'=>$request->type,
                'status_'=>'1',
                'serial_'=>'0',
                'branch'=>$request->branch,
                'username'=>$request->username,
                'PASSWORD'=>md5($request->get('password')),
                'mo7fza'=>$request->mohfza,
                'mantqa'=>$request->mantqa,
                
            ]);
            $client = AllUser::latest()->first();
            $branch_id = DB::table('branch_info_tb')->where('name_',$request->branch)->first();
            DB::table('add_clients_main_comp_tb')->insert([
                'name_'=>$client->name_,
                'username'=>$request->username,
                'PASSWORD'=>md5($request->get('password')),
                'code_'=>$client->code_,
                'commercial_name'=>$request->commercial_name,
                'Branch_ID'=> $branch_id->code_,
                'Branch_name'=> $request->branch,
                'Special_prices'=>'لا',
                'mo7fza'=>$request->mohfza,
                'mantqa'=>$request->mantqa,
                'phone_'=>$request->phone_,
                'ID_'=>$request->ID,
                'address_'=>""
            ]);
            DB::commit();
            $token = JWTAuth::fromUser($client);

            return response()->json([
                "status" => true,
                'message'=> 'register success',
                'user'   =>$client,
                'token'  => $token,
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response::falid(['error' => $e->getMessage()], 422);
        }
    }else
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required',Rule::in('عميل','موظف','مندوب استلام','مندوب تسليم')],
            'branch' => 'required|exists:branch_info_tb,name_',
            'mohfza' => 'required|exists:edaft_mo7afzat_iraq_tb,name',
            'name_'=>'required|string',
            'username'=>'required|string|unique:all_users',
            'password'=>'required|string|min:6',
            'confirmPassword'   => 'required|string|same:password',
            'phone_'=>'required|string',
            'transport_kind'=>'required|string',
            'mantqa'=>'required|exists:edaft_manateq_iraq_tb,name',
            'ID'=>'required|string',
        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        DB::beginTransaction();

        try {
             AllUser::create([
                'name_'=>$request->name_,
                'type_'=>$request->type,
                'status_'=>'1',
                'serial_'=>'0',
                'branch'=>$request->branch,
                'USERNAME'=>$request->username,
                'PASSWORD'=>md5($request->get('password')),
                'mo7fza'=>$request->mohfza,
                'mantqa'=>$request->mantqa,
                
            ]);
            $client = AllUser::latest()->first();
            DB::table('add_branch_users_tb')->insert([
                'name_'=>$client->name_,
                'USERNAME'=>$request->username,
                'PASSWORD'=>md5($request->get('password')),
                'code_'=>$client->code_,
                'branch_name'=> $request->branch,
                'Job'=>$request->type,
                'transport_kind'=>$request->transport_kind,
                'mo7fza'=>$request->mohfza,
                'mantqa'=>$request->mantqa,
                'phonekey_'=>$request->phone_,
                'ID_'=>$request->ID,
                'address_'=>""
            ]);
            $token = JWTAuth::fromUser($client);
            DB::commit();
            return response()->json([
                "status" => true,
                'message'=> 'register success',
                'user'   =>$client,
                'token'  => $token,
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response::falid(['error' => $e->getMessage()], 422);
        }


    }
        
        
       
    }

    }
