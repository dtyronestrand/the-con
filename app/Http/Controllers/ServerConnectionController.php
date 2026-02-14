<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AppSetting;

class ServerConnectionController extends Controller
{
    public function connect(Request $request)
    {
        // 1. Send credentials to the REMOTE server
        $apiUrl = rtrim(config('app.api_url', 'http://localhost'), '/');
        $response = Http::post($apiUrl . '/api/login', [
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // 2. If successful, save the token to our custom settings table
        if ($response->successful()) {
            $token = $response->json('token');

            AppSetting::updateOrCreate(
                ['key' => 'api_token'],
                ['value' => $token]
            );

            return redirect()->back()->with('status', 'Successfully connected to server!');
        }

        // 3. If failed
        return back()->withErrors(['email' => 'Could not connect to server. Check credentials.']);
    }
}