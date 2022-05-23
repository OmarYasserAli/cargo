<?php

namespace App\Http\Controllers\setting;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Mohfza;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class definationsController extends Controller
{
        public function company()
        {
        	return view('deffinations.company');
        }

        public function addCity()
        {
        	$cities=Mohfza::groupBy('name')->get();
        	return view('deffinations.city',compact('cities'));
        }

        public function addBranch()
        {
        	return view('deffinations.branch');
        }
}
