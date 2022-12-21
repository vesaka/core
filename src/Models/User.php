<?php

namespace Vesaka\Core\Models;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Vesaka\Core\Traits\MetaTrait;

 
class User extends Authenticatable
{
    use Notifiable, HasApiTokens, MetaTrait, AclTraitneAware;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'locale', 'timezone'
    ];

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
    
    protected $collection = 'user';
    
    protected $metables = [];
    
    public function meta() {
        
        return $this->hasMany('Vesaka\Core\Models\UserMeta', 'user_id', 'id');
    }
    
    public function getLocaleAttribute() {
        if (isset($this->attributes['locale'])) {
            return $this->attributes['locale'];
        }
        
        return config('app.locale');
    }
    
    public function preferredLocale() {
        return $this->locale; 
    }
    
    public function getMetables() {
        return $this->metables;
    }
    
    public function getTokenAttribute() {
        return $this->currentAccessToken()->plainTextToken;
    }
}
