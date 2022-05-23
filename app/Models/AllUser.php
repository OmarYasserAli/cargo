<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class AllUser extends Authenticatable implements JWTSubject
{
    use Notifiable, LaratrustUserTrait, HasApiTokens;
    protected $primaryKey = 'code_';
    protected $guarded = [];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'type'  =>'all_user',
        ];
    }
    public function getAuthPassword() {
        return $this->PASSWORD;
    }
}
