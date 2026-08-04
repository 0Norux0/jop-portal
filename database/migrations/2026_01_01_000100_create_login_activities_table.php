<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('login_activities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('successful');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            // Coarse failure category only — never raw submitted data.
            $table->string('failure_reason', 64)->nullable();
            $table->timestamp('logged_in_at')->nullable();
            $table->timestamp('logged_out_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('created_at'); // supports retention pruning
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login_activities');
    }
};
