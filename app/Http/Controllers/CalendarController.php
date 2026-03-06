<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Inertia\Inertia;

class CalendarController extends Controller
{
   public function redirectToProvider()
   {
    return Socialite::driver('graph')
        ->scopes(['Calendars.Read'])
        ->redirect();
   }

   public function handleProviderCallback()
   {
    $user = Socialite::driver('graph')->user();
    session(['outlook_token' => $user->token]);
    session(['outlook_refresh_token' => $user->refreshToken]);

    return redirect()->route('home');
   }

   public function index()
   {
    $events = [];
    $isConnected = session()->has('outlook_token');

    if($isConnected) {
        $token = session('outlook_token');

        $startDateTime = Carbon::now()->toIso8601String();
        $endDateTime = Carbon::now()->addDays(3)->toIso8601String();

        $response = Http::withToken($token)
            ->get('https://graph.microsoft.com/v1.0/me/calendarview', [
                'startDateTime' => $startDateTime,
                'endDateTime' => $endDateTime,
                '$select' => 'subject,organizer,start,end',
                '$orderby' => 'start/dateTime',
            ]);

            if($response->successful()) {
                $events = $response->json()['value'];
            } else {
                // Handle error, possibly refresh token or notify user
                $events = [];
            }

            return Inertia::render('Dashboard', [
                'isConnected' => $isConnected,
                'events' => $events,
            ]);
        }
        
        return Inertia::render('Dashboard', [
            'isConnected' => $isConnected,
            'events' => $events,
        ]);
   }
}
