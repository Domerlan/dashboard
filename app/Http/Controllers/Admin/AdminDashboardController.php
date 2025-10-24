<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DashboardCard;
use App\Models\User;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'cards' => DashboardCard::count(),
                'users' => User::count(),
                'pending_users' => User::where('is_approved', false)->count(),
            ],
        ]);
    }
}

