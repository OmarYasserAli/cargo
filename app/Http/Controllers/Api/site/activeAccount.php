<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\ActivateAcount;
use App\Mail\resetPassword;
use App\Models\Employees;
use App\Models\Employer;
use App\Models\EmployeePasswordForget;
use App\Models\EmployerPasswordForget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;


class activeAccount
{
   
    public function sendEmail(Request $request)  // this is most important function to send mail and inside of that there are another function
    {
        $table = $request->route()->getName();

        if (!$this->validateEmail($request->email)) {  // this is validate to fail send mail or true
            return $this->failedResponse();
        }
        
        $request->table = $table;

        // code is important in send mail 
        $code = $this->createCode($request->email, $request);
        Mail::to($request->email)->send(new ActivateAcount($code));

        return $this->successResponse();
    }


    public function createCode($email, $request)  // this is a function to get your request email that there are or not to send mail
    {
        $table = $request->table . '_active';

        $oldCode = DB::table($table)->where('email', $email)->first();

        if ($oldCode) {
            return $oldCode->code;
        }

        $code = Str::random(8);
        $this->saveCode($code, $email, $request);
        return $code;
    }


    public function saveCode($code, $email, $request)  // this function save new code
    {
        $table = $request->table . '_active';

        DB::table($table)->insert([
            'email' => $email,
            'code' => $code,
            'created_at' => Carbon::now()
        ]);
    }

    public function validateEmail($email)  //this is a function to get your email from database
    {
        return !!Employees::where('email', $email)->first();
    }

    public function failedResponse()
    {
        return response()->json([
            'status' => false,
            'message'=>'Email does\'t found on our database',
        ], Response::HTTP_NOT_FOUND);
    }

    public function successResponse()
    {
        return response()->json([
            'status' => true,
            'message'=>'Reset Email is send successfully, please check your inbox.',
        ], Response::HTTP_OK);
    }

    
    //////////////////////// change code ////////////

    public function active(Request $request){
        $table = $request->route()->getName();
        $request->table = $table;

        return $this->updateStateRow($request)->count() > 0 ? $this->changeState($request) : $this->codeNotFoundError();
      }
  
      // Verify if code is valid
      private function updateStateRow($request){
        $table = $request->table . '_active';

        return DB::table($table)->where([
            'email' => $request->email,
            'code' => $request->code
        ]);
      }
  
      // code not found response  
      private function codeNotFoundError() {
          return response()->json([
            'status' => false,
            'message'=>'Either your email or code is wrong.',
          ],Response::HTTP_UNPROCESSABLE_ENTITY);
      }
  
      // change State
      private function changeState($request) {
        $table = $request->table . 's';
          // active
          DB::table($table)
          ->where('email', $request->email)
          ->update(['state' => 1]);

          // remove verification data from db
          $this->updateStateRow($request)->delete();
  
          //response
          return response()->json([
            'status' => true,
            'message'=>'activation success',
          ],Response::HTTP_CREATED);
      }
}
