<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('zoom_room_questions', function (Blueprint $table) {
            $table->timestamp('seen_at')->nullable()->after('asked_at')->index();
        });
    }

    public function down(): void
    {
        Schema::table('zoom_room_questions', function (Blueprint $table) {
            $table->dropColumn('seen_at');
        });
    }
};
