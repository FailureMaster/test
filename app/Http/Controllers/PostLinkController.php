<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PostLinkController extends Controller
{
    public function index()
    {
        return view('postlink.index');
    }

    public function store(Request $request)
    {
      
        User::create($request->validate([
            'firstname' => 'required|alpha|',
            'lastname' => 'required|alpha|',
            'email' => 'required|alpha|',
            'mobile' => 'required|alpha|',
            'country_code' => 'required|alpha|',
            'account_type' => 'required|alpha|',
            'lead_source' => 'required|alpha|',
        ]));
      

        // Process the data
        // ...

        // return redirect()->route('post-link')->with('success', 'Form submitted successfully!');
    }
}
