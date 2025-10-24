<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'bg_effect')) {
                $table->string('bg_effect', 16)->default('image'); // image|matrix
            }
            if (!Schema::hasColumn('settings', 'matrix_fg_color')) {
                $table->string('matrix_fg_color', 16)->default('#00ff00');
            }
            if (!Schema::hasColumn('settings', 'matrix_bg_color')) {
                $table->string('matrix_bg_color', 16)->default('#000000');
            }
            if (!Schema::hasColumn('settings', 'matrix_opacity')) {
                $table->decimal('matrix_opacity', 3, 2)->default(0.85);
            }
            if (!Schema::hasColumn('settings', 'matrix_speed')) {
                $table->decimal('matrix_speed', 3, 2)->default(1.00); // multiplier
            }
            if (!Schema::hasColumn('settings', 'matrix_density')) {
                $table->unsignedSmallInteger('matrix_density')->default(20); // columns per ~1000px
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            foreach (['bg_effect','matrix_fg_color','matrix_bg_color','matrix_opacity','matrix_speed','matrix_density'] as $c) {
                if (Schema::hasColumn('settings', $c)) {
                    $table->dropColumn($c);
                }
            }
        });
    }
};

