<?php namespace App\Http\Controllers;

use App\Model\Facebook\OAuth\FacebookProvider;
use App\Model\OAuth\OAuthUserData;
use App\Model\Socialaccount\Socialaccount;
use App\Model\Socialaccount\SocialaccountConverter;
use App\Model\Socialaccount\Socialaccountdata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class OAuthController
 *
 * @package App\Http\Controllers
 */
class OAuthController extends Controller
{

    /**
     * The current authenticated user
     *
     * @var null
     */
    private $socialaccount = null;

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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function login()
    {
        return redirect($this->fb->getLoginUrl());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success()
    {
        try {

            $token = $this->fb->fetchAccessToken();
            $userData = $this->fb->getUserData($token->getValue());

        } catch (Exception $e) {

            Log::error('OAuth login failed: ' . $e->getMessage());
            return redirect()->route('login-error');
        }

        if ($this->persistUserData($userData) === false) {
            return redirect()->route('login-error');
        }

        // Set the auth status

        Auth::login($this->socialaccount, true);

        // Show success page

        return view('oauth/success');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function error()
    {
        return view('oauth/error');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function revoke()
    {
        return view('oauth/revoke');
    }

    /**
     * Saves the given data as socialaccount
     *
     * @param OAuthUserData $userData
     * @return boolean
     */
    private function persistUserData(OAuthUserData $userData)
    {
        DB::beginTransaction();

        try {
            $socialaccount = Socialaccount::find($userData->id);

            if ($socialaccount) {
                $this->updateExisting($socialaccount);
            } else {
                $this->insertNewUserData($userData);
            }

        } catch (Exception $e) {

            DB::rollBack();
            Log::error('User data save failed: ' . $e->getMessage());

            return false;
        }

        DB::commit();
        return true;
    }

    /**
     * @param OAuthUserData $userData
     */
    private function insertNewUserData(OAuthUserData $userData)
    {
        $converter          = SocialaccountConverter::initializeFromOAuthData($userData);
        $socialaccount      = $converter->getSocialaccount();
        $socialaccountdata  = $converter->getSocialaccountdata();

        if (! empty($socialaccount->id)) {
            $socialaccount->save();

            // Store the current user in local reference

            $this->socialaccount = $socialaccount;
        }

        if (! empty($socialaccountdata->id)) {
            $socialaccountdata->save();
        }
    }

    /**
     * @param Socialaccount $socialaccount
     */
    private function updateExisting(Socialaccount $socialaccount)
    {
        $socialaccountdata = Socialaccountdata::find($socialaccount->id);
        $socialaccountdata->save();
        $socialaccount->save();

        // Store the current user in local reference

        $this->socialaccount = $socialaccount;
    }

}