<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RemoteAuthService;

class ServerConnectionController extends Controller
{
    public function connect(Request $request, RemoteAuthService $auth)
    {
        if ($auth->login($request->email, $request->password)) {
            return redirect()->back()->with('status', 'Successfully connected to server!');
        }

        return back()->withErrors(['email' => 'Could not connect to server. Check credentials.']);
    }
}