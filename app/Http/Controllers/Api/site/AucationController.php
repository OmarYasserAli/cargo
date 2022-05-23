<?php

namespace App\Http\Controllers\Api\site;
use App\Models\Ad;
use App\Models\Aucation;
use App\Models\Winner;
use App\Models\Counter;
use App\Models\Bidder;
use App\Models\Vehicle;
use App\Models\Saved;
use App\Models\Notification;
use App\CustomClass\response;
use App\Http\Resources\BidderResource;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Bidderrequest;
use JWTAuth;
use Auth;

use App\Http\Resources\VehicleResource;
use Illuminate\Support\Facades\DB;
use Kutia\Larafirebase\Facades\Larafirebase;

class AucationController extends Controller
{
    private $FIREBASE_SERVER_API_KEY='AAAAfoG6AAk:APA91bFMbZQNi8siaOD8GmREM20HZzX8zokQgMrIDimg3WviJ_a8FwhSbIFpf4tk-dP_CLAwzbEw5W6lvy7t_2kxhigra4EnSi859aPTNPHKmxSnoa2p71xNMQEcSFZCZPfd530NquNg';
    public function startOrJionToPrebid(Request $request){
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
        $validator=Validator::make($request->all(),
        [
            'vehicle_id'=>'required | exists:vehicles,id',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'errors' => $validator->errors()->tojson(),
            ], 200);

        }
        // $vehicle = Vehicle::where('published','Publish')->where('status','Continue')
        //             ->where('id',$request->vehicle_id)->where('bidder_id',$bidder->id)
        //             ->first();
        // if(!empty($vehicle)){
        //     return response()->json([
        //         'successful' => '1',
        //         'status' => '01',
        //         'message' => 'This Your Vehicle And You Can not Bid It',
        //     ], 200);
        // }
        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->first();
        if(empty($vehicle))
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'message' => 'This Vehicle Not Found',
            ], 404);
        }
       $sumOfBidValue=$vehicle->aucations()->latest()->first()->bid_value ?? 0;

        if($sumOfBidValue >= 0)
        {
            return response()->json([
                'successful' => '1',
                'status' => '01',
                'sumOfBidValue' => $sumOfBidValue,
            ], 200);
        }

    }

    public function prebid(Request $request){
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
        $validator=Validator::make($request->all(),
        [
            'vehicle_id'=>'required | exists:vehicles,id',
            'bid_value'=>'required | min:1',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'errors' => $validator->errors()->tojson(),
            ], 200);

        }
        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->where('bidder_id',$bidder->id)
                    ->first();
        if(!empty($vehicle)){
            return response()->json([
                'successful' => '1',
                'status' => '01',
                'message' => 'This Your Vehicle And You Can not Bid It',
            ], 200);
        }
        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->first();
        if(empty($vehicle))
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'message' => 'This Vehicle Not Found',
            ], 404);
        }
       $auction=Aucation::create([
            'bidder_id'=>$bidder->id,
            'vehicle_id'=>$vehicle->id,
            'bid_value'=>$request->bid_value,
        ]);
        $sumOfBidValue=$vehicle->aucations()->latest()->first()->bid_value ?? 0;

        if(!empty($auction))
        {
            return response()->json([
                'successful' => '1',
                'status' => '01',
                'message' => 'Thanks You bid in this auction',
                'sumOfBidValue'=>$sumOfBidValue,

            ], 200);
        }

    }
    public function RealtimePrebid(Request $request){
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
        $validator=Validator::make($request->all(),
        [
            'vehicle_id'=>'required | exists:vehicles,id',
            'bid_value'=>'required | min:1',

        ]);
        if($validator->fails())
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'errors' => $validator->errors()->tojson(),
            ], 200);

        }
        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->where('bidder_id',$bidder->id)
                    ->first();
        if(!empty($vehicle)){
            return response()->json([
                'successful' => '1',
                'status' => '01',
                'message' => 'This Your Vehicle And You Can not Bid It',
            ], 200);
        }
        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->first();
        if(empty($vehicle))
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'message' => 'This Vehicle Not Found',
            ], 404);
        }

       $auction=Aucation::create([
            'bidder_id'=>$bidder->id,
            'vehicle_id'=>$vehicle->id,
            'bid_value'=>$request->bid_value,
        ]);
        $sumOfBidValue=$vehicle->aucations()->latest()->first()->bid_value ?? 0;
        $onlypartner=array();
        $whoPartner=$vehicle->aucations()->pluck('bidder_id');
        $onlypartner=array_unique($whoPartner->toarray());
        $bidders=Bidder::whereIn('id',$onlypartner)->get();
        //start realtime value
        //if($bidders->count()>0){
            foreach($bidders as $bidder){
                if($bidder->device_token != null)
                {
                    $token_1 = $bidder->device_token;
                    // Larafirebase::withTitle('New bid')
                    //             ->withBody('new Bid')
                    //             ->withPriority('high')
                    //             ->withImage('https://firebase.google.com/images/social.png')
                    //             ->sendNotification($token_1);
                    Larafirebase::fromRaw([
                        'registration_ids' => [$token_1],
                        'data' => [
                            'sumOfBidValue' => $sumOfBidValue,
                        ],
                        'android' => [
                            'ttl' => '1000s',
                            'priority' => 'normal',
                            'notification' => [
                                'Title' => 'New Bid',
                                'Body' => 'New Bid'
                            ],
                        ],
                    ])->send();

                }
            }
        //}
        //end realtime value
        if(!empty($auction))
        {
            return response()->json([
                'successful' => '1',
                'status' => '01',
                'message' => 'Thanks You bid in this auction',
                'sumOfBidValue'=>$sumOfBidValue,
            ], 200);
        }

    }
    public function EndPrebid(Request $request){

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
        $validator=Validator::make($request->all(),[
            'vehicle_id'=>'required | exists:vehicles,id',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'errors' => $validator->errors()->tojson(),
            ], 200);

        }

        $vehicle = Vehicle::where('status','Accept')
                    ->where('id',$request->vehicle_id)->first();
         if(empty($vehicle))
        {
            return response()->json([
                'successful' => false,
                'message' =>"Vehicle Not Found",
            ], 404);
        }
        if($bidder->id != $vehicle->bidder_id)
        {
            return response()->json([
                'successful' => false,
                'message' =>"The owner Only Who Can End The Auction",
            ], 404);
        }
        if(empty($vehicle))
        {
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'message' => 'This Vehicle Not Found',
            ], 404);
        }

        if($vehicle->aucations()->count() <= 0)
        {
            $vehicle->update(['status'=>'Canceled']);
            Notification::create([
                'bidder_id'=>$vehicle->bidder->id,
                'vehicle_id'=>$vehicle->id,
                'title'=>"Sorry :(",
                'body'=>'Your Car Canceled No One Bids It',
            ]);
            if($vehicle->bidder->device_token != null)
            {
                $token_1 = $vehicle->bidder->device_token;
                 Larafirebase::withTitle('Sorry :(')
                             ->withBody('Your Car Canceled No One Bids It')
                             ->withPriority('high')
                             ->withImage('https://firebase.google.com/images/social.png')
                             ->sendNotification($token_1);

            }

              return response()->json([
                'status' => false,
                'mesage'=>'This Car Canceled No ONe Bids',
            ], 404);
        }

        $dateForLastBid=date('d/m/y',strtotime($vehicle->aucations()->latest()->first()->created_at));
        if($dateForLastBid <= $vehicle->start_date)
        {
             $vehicle->update(['status'=>'Canceled']);
            Notification::create([
                'bidder_id'=>$vehicle->bidder->id,
                'vehicle_id'=>$vehicle->id,
                'title'=>"Sorry :(",
                'body'=>'Your Car Canceled No One Bids It',
            ]);
            if($vehicle->bidder->device_token != null)
            {
                $token_1 = $vehicle->bidder->device_token;
                 Larafirebase::withTitle('Sorry :(')
                             ->withBody('Your Car Canceled No One Bids It')
                             ->withPriority('high')
                             ->withImage('https://firebase.google.com/images/social.png')
                             ->sendNotification($token_1);

            }
              return response()->json([
                'status' => false,
                'mesage'=>'This Car Canceled No ONe Bids',
            ], 404);

        }


        $sumOfBidValue=$vehicle->aucations()->latest()->first()->bid_value ?? 0;
        $whoWinner=Bidder::find($vehicle->aucations()->latest()->first()->bidder_id);
        $owner=$vehicle->bidder;
        if($vehicle->sell_type=='PureSale')
        {
            $vehicle->update(['status'=>'Solid']);
            Notification::create([
                'bidder_id'=>$owner->id,
                'vehicle_id'=>$vehicle->id,
                'whowinner_id'=>$whoWinner->id,
                'title'=>"Congratulation",
                'body'=>'Your Car Solid By ' .$sumOfBidValue . ' to' . $whoWinner->name,
            ]);
            Notification::create([
                'bidder_id'=>$whoWinner->id,
                'vehicle_id'=>$vehicle->id,
                'whowner_id'=>$owner->id,
                'title'=>"Congratulation",
                'body'=>'You Win This Car By ' .$sumOfBidValue . ' From' . $owner->name,
            ]);
            if($whoWinner->device_token != null)
            {
                $token_1 = $whoWinner->device_token;
                // Larafirebase::withTitle('Congratulation')
                //             ->withBody('You Win This Car By ' .$sumOfBidValue . ' From' . $owner->name)
                //             ->withPriority('high')
                //             ->withImage('https://firebase.google.com/images/social.png')
                //             ->sendNotification($token_1);
                Larafirebase::fromRaw([
                    'registration_ids' => [$token_1],
                    'data' => [
                        'sumOfBidValue' => $sumOfBidValue,
                        'owner'     =>$owner,
                    ],
                    'android' => [
                        'ttl' => '1000s',
                        'priority' => 'normal',
                    ],
                ])->send();
            }
            if($owner->device_token != null)
            {
                $token_1 = $owner->device_token;
                // Larafirebase::withTitle('Congratulation')
                //             ->withBody('Your Car Solid By ' .$sumOfBidValue . ' to' . $whoWinner->name)
                //             ->withPriority('high')
                //             ->withImage('https://firebase.google.com/images/social.png')
                //             ->sendNotification($token_1);
                Larafirebase::fromRaw([
                    'registration_ids' => [$token_1],
                    'data' => [
                        'sumOfBidValue' => $sumOfBidValue,
                        'whoWinner'     =>$whoWinner,
                    ],
                    'android' => [
                        'ttl' => '1000s',
                        'priority' => 'normal',
                    ],
                ])->send();
            }
            Winner::create([
                'bidder_id'=>$whoWinner->id,
                'vehicle_id'=>$vehicle->id,
                'final_price'=>$sumOfBidValue,
            ]);
            return response()->json([
                'successful' => '0',
                'status' => '02',
                'mesage'=>'this car Solid',
                'final_price'=>$sumOfBidValue,
                'winner' =>  new bidderResource($whoWinner),
                'vehicle'=>  new VehicleResource($vehicle),
                'owner'  =>  new bidderResource($owner),
            ], 200);
        }else{
        //if the type is OnApprove
            if($vehicle->sell_type=='OnApprove')
            {
                if($sumOfBidValue >= $vehicle->retail_value){
                    $vehicle->update(['status'=>'Solid']);
                    Notification::create([
                        'bidder_id'=>$owner->id,
                        'vehicle_id'=>$vehicle->id,
                        'whowinner_id'=>$whoWinner->id,
                        'title'=>"Congratulation",
                        'body'=>'Your Car Solid By ' .$sumOfBidValue . ' to' . $whoWinner->name,
                    ]);
                    Notification::create([
                        'bidder_id'=>$whoWinner->id,
                        'vehicle_id'=>$vehicle->id,
                        'whowner_id'=>$owner->id,
                        'title'=>"Congratulation",
                        'body'=>'You Win This Car By ' .$sumOfBidValue . ' From' . $owner->name,
                    ]);
                    if($whoWinner->device_token != null)
                    {
                        $token_1 = $whoWinner->device_token;
                        // Larafirebase::withTitle('Congratulation')
                        //             ->withBody('You Win This Car By ' .$sumOfBidValue . ' From' . $owner->name)
                        //             ->withPriority('high')
                        //             ->withImage('https://firebase.google.com/images/social.png')
                        //             ->sendNotification($token_1);
                        Larafirebase::fromRaw([
                            'registration_ids' => [$token_1],
                            'data' => [
                                'sumOfBidValue' => $sumOfBidValue,
                                'owner'     =>$owner,
                            ],
                            'android' => [
                                'ttl' => '1000s',
                                'priority' => 'normal',
                            ],
                        ])->send();
                    }
                    if($owner->device_token != null)
                    {
                        $token_1 = $owner->device_token;
                        // Larafirebase::withTitle('Congratulation')
                        //             ->withBody('Your Car Solid By ' .$sumOfBidValue . ' to' . $whoWinner->name)
                        //             ->withPriority('high')
                        //             ->withImage('https://firebase.google.com/images/social.png')
                        //             ->sendNotification($token_1);
                        Larafirebase::fromRaw([
                            'registration_ids' => [$token_1],
                            'data' => [
                                'sumOfBidValue' => $sumOfBidValue,
                                'whoWinner'     =>$whoWinner,
                            ],
                            'android' => [
                                'ttl' => '1000s',
                                'priority' => 'normal',
                            ],
                        ])->send();
                    }
                    Winner::create([
                        'bidder_id'=>$whoWinner->id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$sumOfBidValue,
                    ]);
                    return response()->json([
                        'successful' => '0',
                        'status' => '02',
                        'mesage'=>'this car Solid ',
                        'final_price'=>$sumOfBidValue,
                        'winner' =>  new bidderResource($whoWinner),
                        'vehicle'=>  new VehicleResource($vehicle),
                        'owner'  =>  new bidderResource($owner),
                    ], 200);
                }
            else{

                    $vehicle->update(['status'=>'Counter']);
                    Notification::create([
                        'bidder_id'=>$owner->id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>"Congratulation",
                        'body'=>'Your Car Enter Negotiations With ' . $whoWinner->name .' Last Price ' . $sumOfBidValue,
                    ]);
                    Notification::create([
                        'bidder_id'=>$whoWinner->id,
                        'vehicle_id'=>$vehicle->id,
                        'whowner_id'=>$owner->id,
                        'title'=>"Congratulation",
                        'body'=>'This Car Enter Negotiations With Owner ' . $owner->name  .' Last Price ' . $sumOfBidValue,
                    ]);
                    if($whoWinner->device_token != null)
                    {
                        $token_1 = $whoWinner->device_token;
                        Larafirebase::withTitle('Hi' . $whoWinner->name)
                                    ->withBody('Car Enter Negotiations With Owner ' . $owner->name  .' Last Price ' . $sumOfBidValue)
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($token_1);
                        // Larafirebase::fromRaw([
                        //     'registration_ids' => [$token_1],
                        //     'data' => [
                        //         'sumOfBidValue' => $sumOfBidValue,
                        //         'owner'     =>$owner,
                        //     ],
                        //     'android' => [
                        //         'ttl' => '1000s',
                        //         'priority' => 'normal',
                        //     ],
                        // ])->send();
                    }
                    if($owner->device_token != null)
                    {
                        $token_1 = $owner->device_token;
                        Larafirebase::withTitle('Congratulation')
                                    ->withBody('Your Car Enter Negotiations With ' . $whoWinner->name .' Last Price ' . $sumOfBidValue)
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($token_1);
                        // Larafirebase::fromRaw([
                        //     'registration_ids' => [$token_1],
                        //     'data' => [
                        //         'sumOfBidValue' => $sumOfBidValue,
                        //         'whoWinner'     =>$whoWinner,
                        //     ],
                        //     'android' => [
                        //         'ttl' => '1000s',
                        //         'priority' => 'normal',
                        //     ],
                        // ])->send();
                    }
                    Counter::create([
                        'bidder_id'=>$whoWinner->id,
                        'owner_id'=>$owner->id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$sumOfBidValue,
                        'who'       =>'buyer',
                        'type'      =>'offer'
                    ]);
                    return response()->json([
                        'successful' => '0',
                        'status' => '02',
                        'mesage'=>'this car Enter Counter',
                        'final_price'=>$sumOfBidValue,
                        'Who Neg' =>  new bidderResource($whoWinner),
                        'vehicle'=>  new VehicleResource($vehicle),
                        'owner'  =>  new bidderResource($owner),
                    ], 200);
             }
         }
        //end the type On Approve

        }

    }

    public function realtimeCounter(Request $request)
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

        $validator=Validator::make($request->all(),[
            'vehicle_id'    =>'required | exists:vehicles,id',
            'bid_value'     =>'required | min:1',
            'status'        =>['required','string',Rule::in('cancel','approve','stay','offer','prepost')],
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->tojson(),
            ], 403);
        }

        $vehicle = Vehicle::where('status','Counter')
                    ->where('id',$request->vehicle_id)->first();
        if(empty($vehicle))
        {
            return response()->json([

                'status' => false,
                'message' => 'This Vehicle Not Found',
            ], 403);
        }
        $lastVehicleCounter=Counter::where('vehicle_id',$request->vehicle_id)->latest()->first();
        //This Three Varibles For deally with counter
        $bidder_id=$lastVehicleCounter->bidder_id;
        $owner_id=$lastVehicleCounter->owner_id;
        $auth_owner=false;

        if($lastVehicleCounter->owner_id==$bidder->id)
        {
            $auth_owner=true;
        }
        //End This Three Varibles For deally with counter
        //start cacel cars
        if($request->status == 'cancel')
        {
                $vehicle->update(['status'=>'Canceled']);
                //ownet who auth
                if($auth_owner==true)
                {
                    $buyer=Bidder::find($bidder_id);
                    Notification::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $buyer->name,
                        'body'=>'Sorry The Owner Cancel his Car',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'seller',
                        'type'      =>'cancel'
                    ]);
                    if( $buyer->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $buyer->name)
                                    ->withBody('Sorry The Owner Cancel his Car')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($buyer->device_token);
                    }
                    return response()->json([
                        'successful' => '1',
                        'status' => '01',
                        'message' => 'This Vehicle Cancel By Owner',
                    ], 404);
                }
                //end owner Cancel the car
                //buyer who cancel
                if($auth_owner==false)
                {
                    $owner=Bidder::find($owner_id);
                    Notification::create([
                        'bidder_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $owner->name,
                        'body'=>'Sorry The Buyer Cancel Your Car',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'buyer',
                        'type'      =>'cancel'
                    ]);
                    if( $owner->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $owner->name)
                                    ->withBody('Sorry The Buyer Cancel Your Car')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($owner->device_token);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Cancel By Buyer',
                    ], 200);
                }
                //end buyer Cancel the car
        }
        //end  cancel cars

        //start approve cars
        if($request->status == 'approve')
        {
                if($vehicle->counters()->count() <= 0)
                {
                     return response()->json([
                            'status' => false,
                            'message' => 'You Can not Approve NO Offer',
                        ], 403);
                }
                //ownet who auth
                if($auth_owner==true)
                {
                    $count=$vehicle->counters()->where('type','offer')->latest()->first();
                    if($count->who == 'seller')
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'You Can not Approve Your Offer',
                        ], 403);
                    }

                    $vehicle->update(['status'=>'Solid']);
                    Winner::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                    ]);
                    $buyer=Bidder::find($bidder_id);
                    Notification::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $buyer->name,
                        'body'=>'Congrateulation The Owner Approve And You Win The Car',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'seller',
                        'type'      =>'approve'
                    ]);
                    if( $buyer->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $buyer->name)
                                    ->withBody('Congrateulation The Owner Approve And You Win The Car')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($buyer->device_token);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Approve By Owner',
                    ], 200);
                }
                //end owner approve the car
                //buyer who approve
                if($auth_owner==false)
                {
                    $count=$vehicle->counters()->where('type','offer')->latest()->first();
                    if($count->who == 'buyer')
                    {
                        return response()->json([
                            'status' => false,
                            'message' => 'You Can not Approve Your Offer',
                        ], 403);
                    }
                    $vehicle->update(['status'=>'Solid']);
                    Winner::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                    ]);
                    $owner=Bidder::find($owner_id);
                    Notification::create([
                        'bidder_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $owner->name,
                        'body'=>'The Buyer Approve Your Price And Win By Your Car',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'buyer',
                        'type'      =>'approve'
                    ]);
                    if( $owner->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $owner->name)
                                    ->withBody('The Buyer Approve Your Price And Win By Your Car')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($owner->device_token);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Approve By Buyer',
                    ], 200);
                }
                //end buyer approve the car
        }
        //end  approve cars
        //start stay cars
        if($request->status == 'stay')
        {
                //ownet who stay
                if($auth_owner==true)
                {
                    $buyer=Bidder::find($bidder_id);
                    Notification::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $buyer->name,
                        'body'=>'The Owner Stay on his Price',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'seller',
                        'type'      =>'stay'
                    ]);
                    if( $buyer->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $buyer->name)
                                    ->withBody('The Owner Stay on his Price')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($buyer->device_token);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Stay By Owner',
                    ], 200);
                }
                //end owner stay the car
                //buyer who stay
                if($auth_owner==false)
                {
                    $owner=Bidder::find($owner_id);
                    Notification::create([
                        'bidder_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $owner->name,
                        'body'=>'The Buyer Stay on his Price',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'buyer',
                        'type'      =>'stay'
                    ]);
                    if( $owner->device_token != null)
                    {
                        Larafirebase::withTitle('Hi ' . $owner->name)
                                    ->withBody('The Buyer Stay on his Price')
                                    ->withPriority('high')
                                    ->withImage('https://firebase.google.com/images/social.png')
                                    ->sendNotification($owner->device_token);
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Stay By Buyer',
                    ], 200);
                }
                //end buyer stay the car
        }
        //end  stay cars
        //start offer cars
        if($request->status == 'offer')
        {
                //ownet who stay
                if($auth_owner==true)
                {
                    $buyer=Bidder::find($bidder_id);
                    Notification::create([
                        'bidder_id'=>$bidder_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $buyer->name,
                        'body'=>'The Car Owner Send New Offer',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'seller',
                        'type'      =>'offer'
                    ]);
                    if( $buyer->device_token != null)
                    {
                        // Larafirebase::withTitle('Hi ' . $buyer->name)
                        //             ->withBody('The Car Owner Send New Offer')
                        //             ->withPriority('high')
                        //             ->withImage('https://firebase.google.com/images/social.png')
                        //             ->sendNotification($buyer->device_token);
                        Larafirebase::fromRaw([
                            'registration_ids' => [$buyer->device_token],
                            'data' => [
                                'bid_value' => $request->bid_value,
                            ],
                            'android' => [
                                'ttl' => '1000s',
                                'priority' => 'normal',
                            ],
                        ])->send();
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle offer By Owner',
                    ], 200);
                }
                //end owner offer the car
                //buyer who offer
                if($auth_owner==false)
                {
                    $owner=Bidder::find($owner_id);
                    Notification::create([
                        'bidder_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'title'=>'Hi ' . $owner->name,
                        'body'=>'The Buyer new Offer',
                    ]);
                    Counter::create([
                        'bidder_id'=>$bidder_id,
                        'owner_id'=>$owner_id,
                        'vehicle_id'=>$vehicle->id,
                        'final_price'=>$request->bid_value,
                        'who'       =>'buyer',
                        'type'      =>'offer'
                    ]);
                    if( $owner->device_token != null)
                    {
                        // Larafirebase::withTitle('Hi ' . $owner->name)
                        //             ->withBody('The Buyer new Offer')
                        //             ->withPriority('high')
                        //             ->withImage('https://firebase.google.com/images/social.png')
                        //             ->sendNotification($owner->device_token);

                        Larafirebase::fromRaw([
                            'registration_ids' => [$owner->device_token],
                            'data' => [
                                'bid_value' => $request->bid_value,
                            ],
                            'android' => [
                                'ttl' => '1000s',
                                'priority' => 'normal',
                            ],
                        ])->send();

                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'This Vehicle Offer By Buyer',
                    ], 200);
                }
                //end buyer offer the car
                //start offer cars
            }
        if($request->status == 'prepost')
        {
            //ownet who stay
            if($auth_owner==true)
            {
                $vehicle->update(['status'=>'Pending']);
                $buyer=Bidder::find($bidder_id);
                Notification::create([
                    'bidder_id'=>$bidder_id,
                    'vehicle_id'=>$vehicle->id,
                    'title'=>'Hi ' . $buyer->name,
                    'body'=>'The Car Owner Cancel It And Prepost It',
                ]);
                if($vehicle->aucations()->count() > 0)
                {
                    $vehicle->aucations()->delete();
                }
                if($vehicle->aucations()->count() > 0)
                {
                    $vehicle->counters()->delete();
                }
                if( $buyer->device_token != null)
                {
                    Larafirebase::withTitle('Hi ' . $buyer->name)
                                ->withBody('The Car Owner Cancel It And Prepost It')
                                ->withPriority('high')
                                ->withImage('https://firebase.google.com/images/social.png')
                                ->sendNotification($buyer->device_token);

                }
                $bidreq=Bidderrequest::create([
                    'bidder_id'     =>$owner_id,
                    'vehicle_id'    =>$vehicle->id,
                    'note'          =>$request->note,
                ]);
                if(!empty($bidreq)){
                    return response()->json([
                        "status"    => true,
                        'message'   => "You Request Send Successfully",
                    ],200);
                }

            }
                    return response()->json([
                        "status"    => false,
                        'message'   => "The Owner Only Who Can Prepost The Car",
                    ],403);
        } //end owner offer the car

}}