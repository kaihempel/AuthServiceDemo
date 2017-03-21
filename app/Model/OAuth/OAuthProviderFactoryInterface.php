<?php namespace App\Model\OAuth;

/**
 * Interface OAuthProviderFactoryInterface
 * Factory definition.
 *
 * @package App\Model\OAuth
 * @author Kai Hempel <kai.hempel@kuweh.de>
 */
interface OAuthProviderFactoryInterface
{
    /**
     *
     * @return mixed
     */
    public function getProvider();
}