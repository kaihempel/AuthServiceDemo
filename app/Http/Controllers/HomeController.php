<?php namespace App\Http\Controllers;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{

    public function show()
    {
        return view('home/welcome');
    }
}