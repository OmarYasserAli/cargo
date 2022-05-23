<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Mohfza;
use App\Models\Tas3ir_3amil;
use App\Models\Tas3ir_ta7wel;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class tas3irController extends Controller
{
	
        public function save_3amel(Request $request)
        {
        	
        	$tas3ir = Tas3ir_3amil::find($request->code);
                $tas3ir->price_= $request->value;
                $tas3ir->save();
        }
        public function save_ta7wel(Request $request)
        {
        	
        	$tas3ir = Tas3ir_ta7wel::find($request->code);
                $tas3ir->price_= $request->value;
               $tas3ir->save();
                
        }

       
}
