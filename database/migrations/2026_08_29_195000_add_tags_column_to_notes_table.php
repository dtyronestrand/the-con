<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Note model has always declared `tags` as fillable/cast, and
     * NoteController/the note editor UI both read and write it — but the
     * original notes migration never actually created the column, so saving
     * a note's tags has been failing with "no such column: tags" all along.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('notes', 'tags')) {
            Schema::table('notes', function (Blueprint $table) {
                $table->json('tags')->nullable()->default('[]')->after('title');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('notes', 'tags')) {
            Schema::table('notes', function (Blueprint $table) {
                $table->dropColumn('tags');
            });
        }
    }
};
