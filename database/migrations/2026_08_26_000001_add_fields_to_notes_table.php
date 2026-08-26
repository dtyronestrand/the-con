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
        Schema::table('notes', function (Blueprint $table) {
            $table->string('title')->nullable()->after('user_id');
            $table->json('tags')->nullable()->default('[]')->after('color');
            $table->boolean('pinned')->default(false)->after('tags');
            $table->boolean('archived')->default(false)->after('pinned');
            $table->json('demoted_tasks')->nullable()->default('[]')->after('archived');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            $table->dropColumn(['title', 'tags', 'pinned', 'archived', 'demoted_tasks']);
        });
    }
};
