<?php

namespace App\Http\Controllers;

use App\Models\DashboardCard;
use App\Models\Setting;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $query = DashboardCard::query()->where('active', true);

        $user = request()->user();
        if ($user) {
            if ($user->hasRole(['admin', 'mager'])) {
                // все активные
            } else {
                $aud = [];
                if ($user->hasRole('support')) { $aud[] = 'support'; }
                if ($user->hasRole('technical')) { $aud[] = 'technical'; }
                if (empty($aud)) { $aud[] = 'all'; }
                $query->whereIn('audience', $aud);
            }
        } else {
            // гость — только для всех
            $query->where('audience', 'all');
        }

        $cards = $query->latest('created_at')->take(12)->get();

        $settings = Setting::query()->first();

        return Inertia::render('Dashboard', [
            'cards' => $cards,
            'settings' => $settings,
        ]);
    }
}
