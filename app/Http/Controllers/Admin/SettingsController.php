<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\DashboardPing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $pings = DashboardPing::orderBy('sort_order')->get();
        return Inertia::render('Admin/Settings', [
            'settings' => $settings,
            'pings' => $pings,
        ]);
    }

    public function update(Request $request)
    {
        // Normalize decimal inputs: replace comma with dot
        foreach (['topbar_bg_opacity','matrix_opacity','matrix_speed'] as $k) {
            if ($request->has($k)) {
                $v = str_replace(',', '.', (string) $request->input($k));
                $request->merge([$k => $v]);
            }
        }

        $data = $request->validate([
            'dashboard_logo' => ['nullable', 'image', 'max:2048'],
            'dashboard_bg' => ['nullable', 'image', 'max:4096'],
            'topbar_bg_color' => ['nullable', 'string', 'max:32'],
            'topbar_bg_opacity' => ['nullable', 'numeric', 'between:0,1'],
            'topbar_clock_color' => ['nullable', 'string', 'max:32'],
            'topbar_ping_color' => ['nullable', 'string', 'max:32'],
            'bg_effect' => ['nullable', 'in:image,matrix'],
            'matrix_fg_color' => ['nullable','string','max:16'],
            'matrix_bg_color' => ['nullable','string','max:16'],
            'matrix_opacity' => ['nullable','numeric','between:0,1'],
            'matrix_speed' => ['nullable','numeric','between:0.1,5'],
            'matrix_density' => ['nullable','integer','min:5','max:80'],
            'card_bg_color' => ['nullable','string','max:16'],
            'card_bg_opacity' => ['nullable','numeric','between:0,1'],
        ]);

        $settings = Setting::firstOrCreate([]);

        if ($request->hasFile('dashboard_logo')) {
            if ($settings->dashboard_logo_path) {
                Storage::disk('public')->delete($settings->dashboard_logo_path);
            }
            $settings->dashboard_logo_path = $request->file('dashboard_logo')->store('settings', 'public');
        }

        if ($request->hasFile('dashboard_bg')) {
            if ($settings->dashboard_bg_path) {
                Storage::disk('public')->delete($settings->dashboard_bg_path);
            }
            $settings->dashboard_bg_path = $request->file('dashboard_bg')->store('settings', 'public');
        }

        // scalar settings (with clamping)
        foreach ([
            'topbar_bg_color','topbar_bg_opacity','topbar_clock_color','topbar_ping_color',
            'bg_effect','matrix_fg_color','matrix_bg_color','matrix_opacity','matrix_speed','matrix_density',
            'card_bg_color','card_bg_opacity'
        ] as $key) {
            if ($request->has($key)) {
                $val = $request->input($key);
                if (in_array($key, ['topbar_bg_opacity','matrix_opacity'], true)) {
                    $val = max(0, min(1, (float) $val));
                }
                if ($key === 'matrix_speed') {
                    $val = max(0.1, min(5, (float) $val));
                }
                if ($key === 'matrix_density') {
                    $val = max(5, min(80, (int) $val));
                }
                $settings->{$key} = $val;
            }
        }

        $settings->save();

        return redirect()->route('admin.settings.index')->with('success', '?????????????????? ??????????????????');
    }

    public function storePing(Request $request)
    {
        $count = DashboardPing::count();
        abort_if($count >= 5, 422, '?????????? ?????????????? ???? ?????????? 5 ????????????');

        $data = $request->validate([
            'label' => ['required','string','max:50'],
            'host' => ['required','string','max:255'],
            'sort_order' => ['nullable','integer','min:1','max:999'],
            'active' => ['boolean'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? ($count + 1);

        DashboardPing::create($data);
        return redirect()->route('admin.settings.index')->with('success','???????? ????????????????');
    }

    public function updatePing(Request $request, DashboardPing $ping)
    {
        $data = $request->validate([
            'label' => ['required','string','max:50'],
            'host' => ['required','string','max:255'],
            'sort_order' => ['nullable','integer','min:1','max:999'],
            'active' => ['boolean'],
        ]);
        $ping->update($data);
        return redirect()->route('admin.settings.index')->with('success','???????? ????????????????');
    }

    public function destroyPing(DashboardPing $ping)
    {
        $ping->delete();
        return redirect()->route('admin.settings.index')->with('success','???????? ????????????');
    }
}




