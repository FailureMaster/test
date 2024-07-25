<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'filepond' => 'required|mimes:csv,txt'
        ]);

        $path = $request->file('filepond')->getRealPath();
        $data = array_map('str_getcsv', file($path));

        foreach ($data as $row) {
            User::create([
                'firstname'         => $row[0],
                'lastname'          => $row[1],
                'email'             => $row[2],
                'mobile'            => $row[3],
                'country_code'      => $row[4],
                'account_type'      => $row[5],
            ]);
        }

    
    }

}
