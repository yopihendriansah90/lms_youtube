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
        Schema::table('zoom_records', function (Blueprint $table) {
            $table->string('youtube_video_id', 32)->nullable()->after('youtube_url')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zoom_records', function (Blueprint $table) {
            $table->dropColumn('youtube_video_id');
        });
    }
};
