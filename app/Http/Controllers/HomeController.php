<?php
/**
 * Created by PhpStorm.
 * User: erosol
 * Date: 18.03.17
 * Time: 00:06
 */

namespace App\Http\Controllers;


class HomeController extends Controller
{

    public function show()
    {
        return view('welcome');
    }
}