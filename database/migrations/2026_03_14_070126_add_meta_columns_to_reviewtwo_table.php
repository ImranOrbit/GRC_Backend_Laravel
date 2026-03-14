<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviewtwo', function (Blueprint $table) {
            if (!Schema::hasColumn('reviewtwo', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('review_text');
            }
            if (!Schema::hasColumn('reviewtwo', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviewtwo', function (Blueprint $table) {
            $table->dropColumn(['meta_title', 'meta_description']);
        });
    }
};