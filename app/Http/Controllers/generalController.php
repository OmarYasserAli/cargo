<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mohfza;
use App\Models\Mantikqa;
class generalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getManateqByMa7afza( )
    {
        $mo7afza=request()->mo7afza;
        
        $manatek =Mantikqa::with('Tas3ir_3amil' ,'Tas3ir_ta7wel')->where('mo7afza',$mo7afza)->where('branch','الفرع الرئيسى')->get();

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'all' => $manatek,
            'sum' => count($manatek),
        ], 200); 
        
    }
  
}
