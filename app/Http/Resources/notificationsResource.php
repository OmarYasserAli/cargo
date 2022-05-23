<?php

namespace App\Http\Resources;
use App\Http\Resources\BidderResource;
use App\Http\Resources\VehicleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class notificationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    =>$this->id,
            'title'=>$this->title,
            'body'=>$this->body,
            'bidder'=>$this->bidder_id != null ? new BidderResource($this->bidder) : null,
            'winner'=>$this->whowinner_id != null ? new BidderResource($this->winner) : null,
            'owner'=>$this->whowner_id != null ? new BidderResource($this->owner) : null,
            'vehicle'=>$this->vehicle_id != null ? new VehicleResource($this->vehicle) : null,
            'read'=>$this->read_at != null ? true : false,
            'create_at'=>date('d/m/y h:i',strtotime($this->created_at)),
        ];
    }
}