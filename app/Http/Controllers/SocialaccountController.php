<?php namespace App\Http\Controllers;

use App\Model\Socialaccount\Socialaccountdata;
use Illuminate\Support\Facades\Auth;

class SocialaccountController extends Controller
{

    public function showProfile()
    {
        $socialaccountdata = Socialaccountdata::find(Auth::id());

        return view('socialaccount/profile', [
            'user' => $socialaccountdata
        ]);
    }
}