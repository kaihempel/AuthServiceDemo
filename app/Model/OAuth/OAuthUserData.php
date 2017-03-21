<?php namespace App\Model\OAuth;

/**
 * Class OAuthUserData
 * Simple data container object
 *
 * @package App\Model\OAuth
 * @author Kai Hempel <kai.hempel@kuweh.de>
 */
class OAuthUserData
{
    /**
     * @var integer The user id
     */
    public $id;

    /**
     * @var string The oauth access token
     */
    public $token;

    /**
     * @var string The email address
     */
    public $email;

}