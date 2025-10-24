<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('dashboard_logo_path')->nullable();
            $table->string('dashboard_bg_path')->nullable();
            // Top bar customization
            $table->string('topbar_bg_color')->default('#111827'); // Tailwind gray-900
            $table->decimal('topbar_bg_opacity', 3, 2)->default(0.60); // 0.00..1.00
            $table->string('topbar_clock_color')->default('#ffffff');
            $table->string('topbar_ping_color')->default('#ffffff');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
