<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->latest('id')->paginate(20)->through(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'is_approved' => $u->is_approved,
                'roles' => $u->getRoleNames(),
            ];
        });

        $allRoles = ['admin', 'mager', 'support', 'technical'];

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'roles' => $allRoles,
        ]);
    }

    public function approve(User $user)
    {
        $user->is_approved = true;
        $user->save();
        return back()->with('success', 'Пользователь подтвержден');
    }

    public function revoke(User $user)
    {
        $user->is_approved = false;
        $user->save();
        return back()->with('success', 'Подтверждение снято');
    }

    public function setRoles(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['array'],
            'roles.*' => ['string'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return back()->with('success', 'Роли обновлены');
    }
}
