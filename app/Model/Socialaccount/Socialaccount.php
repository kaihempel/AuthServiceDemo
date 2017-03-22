<?php namespace App\Model\Socialaccount;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Socialaccount
 *
 * @package App\Model\Socialaccount
 */
class Socialaccount extends Authenticatable
{
    /**
     * The provider value
     */
    const FACEBOOK_PROVIDER = 'facebook';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'token',
        'provider',
        'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialaccountdata()
    {
        return $this->hasOne('App\Model\Socialaccount\Socialaccountdata');
    }
}
