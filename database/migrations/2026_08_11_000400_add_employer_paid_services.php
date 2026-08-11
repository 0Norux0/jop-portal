<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employers', function (Blueprint $table): void {
            foreach ([
                'job_post_limit' => 2,
                'featured_job_credits' => 0,
                'candidate_search_credits' => 10,
                'cv_access_credits' => 1,
                'candidate_contact_credits' => 1,
                'matching_request_credits' => 0,
                'ai_recruitment_credits' => 0,
            ] as $column => $default) {
                if (! Schema::hasColumn('employers', $column)) {
                    $table->unsignedInteger($column)->default($default)->after('premium_status');
                }
            }
        });

        if (! Schema::hasTable('employer_service_requests')) {
            Schema::create('employer_service_requests', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('job_id')->nullable()->constrained('job_posts')->nullOnDelete();
                $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type');
                $table->string('title');
                $table->string('status')->default('requested');
                $table->decimal('budget', 10, 2)->nullable();
                $table->json('payload')->nullable();
                $table->text('notes')->nullable();
                $table->timestamp('reviewed_at')->nullable();
                $table->timestamps();
                $table->index(['employer_id', 'type', 'status']);
            });
        }

        if (! Schema::hasTable('employer_credit_transactions')) {
            Schema::create('employer_credit_transactions', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('employer_id')->constrained()->cascadeOnDelete();
                $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('job_id')->nullable()->constrained('job_posts')->nullOnDelete();
                $table->string('credit_type');
                $table->integer('amount');
                $table->string('description');
                $table->json('metadata')->nullable();
                $table->timestamps();
                $table->index(['employer_id', 'credit_type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employer_credit_transactions');
        Schema::dropIfExists('employer_service_requests');

        Schema::table('employers', function (Blueprint $table): void {
            foreach ([
                'ai_recruitment_credits',
                'matching_request_credits',
                'candidate_contact_credits',
                'cv_access_credits',
                'candidate_search_credits',
                'featured_job_credits',
                'job_post_limit',
            ] as $column) {
                if (Schema::hasColumn('employers', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
