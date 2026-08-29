<?php

namespace App\Http\Controllers;

use App\Services\RemoteAuthService;
use Illuminate\Http\Request;

class ServerConnectionController extends Controller
{
    public function connect(Request $request, RemoteAuthService $auth)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($auth->login($validated['email'], $validated['password'])) {
            return redirect()->back()->with('status', 'Successfully connected to server!');
        }

        return back()->withErrors(['email' => 'Could not connect to server. Check credentials.']);
    }
}
