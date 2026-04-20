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
        Schema::table('material_payments', function (Blueprint $table) {
            $table->string('payment_target_type', 30)->default('material')->after('user_id')->index();
            $table->foreignId('zoom_record_id')->nullable()->after('material_id')->constrained()->cascadeOnDelete();
        });

        Schema::table('material_payments', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable()->change();
            $table->index(['user_id', 'zoom_record_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_payments', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'zoom_record_id']);
            $table->dropConstrainedForeignId('zoom_record_id');
            $table->dropColumn('payment_target_type');
        });

        Schema::table('material_payments', function (Blueprint $table) {
            $table->foreignId('material_id')->nullable(false)->change();
        });
    }
};
