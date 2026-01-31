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
            $table->id();
            $table->nullableMorphs('noteable');
            $table->text('content');
            $table->enum('type', ['note', 'reminder', 'warning'])->default('note');
            $table->boolean('is_important')->default(false);
            $table->timestamp('reminder_date')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            
            $table->index(['is_completed', 'reminder_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
