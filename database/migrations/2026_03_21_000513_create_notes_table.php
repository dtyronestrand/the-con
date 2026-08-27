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
        Schema::create('notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            $table->text('content')->nullable();
            $table->string('color')->default('#FFFF88');
            $table->string('width')->default('200px');
            $table->string('height')->default('200px');
             $table->string('title')->nullable();
            $table->json('tags')->nullable()->default('[]');
            $table->boolean('pinned')->default(false);
            $table->boolean('archived')->default(false);
            $table->json('demoted_tasks')->nullable()->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sticky_notes');
    }
};
