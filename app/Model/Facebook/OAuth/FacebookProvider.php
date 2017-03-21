<?php namespace App\Model\Facebook\OAuth;

use App\Model\OAuth\OAuthProviderInterface;
use App\Model\OAuth\OAuthProviderUserInterface;
use App\Model\OAuth\Exception\OAuthException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;
use Facebook\Authentication\AccessToken;
use Facebook\Authentication\AccessTokenMetadata;
use Facebook\PseudoRandomString\PseudoRandomStringGeneratorFactory;

/**
 * Class OAuthProvider
 *
 * @package App\Model\Facebook\OAuth
 */
class FacebookProvider implements OAuthProviderInterface, OAuthProviderUserInterface
{
    /**
     * @const int The length of CSRF string to validate the login link.
     */
    const CSRF_LENGTH = 32;

    /**
     * @var Facebook
     */
    private $fb;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var array
     */
    private $permissions;

    /**
     * OAuthProvider constructor.
     * @param Client $client
     */
    public function __construct(Facebook $fb, $callbackUrl, array $permissions)
    {
        $this->fb = $fb;
        $this->callbackUrl = $callbackUrl;
        $this->permissions = $permissions;
    }

    /**
     * Returns the oauth providers login url
     *
     * @return string The login url
     */
    public function getLoginUrl()
    {
        $client = $this->fb->getOAuth2Client();
        return $client->getAuthorizationUrl(
            $this->callbackUrl,
            $this->generateStateToken(),
            $this->permissions,
            [
                'display' => 'popup'
            ]
        );
    }

    /**
     * Returns the oauth providers permission request url
     *
     * @return string The permission request url
     */
    public function getRequestPermissionUrl()
    {
        $client = $this->fb->getOAuth2Client();
        return $client->getAuthorizationUrl(
            $this->callbackUrl,
            $this->generateStateToken(),
            $this->permissions,
            [
                'display' => 'popup',
                'auth_type' => 'rerequest'
            ]
        );
    }

    /**
     * Retrieves an access token from an auth token
     *
     * @param string $code The auth code
     * @return \Facebook\Authentication\AccessToken
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function fetchAccessToken()
    {
        $helper = $this->fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (FacebookSDKException $e) {
            throw new OAuthException('Fetch of access token failed: ' . $e->getMessage());
        }

        $this->validateAccessToken($accessToken);

        return $accessToken;
    }

    /**
     * Validate an access token
     *
     * @param \Facebook\Authentication\AccessToken $token
     * @return boolean
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function validateAccessToken($token)
    {
        $accessToken = $this->generateAccessTokenFromString($token);
        $metaData = $this->getMetaDataFromToken($accessToken);
        $this->checkScopes($metaData);

        try {

            if (! $metaData->getIsValid()) {
                throw new InvalidTokenException('Token is invalid');
            }

            $metaData->validateAppId($this->fb->getApp()->getId());
            $metaData->validateExpiration();

        } catch(FacebookSDKException $e) {
            throw new InvalidTokenException($e->getMessage());
        }
    }

    /**
     * Validates the token and loads the user data
     *
     * @param AccessToken $token The oauth token
     * @return FacebookUserData The user data
     * @throws OAuthException General error
     * @throws InvalidTokenException Invalid oauth token
     * @throws MissingScopeException Scope permissions missing
     */
    public function getUserData($token)
    {
        $accessToken = $this->generateAccessTokenFromString($token);
        $this->validateAccessToken($accessToken);

        return $this->loadUserData($accessToken);
    }

    /**
     * Checks the granted permissions
     *
     * @param AccessTokenMetadata $metaData
     * @throws MissingScopeException Scope permissions missing
     */
    private function checkScopes(AccessTokenMetadata $metaData)
    {
        if (! in_array('email', $metaData->getScopes())) {
            throw new MissingScopeException('No email is set!');
        }
    }

    /**
     * Generates a random csrf token to secure oauth handle against manipulation
     *
     * @return string
     */
    private function generateStateToken()
    {
        $randomGenerator = PseudoRandomStringGeneratorFactory::createPseudoRandomStringGenerator(null);
        return $randomGenerator->getPseudoRandomString(self::CSRF_LENGTH);
    }

    /**
     * Creates new access token instance from given token string
     *
     * @param string $token
     * @return AccessToken
     */
    private function generateAccessTokenFromString($token)
    {
        return new AccessToken($token);
    }

    /**
     * Extracts the metadata from given access token
     *
     * @param AccessToken $accessToken
     * @return \Facebook\Authentication\AccessTokenMetadata
     */
    private function getMetaDataFromToken(AccessToken $accessToken)
    {
        $client = $this->fb->getOAuth2Client();
        return $client->debugToken($accessToken);
    }

    /**
     * Loads the user data from facebook api
     *
     * @param AccessToken $token
     * @return FacebookUserData
     * @throws OAuthException
     */
    private function loadUserData(AccessToken $token)
    {

        try {
            $user       = $this->fb->get('/me?fields=id,email,gender,first_name,last_name', $token)->getGraphUser();
            $picture    = $this->fb->get('/me/picture?width=100&height=100&redirect=false', $token)->getGraphUser();

        } catch(FacebookSDKException $e) {
            throw new OAuthException($e->getMessage());
        }

        if (empty($user->getEmail())) {
            throw new OAuthException('Facebook email is missing');
        }

        $data = new FacebookUserData();
        $data->id           = $user->getId();
        $data->token        = $token->getValue();
        $data->email        = $user->getEmail();
        $data->gender       = (! empty($user->getGender())) ? $user->getGender() : '';
        $data->firstname    = $user->getFirstName();
        $data->surname      = $user->getLastName();
        $data->pictureUrl   = $picture->getField('url');

        return $data;
    }
}