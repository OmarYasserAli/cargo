<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $table = 'add_shipment_tb_';
    protected $primaryKey = 'code_';
    public $timestamps = false;
    protected $casts = [
        'shipment_coast_' => 'double',
        'tawsil_coast_' => 'double',
        'tas3ir_mandoub_estlam' => 'double',
        'tas3ir_mandoub_taslim' =>'double',
        'total_' => 'double',
        'code_' =>'string',
    ];
    public function Shipment_status()
    {
        return $this->belongsTo(Shipment_status::class,'Status_');
    }
    
    public function Commercial_name()
    {
        return $this->belongsTo(Commercial_name::class);
    }
    public function Branch_user()
    {
        return $this->belongsTo(Branch_user::class,'Delivery_Delivered_Shipment_ID');
    }

    

    public function scopeUserType($query,$type, $id)
    {
        if($type=="عميل")
            return $query->where('client_ID_',$id);
        elseif($type=="مندوب تسليم")
            return $query->where('Delivery_Delivered_Shipment_ID', $id);
        
        elseif($type=="مندوب استلام")
            return $query->where('Delivery_take_shipment_ID', $id);
    }
    
    // public function scopedateFilter($query,$from, $to)
    // {
    //     dd($this->all());
       
           
    //         if(isset($from))
    //         return $query->where('tarikh_tasdid_el3amil' ,'>=',DATE( $from) );
    //         if(isset($to))
               
    //             return $query->where('tarikh_el7ala' ,'>=',DATE( $from) );
              
    // }


}
