<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * tasks.calendar_id is an opaque external Google/Outlook calendar event
     * id (see GoogleCalendarController), not a relation to a local table —
     * no `calendars` table has ever existed. The original migration declared
     * it with `->constrained()` anyway, which by convention points at a
     * `calendars` table that doesn't exist, breaking every task insert and
     * delete (SQLite validates the FK target even for this) once foreign
     * key enforcement is on.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['calendar_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('calendar_id')->references('id')->on('calendars')->onDelete('cascade');
        });
    }
};
