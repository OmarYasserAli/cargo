<?php

namespace App\Http\Controllers\Api\site;

use App\Models\Ad;
use App\Models\Aucation;
use App\Models\Winner;
use App\Models\Bidder;
use App\Models\Vehicle;
use App\Models\Saved;
use App\CustomClass\response;
use App\Http\Resources\bidderResource;
use App\Http\Resources\VehicleResource;

use App\Http\Resources\notificationsResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use App\Models\bidders;
use App\Models\Employer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Auth;
use Illuminate\Support\Facades\DB;
use Kutia\Larafirebase\Facades\Larafirebase;
use App\Models\Counter;
use App\Models\Notification;

class VehicleController extends Controller
{

    public function storeVehicle(Request $request)
    {
        try {
            if (! $bidder = auth('bidder')->user()) {
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
            'vehicle_title' => 'required|string|max:250',
            'vehicle_vin' => 'required|string|max:250',
            'published' => 'nullable|string|max:250',
            'engine_type' => 'nullable|string|max:250',
            'primary_damage' => 'nullable|string|max:250',
            'retail_value' => 'nullable|numeric|max:10000',
            'featured' => 'nullable|boolean',
            'transmission' => 'required|string|max:250',
            'vat_added' => 'required|numeric|max:250',
            'selender' => 'required|numeric|max:250',
            'fuel' => 'required|string|max:250',
            'keys' => 'required|boolean',
            'drive' => 'required|string|max:250',
            'sell_type' => 'required|string|max:250',
            'special_notes' => 'nullable|string|max:250',
            'body_style' => 'nullable|string|max:250',
            'odometer' => 'required|numeric',
            'company' => 'nullable|string|max:250',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:250',
            'year' => 'nullable|integer',
            'model_id' => 'nullable|exists:modeles,id',
            'start_date' => 'nullable|date_format:d/m/Y| after_or_equal : today',
            'start_time' => 'nullable|date_format:G:i',
            'end_time' => 'nullable|date_format:G:i',
            'photos'       =>'nullable|array',
            'licence'       =>'nullable | mimes:png,jpg,jpeg,gif',
        ]);
        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        $input = $request->only(
            'retail_value',
            'start_date',
            'start_time',
            'end_time',
            'sell_type',
            'vehicle_title',
            'vehicle_vin',
            'primary_damage',
            'transmission',
            'fuel',
            'special_notes',
            'retail_value',
            'odometer',
            'engine_type',
            'vat_added',
            'selender',
            'drive',
            'keys',
            'published',
            'featured',
            'modele_id',
            'year',
            'company',
            'category_id',
            'color',
            'body_style',
        );

        $input['bidder_id'] = auth('bidder')->id();
        $vehicle = new Vehicle($input);
        if($request->has('licence'))
        {
            $path = rand(0,1000000) . time() . '.' . $request->file('licence')->getClientOriginalExtension();
            $request->file('licence')->move(base_path('public/uploads/vehicles') , $path);
            $vehicle->licence   = $path;
        }
        if($request->has('photos'))
        {
            $arr=array();
            foreach($request->photos as $photo)
            {
                $path = rand(0,1000000) . time() . '.' . $photo->getClientOriginalExtension();
                $photo->move(base_path('public/uploads/vehicles') , $path);
                array_push($arr,$path);
            }
            $vehicle->photos=json_encode($arr);
        }

        $vehicle->final_price=0;
        $vehicle->save();
        return response()->json([
            'ststus'=> 1,
            'vehicle'=>new VehicleResource($vehicle),
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            if (! $bidder = auth('bidder')->user()) {
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
            'vehicle_title' => 'required|string|max:250',
            'vehicle_vin' => 'required|string|max:250',
            'published' => 'nullable|string|max:250',
            'engine_type' => 'nullable|string|max:250',
            'primary_damage' => 'nullable|string|max:250',
            'retail_value' => 'nullable|numeric',
            'featured' => 'nullable|boolean',
            'transmission' => 'nullable|string|max:250',
            'vat_added' => 'nullable|numeric|max:250',
            'selender' => 'nullable|numeric|max:250',
            'fuel' => 'nullable|string|max:250',
            'keys' => 'nullable|boolean',
            'drive' => 'nullable|string|max:250',
            'sell_type' => 'nullable|string|max:250',
            'special_notes' => 'nullable|array',
            'special_notes' => 'nullable|string|max:250',
            'body_style' => 'nullable|string|max:250',
            'odometer' => 'nullable|numeric',
            'company' => 'nullable|string|max:250',
            'category_id' => 'nullable|exists:categories,id',
            'color' => 'nullable|string|max:250',
            'year' => 'nullable|integer',
            'model' => 'nullable|string|max:250',
            'start_date' => 'nullable|date_format:d/m/Y| after_or_equal : today',
            'starts_time' => 'nullable|date_format:G:i',
            'end_time' => 'nullable|date_format:G:i',
            'licence'       =>'nullable | mimes:png,jpg,jpeg,gif',

        ]);

        if($validator->fails()){
            return response::falid($validator->errors(), 422);
        }
        $input = $request->only(
            'retail_value',
            'start_date',
            'start_time',
            'end_time',
            'sell_type',
            'vehicle_title',
            'vehicle_vin',
            'primary_damage',
            'transmission',
            'fuel',
            'special_notes',
            'retail_value',
            'odometer',
            'engine_type',
            'vat_added',
            'selender',
            'drive',
            'keys',
            'published',
            'featured',
            'modele_id',
            'year',
            'company',
            'category_id',
            'color',
            'body_style',
        );



        $vehicle = Vehicle::find($id);

        if (!$vehicle) return response()->json($this->entityNotFoundErr, 422);




        if ($vehicle->update($input)) {
            if ($request->has('photos')) {
                $oldArr=json_decode($vehicle->photos);
                $arr=array();
                foreach($request->photos as $photo)
                {
                    $path = rand(0,1000000) . time() . '.' . $photo->getClientOriginalExtension();
                    $photo->move(base_path('public/uploads/vehicles') , $path);
                    array_push($arr,$path);
                }
                $vehicle->photos=json_encode($arr);

                if(!empty($oldArr) && count($oldArr)>0){
                    foreach($oldArr as $vphoto){
                        if(file_exists(base_path('public/uploads/vehicles') . $vphoto)){
                            unlink(base_path('public/uploads/vehicles') . $vphoto);
                        }
                    }
                }
            }


            if($request->has('licence')){
                if($bidder->licence == null){
                    $path = rand(0,1000000) . time() . '.' . $request->file('licence')->getClientOriginalExtension();
                    $request->file('licence')->move(base_path('public/uploads/vehicles') , $path);
                    $bidder->licence   = $path;
                } else {
                    $oldAvatar = $bidder->licence;
                    //updat cv
                    $path = rand(0,1000000) . time() . '.' . $request->file('licence')->getClientOriginalExtension();
                    $request->file('licence')->move(base_path('public/uploads/vehicles') , $path);
                    $bidder->licence   = $path;
                    //delet old cv
                    if(file_exists(base_path('public/uploads/vehicles/') . $oldAvatar)){
                        unlink(base_path('public/uploads/vehicles/') . $oldAvatar);
                    }
                }
            }
            $vehicle->save();

            return response()->json([
                'ststus'=> 1,
                'vehicle'=>new VehicleResource($vehicle),
            ]);
         }

        return response()->json($this->failedErr, 500);
    }


    public function searchVehicles(Request $request)
    {
        $year_min = $request->year_min ?? 1800;
        $year_max = $request->year_max ?? date('Y');
        $vehicles =Vehicle::where('status','Accept')->whereBetween('year', [$year_min, $year_max]);

        if($request->has('category_id') && $request->category_id !=null)
        {
            $vehicles->where('category_id',$request->category_id);
        }
        if($request->has('modele_id') && $request->modele_id !=null)
        {
            $vehicles->where('modele_id',$request->modele_id);
        }
        if($request->has('year') && $request->year !=null)
        {
            $vehicles->where('year',$request->year);
        }

        // return response()->json($vehicles->get());
        if ($vehicles->count()<=0) return response()->json(['message' => 'No such vehicles'], 404);
        return response()->json([
            'vehicles' =>VehicleResource::collection($vehicles->paginate(1))->response()->getData(true),
        ], 200);
    }

    public function destroy($id, Request $request)
    {

        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'ststus'=> 0,
                'message'=>'Vehicle not Found',
            ]);
        }

        if($vehicle->status=='Accept')
        {
            return response()->json([
                'ststus'=> 1,
                'message'=>'Please Contact With Admin To Delete It',
            ]);
        };
        $saved_vehicles = Saved::where('vehicle_id', $id)->delete();
        $saved_vehicles = Aucation::where('vehicle_id', $id)->delete();

        if ($vehicle->delete()) {
                return response()->json([
                    'ststus'=> 1,
                    'message'=>'Vehicle Successfully Deleted',
                ]);
            }
    }
    // return all auctions ...
    public function allAuctions()
    {
        $auctions = Vehicle::where('status','Accept')->get();
        // $date = Carbon::createFromFormat('d/m/Y h:i A', $auction->vehicle_start_data . $auction->vehicle_start_time);
        $today_auctions = [];
        $upcoming_auctions = [];
        foreach ($auctions as $auction) {

            $auction_start_date =$auction->start_date;
            $today =date('d/m/Y');

            if ($auction_start_date > $today) {
                array_push($upcoming_auctions, $auction);
            }
            if ($auction_start_date == $today) {
                array_push($today_auctions, $auction);
            }
        }
        return response()->json([
            'status'            =>1,
            'today_auctions'    =>VehicleResource::collection(collect($today_auctions)),
            'upcoming_auctions' =>VehicleResource::collection(collect($upcoming_auctions)),
        ], 200);
    }
    public function vehiclesByStatus(Request $request)
    {

        try {
            if (! $bidder = auth('bidder')->user()) {
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
            'status'       =>'required | integer',
        ]);
        if($request->status == 1)
        {
            $vehicles = Vehicle::where('status','Pending')->where('bidder_id',$bidder->id)->paginate(6);
            if($vehicles->count() <= 0)
            {
                return response::falid('No Pending Vehicle', 400);
            }
        }
        if($request->status == 2)
        {
            $vehicles = Vehicle::where('status','Accept')->where('bidder_id',$bidder->id)
            ->where('start_date' ,'>',date('d/m/Y'))->paginate(6);

            if($vehicles->count() <= 0)
            {
                return response::falid('No Accept Vehicle', 400);
            }
        }
        if($request->status == 3)
        {
            $vehicles = Vehicle::where('status','Accept')->where('bidder_id',$bidder->id)
            ->where('start_date' ,'=',date('d/m/Y'))->paginate(6);
            if($vehicles->count() <= 0)
            {
                return response::falid('No Aucation  Vehicle', 400);
            }
        }
        if($request->status == 4)
        {
            $vehicles = Vehicle::where('status','Solid')->where('bidder_id',$bidder->id)->paginate(6);
            if($vehicles->count() <= 0)
            {
                return response::falid('No Solid Vehicle', 400);
            }
        }
        if($request->status == 5)
        {
            $vehicles = Vehicle::where('status','Counter')->where('bidder_id',$bidder->id)->paginate(6);
            if($vehicles->count() <= 0)
            {
                return response::falid('No Counter Vehicle', 400);
            }
        }
        if($request->status == 6)
        {
            $vehicles = Vehicle::where('status','Canceled')->where('bidder_id',$bidder->id)->paginate(6);
            if($vehicles->count() <= 0)
            {
                return response::falid('No Canceled Vehicle', 400);
            }
        }
        return response()->json([
            'status'            =>true,
            'vehicles'    =>VehicleResource::collection($vehicles)->response()->getData(true),
        ], 200);
    }

    // get all featured cars
    public function getFeaturedVehicles()
    {
        // $vehicles = Vehicle::with('images')->where('featured', true)->latest()->get('vehicle_title');
        $vehicles = Vehicle::where('status','Accept')->where('featured',1)->latest()->get();
        if($vehicles->count()<=0)
        {
            return response()->json([
                'status'            =>1,
                'messages'    =>'No Feature Vehicles',
            ], 404);
        }
        return response()->json([
            'status'            =>1,
            'features' =>VehicleResource::collection($vehicles),
        ], 200);

    }


    public function getUserVehicles()
    {
        try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }
        $vehicles =Vehicle::where('status','Accept')->where('bidder_id', auth('bidder')->user()->id)->get();
        if ($vehicles->count()<=0) return response()->json([
            'message' => 'You have no saved vehicles yet!'
        ], 404);

        return response()->json([
            'vehicles' =>VehicleResource::collection($vehicles),
        ], 200);
    }
    public function getUserSavedVehicles()
    {
        try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $vehicles = auth('bidder')->user()->savedVehicles()->where('status','Accept')->get();
        if ($vehicles->count()<=0) return response()->json([
            'message' => 'You have no vehicles yet!'
        ], 404);

        return response()->json([
            'vehicles' =>VehicleResource::collection($vehicles),
        ], 200);
    }
    public function toggleFavourite($id)
    {
        try {
            if (! $bidder = auth('bidder')->user()) {
                return response::falid('user_not_found', 404);
            }

        } catch (TokenExpiredException $e) {

            return response::falid('token_expired', 400);

        } catch (TokenInvalidException $e) {

            return response::falid('token_invalid', 400);

        } catch (JWTException $e) {

            return response::falid('token_absent', 400);
        }

        $bidder = auth('bidder')->user();
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'message' => 'vehicle not found'
            ], 422);
        }
        $bidder_id = $bidder->id;

        $is_saved = Saved::all()->where('bidder_id', '==', $bidder_id)
            ->where('vehicle_id', '==', $id)
            ->first();

        if ($is_saved == null) {
            $saved = new Saved();
            $saved->bidder_id        = $bidder_id;
            $saved->vehicle_id       = $vehicle->id;
            if ($saved->save()) {
                return response()->json([
                    'successful' => '1',
                    'status' => '01',
                    'message' => 'Vehicle added To Favourite List successfully',
                ], 200);
            }} else {
                // $vehicle = Vehicle::findOrFail($id);
                $is_saved->delete();
                // $vehicle->save();
                return response()->json([
                    'successful' => '1',
                    'status' => '01',
                    'message' => 'vehicle removed from Favourite successfully'
                ], 200);
            }
    }



    public function getVehicleById($id)
    {

        $bidder_id = auth('bidder')->user()->id;
        if (!$bidder_id) {
            $is_saved = false;
        } else {
            $is_saved = Saved::all()->where('bidder_id', '==', $bidder_id)
                ->where('vehicle_id', '==', $id)
                ->first();
        }
        $vehicle = Vehicle::find($id);

        if (!$vehicle) {
            return response()->json([
                'message' => "No Such Vehicle"
            ], 404);
        }


        return response()->json([
            'is_saved' => $is_saved ? true : false,
            'vehicles' =>new VehicleResource($vehicle),
        ], 200);
    }
    public function getWinners( )
    {
         try {
        if (! $bidder = auth('bidder')->user()) {
            return response::falid('user_not_found', 404);
        }

    } catch (TokenExpiredException $e) {

        return response::falid('token_expired', 400);

    } catch (TokenInvalidException $e) {

        return response::falid('token_invalid', 400);

    } catch (JWTException $e) {

        return response::falid('token_absent', 400);
    }

        $winners=Winner::where('bidder_id',$bidder->id)->pluck('vehicle_id');
        if (count($winners)<=0) {
            return response()->json([
                'status'=>false,
                'message' => "No Winner Vehicles",
            ], 404);
        }


        $vehicles = Vehicle::where('status','Solid')->whereIn('id',$winners)->get();

        if ($vehicles->count() <=0) {
            return response()->json([
                'status'=>false,
                'message' => "No Winner Vehicles",
            ], 404);
        }

        return response()->json([
            'status'=>true,
            'Winner_vehicles' =>VehicleResource::collection($vehicles),
        ], 200);
    }
    public function getOnCounters( )
    {
         try {
        if (! $bidder = auth('bidder')->user()) {
            return response::falid('user_not_found', 404);
        }

    } catch (TokenExpiredException $e) {

        return response::falid('token_expired', 400);

    } catch (TokenInvalidException $e) {

        return response::falid('token_invalid', 400);

    } catch (JWTException $e) {

        return response::falid('token_absent', 400);
    }

        $counters=Counter::where('bidder_id',$bidder->id)->pluck('vehicle_id');
        if (count($counters)<=0) {
            return response()->json([
                'status'=>false,
                'message' => "No Counters Vehicles",
            ], 404);
        }

        $counters=array_unique($counters->toarray());
        $vehicles = Vehicle::where('status','Counter')->whereIn('id',$counters)->get();

        if ($vehicles->count() <=0) {
            return response()->json([
                'status'=>false,
                'message' => "No Counters Vehicles",
            ], 404);
        }

        return response()->json([
            'status'=>true,
            'counter_vehicles' =>VehicleResource::collection($vehicles),
        ], 200);
    }
    public function notificatios( )
    {
         try {
        if (! $bidder = auth('bidder')->user()) {
            return response::falid('user_not_found', 404);
        }

    } catch (TokenExpiredException $e) {

        return response::falid('token_expired', 400);

    } catch (TokenInvalidException $e) {

        return response::falid('token_invalid', 400);

    } catch (JWTException $e) {

        return response::falid('token_absent', 400);
    }

        $notifications=Notification::where('bidder_id',$bidder->id)->latest()->paginate(6);
        if ($notifications->count()<=0) {
            return response()->json([
                'status'=>false,
                'message' => "No notifications",
            ], 404);
        }

        return response()->json([
            'status'=>true,
            'notifications' =>notificationsResource::collection($notifications)->response()->getData(true),
        ], 200);
    }
    public function readNotificatios( )
    {
         try {
        if (! $bidder = auth('bidder')->user()) {
            return response::falid('user_not_found', 404);
        }

    } catch (TokenExpiredException $e) {

        return response::falid('token_expired', 400);

    } catch (TokenInvalidException $e) {

        return response::falid('token_invalid', 400);

    } catch (JWTException $e) {

        return response::falid('token_absent', 400);
    }

        $notifications=Notification::where('bidder_id',$bidder->id)->where('read_at',null)->get();
        if ($notifications->count()<=0) {
            return response()->json([
                'status'=>false,
                'message' => "No notifications for read",
            ], 404);
        }
        foreach($notifications as $notify)
        {
            $notify->update(['read_at'=>date('d/m/y h:m:i')]);
        }
        return response()->json([
            'status'=>true,
            'message'=>'All Notificatins Readed Sucessfully',
        ], 200);
    }
    public function deketeNotification( Request $request)
    {
         try {
        if (! $bidder = auth('bidder')->user()) {
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
        'notify_id' => 'nullable|exists:notifications,id',
    ]);

    if($validator->fails()){
        return response::falid($validator->errors(), 422);
    }
        $notify=Notification::where('bidder_id',$bidder->id)->find($request->notify_id);
        if (empty($notify)) {
            return response()->json([
                'status'=>false,
                'message' => "No notifications for delete",
            ], 404);
        }

        $notify->delete();
        return response()->json([
            'status'=>true,
            'message'=>'This Notify Deleted Sucessfully',
        ], 200);
    }
}
