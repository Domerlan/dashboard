<?php

namespace App\Http\Controllers;

use App\Models\DashboardCard;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CardController extends Controller
{
    public function show(Request $request, DashboardCard $card)
    {
        if (!$card->active) {
            abort(404);
        }

        $user = $request->user();
        $allowed = false;
        if ($user) {
            if ($user->hasRole(['admin','mager'])) {
                $allowed = true;
            } else {
                if ($card->audience === 'all') $allowed = true;
                if ($card->audience === 'support' && $user->hasRole('support')) $allowed = true;
                if ($card->audience === 'technical' && $user->hasRole('technical')) $allowed = true;
            }
        }
        if (!$allowed) abort(403);

        $settings = Setting::first();

        return Inertia::render('Cards/Show', [
            'card' => [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                'link' => $card->link,
                'image_url' => $card->image_url,
            ],
            'settings' => $settings,
        ]);
    }
}

