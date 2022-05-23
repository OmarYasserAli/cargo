<?php
namespace App\Http\Controllers\Api\Client;

use App\CustomClass\response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ReportController
{
    public function Addreport(Request $request)
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
        if ($user->type_ != 'عميل') {
            return response::falid('user_not_found', 400);

        }
        $validator = Validator::make($request->all(), [
            'code_' => 'required|string',
            'rkm_elwasl' => 'required|string',
            'tarikh' => 'required|string',
            'msg_' => 'required|string',
            'USERname' => 'required|string',
            'commerical_name_' => 'required|string',
            'branch_' => 'required|string',

        ]);

        if ($validator->fails()) {
            return response::falid($validator->errors(), 422);
        }
        DB::beginTransaction();

        try {
            DB::table('tablighat')->insert([
                'code_' => $request->code_,
                'rkm_elwasl' => $request->rkm_elwasl,
                'tarikh' => $request->tarikh,
                'msg_' => $request->msg_,
                'USERname' => $request->USERname,
                'client_id_' => $user->code_,
                'commerical_name_' => $request->commerical_name_,
                'branch_' => $request->branch_,
            ]);
            DB::commit();
            return response()->json([
                "status" => true,
                'message' => 'added succefully',

            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response::falid(['error' => $e->getMessage()], 422);
        }
    }
    public function allreport(Request $request)
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
        if ($user->type_ != 'عميل') {
            return response::falid('user_not_found', 400);

        }
        $all = DB::table('tablighat')->where('code_',$request->code_)->get();
        return response()->json([
            "status" => true,
            'message'=> 'register success',
            'all'   =>$all,
        ], 200);
    }

}
