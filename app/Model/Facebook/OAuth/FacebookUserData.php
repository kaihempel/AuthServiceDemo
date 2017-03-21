<?php namespace App\Model\Facebook\OAuth;

use App\Model\OAuth\OAuthUserData;

/**
 * Class FacebookUserData
 *
 * @package App\Model\Facebook\OAuth
 */
class FacebookUserData extends OAuthUserData
{
    /**
     * @var string The gender
     */
    public $gender;

    /**
     * @var string The firstname
     */
    public $firstname;

    /**
     * @var string The surname
     */
    public $surname;

    /**
     * @var \DateTime The birthdate
     */
    public $birthday;

    /**
     * @var string The users account image
     */
    public $pictureUrl;
}