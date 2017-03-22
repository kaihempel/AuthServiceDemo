<?php namespace App\Model\Socialaccount;

use App\Model\Facebook\OAuth\FacebookUserData;
use App\Model\OAuth\OAuthUserData;
use DateTime;

/**
 * Class SocialaccountConverter
 * @package App\Model\Socialaccount
 */
class SocialaccountConverter
{
    /**
     * @var Socialaccount
     */
    private $socialaccount = null;

    /**
     * @var Socialaccountdata
     */
    private $socialaccountdata = null;

    /**
     * Initialize the converter with oauth data
     *
     * @param OAuthUserData $userData
     * @return SocialaccountConverter
     */
    public static function initializeFromOAuthData(OAuthUserData $userData)
    {
        $converter = new SocialaccountConverter();
        $converter->socialaccountFromOAuthUserData($userData);

        if ($userData instanceof FacebookUserData) {
            $converter->socialaccountdataFromFacebookUserData($userData);
        }

        return $converter;
    }

    /**
     * Returns the current socialaccount instance
     *
     * @return Socialaccount
     */
    public function getSocialaccount()
    {
        if (empty($this->socialaccount)) {
            $this->socialaccount = new Socialaccount();
        }

        return $this->socialaccount;
    }

    /**
     * Returns the current socialaccountdata instance
     *
     * @return Socialaccountdata
     */
    public function getSocialaccountdata()
    {
        if (empty($this->socialaccountdata)) {
            $this->socialaccountdata = new Socialaccountdata();
        }

        return $this->socialaccountdata;
    }

    /**
     * Initialize socialaccount model instance from oauth data instance
     *
     * @param FacebookUserData $userData
     * @return void
     */
    public function socialaccountFromOAuthUserData(OAuthUserData $userData)
    {
        $data = [
            'id'            => $userData->id,
            'token'         => $userData->token,
            'provider'      => Socialaccount::FACEBOOK_PROVIDER,
            'is_active'     => true
        ];

        $this->socialaccount = new Socialaccount($data);
    }

    /**
     * Initialize socialaccountdata instance from facebook data
     *
     * @param FacebookUserData $userData
     * @return void
     */
    public function socialaccountdataFromFacebookUserData(FacebookUserData $userData)
    {
        $birthday = new DateTime($userData->birthday);

        $data = [
            'id'            => $userData->id,
            'email'         => $userData->email,
            'gender'        => $userData->gender,
            'firstname'     => $userData->firstname,
            'surname'       => $userData->surname,
            'birthday'      => $birthday,
            'picture_url'   => $userData->pictureUrl
        ];

        $this->socialaccountdata = new Socialaccountdata($data);
    }
}