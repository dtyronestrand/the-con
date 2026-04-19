<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;

class GoogleCalendarController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/calendar.readonly'])
            ->with(['access_type' => 'offline', 'prompt' => 'consent'])
            ->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            session(['google_token' => $user->token]);
            if ($user->refreshToken) {
                session(['google_refresh_token' => $user->refreshToken]);
            }
        } catch (\Exception $e) {
            // handle error
        }

        return redirect()->route('home');
    }

    public function syncEventsAsTasks(Request $request)
    {
        $isConnected = session()->has('google_token');
        if (! $isConnected) {
            return response()->json(['success' => false, 'message' => 'Not connected to Google Calendar']);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (! $startDate || ! $endDate) {
            return response()->json(['success' => false, 'message' => 'Start date and end date are required']);
        }

        $token = session('google_token');

        $startDateTime = Carbon::parse($startDate)->startOfDay()->toRfc3339String();
        $endDateTime = Carbon::parse($endDate)->endOfDay()->toRfc3339String();

        $response = Http::withToken($token)
            ->get('https://www.googleapis.com/calendar/v3/calendars/primary/events', [
                'timeMin' => $startDateTime,
                'timeMax' => $endDateTime,
                'singleEvents' => 'true',
                'orderBy' => 'startTime',
            ]);

        if ($response->successful()) {
            $events = $response->json()['items'] ?? [];

            foreach ($events as $event) {
                // Determine start date
                $eventStart = $event['start']['dateTime'] ?? $event['start']['date'] ?? null;
                if (! $eventStart) {
                    continue;
                }

                $dueDate = Carbon::parse($eventStart)->format('Y-m-d');
                $name = $event['summary'] ?? 'Untitled Event';
                $notes = $event['description'] ?? null;
                $calendarId = $event['id']; // We will map the event ID to the task calendar_id

                Task::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'calendar_id' => $calendarId,
                    ],
                    [
                        'name' => $name,
                        'notes' => $notes,
                        'due_date' => $dueDate,
                    ]
                );
            }

            return response()->json(['success' => true, 'count' => count($events)]);
        }

        if ($response->status() === 401) {
            session()->forget('google_token');

            return response()->json(['success' => false, 'message' => 'Token expired, please reconnect']);
        }

        return response()->json(['success' => false, 'message' => 'Failed to sync from Google API']);
    }
}
