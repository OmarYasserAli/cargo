<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;




class User extends Authenticatable
{
    use Notifiable, LaratrustUserTrait, HasApiTokens;
    protected $table = 'all_users';
    public $timestamps = false;
    protected $primaryKey = 'code_';
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $append = ['image_path'];

    public function getImagePathAttribute(){
        return $this->image != null ? asset('uploads/user_images/'.$this->image) :  asset('uploads/user_images/default.png') ;
    }


  

}
