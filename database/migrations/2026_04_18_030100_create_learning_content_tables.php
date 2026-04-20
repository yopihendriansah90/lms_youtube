<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('status', 30)->default('draft')->index();
            $table->string('visibility', 30)->default('private')->index();
            $table->string('access_type', 30)->default('free')->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('IDR');
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('material_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('material_sections')->nullOnDelete();
            $table->string('title');
            $table->string('youtube_url');
            $table->string('youtube_video_id', 32)->index();
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_in_seconds')->nullable();
            $table->string('access_type', 30)->default('free')->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('pdf_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('access_type', 30)->default('free')->index();
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('material_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content')->nullable();
            $table->string('update_type', 30)->default('info')->index();
            $table->boolean('is_published')->default(false)->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('zoom_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('mentor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('zoom_recording_url')->nullable();
            $table->string('youtube_url')->nullable();
            $table->string('youtube_video_id', 32)->nullable()->index();
            $table->string('thumbnail')->nullable();
            $table->timestamp('recorded_at')->nullable()->index();
            $table->string('access_type', 30)->default('free')->index();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_published')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zoom_records');
        Schema::dropIfExists('material_updates');
        Schema::dropIfExists('pdf_documents');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('material_sections');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('programs');
    }
};
