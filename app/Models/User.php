<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable , HasApiTokens;
    use GetImageTrait;
    const UPLOAD_FOLDER = '/uploads/userImages/';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','mobile', 'nationality', 'bio', 'image',
        'isVerify', 'actor_id' , 'provider', 'provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['image_path'];

    public function addNewProviderUser($input)
    {
        $check = static::where('provider_id',$input['provider_id'])->first();
        if(is_null($check)){
            return static::create($input);
        }
        return $check;
    }

    public function profile()
    {
        return $this->hasOne(UserInformation::class);
    }

    public function document()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function position()
    {
        return $this->hasMany(PlayerPosition::class);
    }

    public function owner()
    {
        return $this->hasMany(OwnerUser::class,'field_owner_id');
    }
}
