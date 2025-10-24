<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = ['admin', 'mager', 'support', 'technical'];
        foreach ($roles as $r) {
            Role::firstOrCreate(['name' => $r]);
        }

        // Remove deprecated 'user' role and sample account if exist
        if ($old = Role::where('name', 'user')->first()) {
            $old->delete();
        }
        User::where('email', 'user@example.com')->delete();

        // Create users
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'is_approved' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        $manager = User::firstOrCreate(
            ['email' => 'mager@example.com'],
            [
                'name' => 'Mager',
                'password' => Hash::make('password'),
                'is_approved' => true,
            ]
        );
        $manager->syncRoles(['mager']);

        // user роль и аккаунт убраны по бизнес-требованию

        $support = User::firstOrCreate(
            ['email' => 'support@example.com'],
            [
                'name' => 'Support',
                'password' => Hash::make('password'),
                'is_approved' => true,
            ]
        );
        $support->syncRoles(['support']);

        $technical = User::firstOrCreate(
            ['email' => 'technical@example.com'],
            [
                'name' => 'Technical',
                'password' => Hash::make('password'),
                'is_approved' => true,
            ]
        );
        $technical->syncRoles(['technical']);
    }
}
