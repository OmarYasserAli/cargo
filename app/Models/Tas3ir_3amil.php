<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tas3ir_3amil extends Model
{
	public $timestamps = false;
	use \Awobaz\Compoships\Compoships;
    protected $primaryKey = 'code_';
    protected $table = 'prices_tb';
    protected $guarded = [];
}
