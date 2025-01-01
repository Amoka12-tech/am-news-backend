<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Preference;
use Illuminate\Support\Facades\Log;

class UserPreferenceController extends Controller
{
    public function savePreferences(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'sources' => 'nullable|array',
            'authors' => 'nullable|array',
        ]);

        $user = $request->user();

        $preference = Preference::updateOrCreate(
            ['user_id' => $user->id],
            [
                'categories' => $request->categories,
                'sources' => $request->sources,
                'authors' => $request->authors,
            ]
        );

        return response()->json(['message' => 'Preferences saved successfully.', 'preferences' => $preference]);
    }

    public function getPreferences(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Log::info('Authenticated User:', ['user' => $user]);

        $preferences = $user->preferences()->get();


        if (!$preferences) {
            return response()->json(['message' => 'No preferences found.'], 404);
        }

        return response()->json(['preferences' => $preferences]);
    }
}

