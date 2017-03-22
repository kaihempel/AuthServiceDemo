<?php

namespace App\Providers;

use App\Model\Facebook\FacebookFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('App\Model\Facebook\OAuth\FacebookProvider', $this->initializeFacebookProvider());
    }

    /**
     * Initialize the facebook oauth provider
     *
     * @return \App\Model\Facebook\OAuth\FacebookProvider
     */
    private function initializeFacebookProvider()
    {
        $clientId       = config('services.facebook.client_id');
        $clientSecret   = config('services.facebook.client_secret');
        $callbackUrl    = config('services.facebook.callback_url');
        $session        = $this->app->session->driver();

        $factory = new FacebookFactory($clientId, $clientSecret, $callbackUrl, $session);
        return $factory->getProvider();
    }
}
