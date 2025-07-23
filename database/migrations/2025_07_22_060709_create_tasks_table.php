<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('urgency')->default(1); // 1-5 scale
            $table->integer('impact')->default(1); // 1-5 scale  
            $table->integer('effort')->default(1); // 1-5 scale
            $table->decimal('priority_score', 8, 2)->default(0);
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('created_by')->nullable('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['status', 'due_date']);
            $table->index('priority_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};