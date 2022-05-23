<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment_status extends Model
{
    protected $primaryKey = 'code_';
    protected $table = 'add_status_for_shipment_tb';
    public function scopeUserTypeFilter($query,$type, $id)
    {
        
        if($type=="مندوب تسليم")
            return $query->whereIn('code_', [6,7,4]);
        
        elseif($type=="مندوب استلام")
            return $query->whereIn('code_', [3,2]);
    }
}
