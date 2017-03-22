<?php namespace App\Model\OAuth;

/**
 * Interface OAuthProviderUserInterface
 * Provider extension for user data load.
 *
 * @package App\Model\OAuth
 * @author Kai Hempel <kai.hempel@kuweh.de>
 */
interface OAuthProviderUserInterface
{
    /**
     * Validates the token and loads the user data
     *
     * @param string $token The oauth token
     * @return OAuthUserData The user data
     * @throws OAuthException General error
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function getUserData($token);
}