<?php

namespace App\Http\Controllers\Api\general;

use App\CustomClass\response;
use App\Http\Controllers\Api\site\Controller;
use App\Models\BranchInfo;
use App\Models\Mantikqa;
use App\Models\Mohfza;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class BranchController extends Controller
{
    public function allbranch(Request $request)
    {

        $branches = BranchInfo::select('name_')->get();

        // return $moh;
        foreach ($branches as $branch) {
            $moh_det = Mohfza::select('name')->where('branch', $branch->name_)->get();
            $branch['mohfza'] = $moh_det;
            // foreach ($moh_det as $m) {
            //     $mantika = Mantikqa::select('name', 'branch', 'serial_')->where('branch', $branch->name_)->get();
            //     $m['mantika'] = $mantika;

            // }
        }

        return response::suceess('success', 200, "Allbranch", $branches);

    }
    public function allmantika(Request $request)
    {
        $mantika = Mantikqa::select('name', 'branch', 'serial_')->where('branch', $request->branch)->where('mo7afza', $request->mohafza)->get();
        return response::suceess('success', 200, "mantika", $mantika);

    }
    public function search_qr(Request $request)
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

        $all = DB::table('add_shipment_tb_')->where('shipment_code_', $request->shipment_code_)->get();
        return response()->json([
            'status' => 200,
            'message' => 'success',
            'all' => $all,
            'sum' => count($all),
        ], 200);
    }
    public function commerical(Request $request)
    {
        $all = DB::table('add_commercial_names_tb')->get();
        return response::suceess('success', 200, "Allcommeric", $all);

    }
    public function paid(Request $request)
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

        if ($user->type_ == 'عميل') {
            $paid = DB::table('add_shipment_tb_')->where('el3amil_elmosadad', 'مسدد')->where('client_ID_', $user->code_)->get();
            return response::suceess('success', 200, "paid", $paid);
        } elseif ($user->type_ == 'مندوب تسليم') {
            $paid = DB::table('add_shipment_tb_')->where('elmandoub_elmosadad_taslim', 'مسدد')->where('Delivery_Delivered_Shipment_ID', $user->code_)->get();
            return response::suceess('success', 200, "paid", $paid);
        } elseif ($user->type_ == 'مندوب استلام') {
            $paid = DB::table('add_shipment_tb_')->where('elmandoub_elmosadad_estlam', 'مسدد')->where('Delivery_take_shipment_ID', $user->code_)->get();
            return response::suceess('success', 200, "paid", $paid);

        }

    }
    public function unpaidship(Request $request)
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
        if ($user->type_ == 'عميل') {
            $unpaid = DB::table('add_shipment_tb_')->where('el3amil_elmosadad', '!=', 'مسدد')->where('client_ID_', $user->code_)->get();
            return response::suceess('success', 200, "unpaid", $unpaid);
        } elseif ($user->type_ == 'مندوب تسليم') {
            $unpaid = DB::table('add_shipment_tb_')->where('elmandoub_elmosadad_taslim', '!=', 'مسدد')->where('Delivery_Delivered_Shipment_ID', $user->code_)->get();
            return response::suceess('success', 200, "unpaid", $unpaid);
        } elseif ($user->type_ == 'مندوب استلام') {
            $unpaid = DB::table('add_shipment_tb_')->where('elmandoub_elmosadad_estlam', '!=', 'مسدد')->where('Delivery_take_shipment_ID', $user->code_)->get();
            return response::suceess('success', 200, "unpaid", $unpaid);

        }

    }
    public function updateship(Request $request)
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
        $validator = Validator::make($request->all(), [

            'shipment_code_' => 'required|exists:add_shipment_tb_,shipment_code_',

        ]);

        if ($validator->fails()) {
            return response::falid($validator->errors(), 422);
        }
        $arr = array('1', '2', '3', '4', '6', '7', '8', '9', '10');
        if (in_array($request->status_, $arr)) {

            if ($user->type_ == 'عميل') {
                $custome = 'client_ID_';

            } elseif ($user->type_ == 'مندوب تسليم') {
                $custome = 'Delivery_Delivered_Shipment_ID';
            } elseif ($user->type_ == 'مندوب استلام') {
                $custome = 'Delivery_take_shipment_ID';

            }
            $data = DB::table('add_shipment_tb_')->where($custome, $user->code_)->
                where('shipment_code_', $request->shipment_code_)->update([
                'status_' => $request->status_,
            ]);

            if ($data == 1) {
                return response()->json([
                    'status' => 200,
                    'message' => 'update succefully',
                ], 200);
            } else {
                return response::falid('somthing error', 400);
            }

        }
        {
            return response::falid('somthing error', 400);
        }

    }
}
