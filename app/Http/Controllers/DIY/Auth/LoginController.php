<?php
namespace App\Http\Controllers\DIY\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Laramin\Utility\Onumoti;

class LoginController extends Controller
{
   public function show()
    {
        $pageTitle = "DIY Login";

        return view('diy.auth.login', compact('pageTitle'));
    }
    
    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        $specialUsername = config('diy.special_user.username');
        $specialPassword = config('diy.special_user.password');

        if ($username === $specialUsername && $password === $specialPassword) {
            Session::put('loggedIn', true);

            return redirect('/do-it-yourself/orders/open');
        } else {
            return redirect()->back()->with('error', 'Invalid username or password');
        }
    }
    
    public function logout()
    {
        // Session::pull('key', 'loggedIn');
        Session::put('loggedIn', false);
    
        return redirect('/do-it-yourself/login');
    }
}
