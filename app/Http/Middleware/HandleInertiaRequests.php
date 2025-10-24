<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $settings = Setting::first();

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'roles' => fn () => $request->user()?->getRoleNames(),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'settings' => $settings ? [
                'dashboard_logo_url' => $settings->dashboard_logo_url,
                'dashboard_bg_url' => $settings->dashboard_bg_url,
                'bg_effect' => $settings->bg_effect,
                'matrix_fg_color' => $settings->matrix_fg_color,
                'matrix_bg_color' => $settings->matrix_bg_color,
                'matrix_opacity' => $settings->matrix_opacity,
                'matrix_speed' => $settings->matrix_speed,
                'matrix_density' => $settings->matrix_density,
                'topbar_bg_color' => $settings->topbar_bg_color,
                'topbar_bg_opacity' => $settings->topbar_bg_opacity,
                'topbar_clock_color' => $settings->topbar_clock_color,
                'topbar_ping_color' => $settings->topbar_ping_color,
                'card_bg_color' => $settings->card_bg_color,
                'card_bg_opacity' => $settings->card_bg_opacity,
            ] : null,
        ];
    }
}

