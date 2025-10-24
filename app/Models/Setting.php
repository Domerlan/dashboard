<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'dashboard_logo_path',
        'dashboard_bg_path',
        'topbar_bg_color',
        'topbar_bg_opacity',
        'topbar_clock_color',
        'topbar_ping_color',
        'bg_effect',
        'matrix_fg_color',
        'matrix_bg_color',
        'matrix_opacity',
        'matrix_speed',
        'matrix_density',
        'card_bg_color',
        'card_bg_opacity',
    ];

    protected $appends = [
        'dashboard_logo_url',
        'dashboard_bg_url',
    ];

    public function getDashboardLogoUrlAttribute(): ?string
    {
        return $this->dashboard_logo_path ? Storage::disk('public')->url($this->dashboard_logo_path) : null;
    }

    public function getDashboardBgUrlAttribute(): ?string
    {
        return $this->dashboard_bg_path ? Storage::disk('public')->url($this->dashboard_bg_path) : null;
    }
}



