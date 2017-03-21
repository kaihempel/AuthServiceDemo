<?php namespace App\Model\Facebook;

use App\Model\Facebook\OAuth\FacebookProvider;
use App\Model\OAuth\OAuthProviderFactoryInterface;
use Facebook\Facebook;

/**
 * Class FacebookFactory
 *
 * @package App\Model\Facebook
 */
class FacebookFactory implements OAuthProviderFactoryInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var array
     */
    private $permissions = [
        'email'
    ];

    /**
     * OAuth provider instance
     *
     * @var FacebookProvider
     */
    private $provider = null;

    /**
     * FacebookFactory constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $callbackUrl
     */
    public function __construct($clientId, $clientSecret, $callbackUrl)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->callbackUrl = $callbackUrl;
    }

    /**
     *
     *
     * @return FacebookProvider
     */
    public function getProvider()
    {
        if (! empty($this->provider)) {
            return $this->provider;
        }

        $this->provider = $this->initializeProvider();
        return $this->provider;
    }

    /**
     * Initialize the provider instance
     */
    private function initializeProvider()
    {
        $fb = new Facebook([
            'app_id'                => $this->clientId,
            'app_secret'            => $this->clientSecret,
            'default_graph_version' => Facebook::DEFAULT_GRAPH_VERSION
        ]);

        return new FacebookProvider($fb, $this->callbackUrl, $this->permissions);
    }
}