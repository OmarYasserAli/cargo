<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchInfo extends Model
{
    protected $primaryKey = 'serial';
    protected $table = 'branch_info_tb';
    protected $guarded = [];
    
}
