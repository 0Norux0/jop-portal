<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('saved_jobs')) {
            Schema::create('saved_jobs', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('job_id')->constrained('job_posts')->cascadeOnDelete();
                $table->timestamp('saved_at')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'job_id']);
            });
        }

        if (! Schema::hasTable('conversation_messages')) {
            Schema::create('conversation_messages', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('candidate_user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('job_application_id')->nullable()->constrained('job_applications')->nullOnDelete();
                $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
                $table->text('body');
                $table->timestamp('read_at')->nullable();
                $table->timestamps();

                $table->index(['candidate_user_id', 'created_at']);
                $table->index(['employer_id', 'created_at']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
        Schema::dropIfExists('saved_jobs');
    }
};
