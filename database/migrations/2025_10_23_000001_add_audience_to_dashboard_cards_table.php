<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->string('audience', 32)->default('all')->after('active');
            $table->index(['active', 'audience']);
        });
    }

    public function down(): void
    {
        Schema::table('dashboard_cards', function (Blueprint $table) {
            $table->dropIndex(['active', 'audience']);
            $table->dropColumn('audience');
        });
    }
};

