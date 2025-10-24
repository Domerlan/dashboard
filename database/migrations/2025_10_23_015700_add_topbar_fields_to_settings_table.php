<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'topbar_bg_color')) {
                $table->string('topbar_bg_color', 32)->default('#111827');
            }
            if (!Schema::hasColumn('settings', 'topbar_bg_opacity')) {
                $table->decimal('topbar_bg_opacity', 3, 2)->default(0.60);
            }
            if (!Schema::hasColumn('settings', 'topbar_clock_color')) {
                $table->string('topbar_clock_color', 32)->default('#ffffff');
            }
            if (!Schema::hasColumn('settings', 'topbar_ping_color')) {
                $table->string('topbar_ping_color', 32)->default('#ffffff');
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'topbar_bg_color')) {
                $table->dropColumn('topbar_bg_color');
            }
            if (Schema::hasColumn('settings', 'topbar_bg_opacity')) {
                $table->dropColumn('topbar_bg_opacity');
            }
            if (Schema::hasColumn('settings', 'topbar_clock_color')) {
                $table->dropColumn('topbar_clock_color');
            }
            if (Schema::hasColumn('settings', 'topbar_ping_color')) {
                $table->dropColumn('topbar_ping_color');
            }
        });
    }
};

