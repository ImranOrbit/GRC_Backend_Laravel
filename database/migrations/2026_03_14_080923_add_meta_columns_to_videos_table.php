<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'title')) {
                $table->string('title')->nullable()->after('url');
            }
            if (!Schema::hasColumn('videos', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('title');
            }
            if (!Schema::hasColumn('videos', 'tags')) {
                $table->json('tags')->nullable()->after('thumbnail');
            }
            if (!Schema::hasColumn('videos', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('tags');
            }
            if (!Schema::hasColumn('videos', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['title', 'thumbnail', 'tags', 'meta_title', 'meta_description']);
        });
    }
};