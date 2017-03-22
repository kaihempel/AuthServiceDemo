<?php namespace App\Http\Controllers;

use App\Model\Facebook\OAuth\FacebookProvider;
use Exception;

/**
 * Class OAuthController
 *
 * @package App\Http\Controllers
 */
class OAuthController extends Controller
{

    /**
     * The facebook oauth provider
     *
     * @var FacebookProvider
     */
    private $fb;

    /**
     * OAuthController constructor.
     * @param FacebookProvider $fb
     */
    public function __construct(FacebookProvider $fb)
    {
        $this->fb = $fb;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        return redirect($this->fb->getLoginUrl());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        try {

            $token = $this->fb->fetchAccessToken();
            $userData = $this->fb->getUserData($token->getValue());

        } catch (Exception $e) {
            return redirect()->route('login-error');
        }

        return view('oauth/success');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function error()
    {
        return view('oauth/error');
    }

}