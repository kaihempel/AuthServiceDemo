<?php namespace App\Model\OAuth;

/**
 * Interface OAuthProviderInterface
 * Main provider interface:
 * Defines all the necessary methods for oauth authentication.
 *
 * @package App\Model\OAuth
 * @author Kai Hempel <kai.hempel@kuweh.de>
 */
interface OAuthProviderInterface {

    /**
     * Returns the oauth providers login url
     *
     * @return string The login url
     */
    public function getLoginUrl();

    /**
     * Returns the oauth providers permission request url
     *
     * @return string The permission request url
     */
    public function getRequestPermissionUrl();

    /**
     * Retrieves an access token from an auth code
     *
     * @return string
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function fetchAccessToken();

    /**
     * Validate an access token a
     * @param string $token
     * @return boolean
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function validateAccessToken($token);

}