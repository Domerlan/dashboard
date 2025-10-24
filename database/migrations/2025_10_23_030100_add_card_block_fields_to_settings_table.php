<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'card_bg_color')) {
                $table->string('card_bg_color', 16)->default('#ffffff');
            }
            if (!Schema::hasColumn('settings', 'card_bg_opacity')) {
                $table->decimal('card_bg_opacity', 3, 2)->default(0.90);
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'card_bg_color')) {
                $table->dropColumn('card_bg_color');
            }
            if (Schema::hasColumn('settings', 'card_bg_opacity')) {
                $table->dropColumn('card_bg_opacity');
            }
        });
    }
};
