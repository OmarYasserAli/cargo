<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mantikqa extends Model
{
	use \Awobaz\Compoships\Compoships;
    protected $primaryKey = 'serial';
    protected $table = 'edaft_manateq_iraq_tb';
    protected $guarded = [];
    
    public function Tas3ir_3amil()
    {
        return $this->hasOne(Tas3ir_3amil::class,  ['area_name_' ,'city_name_'],['name', 'mo7afza']);
    }
    public function Tas3ir_ta7wel()
    {
        return $this->hasOne(Tas3ir_ta7wel::class,  ['area_name_' ,'city_name_'],['name', 'mo7afza']);
    }
}
