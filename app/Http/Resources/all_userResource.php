<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class all_userResource extends JsonResource
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
           'code_'=>$this->code_,
           'name_'=>$this->name_,
           'type_'=>$this->type_,
           'status_'=>$this->status_,
           'serial_'=>$this->serial_,
           'branch'=>$this->branch,
           'USERNAME'=>$this->USERNAME,
           'mo7fza'=>$this->mo7fza,
           'mantqa'=>$this->mantqa,
        ];
    }
}