<?php namespace App\Model\Socialaccount;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Socialaccountdata
 *
 * @package App\Model\Socialaccount
 */
class Socialaccountdata extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'email',
        'gender',
        'firstname',
        'surname',
        'birthday',
        'picture_url'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialaccountdata()
    {
        return $this->hasOne('App\Model\Socialaccount\Socialaccount');
    }
}
