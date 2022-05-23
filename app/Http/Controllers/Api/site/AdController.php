<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ad;
use App\Http\Resources\adResource;
class AdController extends Controller
{
    public function getAllAds()
    {
        $ads=Ad::where('status','publish')->latest()->get();
        if($ads->count() <= 0)
        {
            return response()->json([
                'status'        =>  '0',
                'successfully'  =>  '02',
                'ads'           =>  "Ther is No Ads",
            ]);
        }
        return response()->json([
            'status'        =>  '01',
            'successfully'  =>  '01',
            'ads'=>adResource::collection($ads),
        ]);
    }
}
