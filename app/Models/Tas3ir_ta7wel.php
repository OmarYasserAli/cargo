<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tas3ir_ta7wel extends Model
{
	use \Awobaz\Compoships\Compoships;
	public $timestamps = false;
	
    protected $primaryKey = 'code_';
    protected $table = 'transfer_prices_main_tb';
    protected $guarded = [];
}
