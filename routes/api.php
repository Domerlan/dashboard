<?php

use Illuminate\Support\Facades\Route;
use App\Models\DashboardCard;
use App\Http\Controllers\Api\PingController;

// Public API: active dashboard cards
Route::get('/cards', function () {
    $cards = DashboardCard::where('active', true)
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($card) {
            return [
                'id' => $card->id,
                'title' => $card->title,
                'description' => $card->description,
                'link' => $card->link,
                'image_url' => $card->image_url,
                'audience' => $card->audience,
                'created_at' => $card->created_at,
            ];
        });

    return response()->json($cards);
});

Route::get('/pings', [PingController::class, 'index']);
