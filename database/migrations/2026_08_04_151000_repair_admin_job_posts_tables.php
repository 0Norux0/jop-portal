<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_posts')) {
            Schema::create('job_posts', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('employer_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('job_category_id')->nullable()->constrained()->nullOnDelete();
                $table->string('title');
                $table->string('slug')->unique();
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('work_mode')->default('on_site');
                $table->string('employment_type')->default('full_time');
                $table->string('currency', 8)->nullable();
                $table->unsignedInteger('salary_min')->nullable();
                $table->unsignedInteger('salary_max')->nullable();
                $table->unsignedInteger('vacancies')->default(1);
                $table->date('application_deadline')->nullable();
                $table->string('status')->default('draft');
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_urgent')->default(false);
                $table->boolean('visa_sponsorship')->default(false);
                $table->boolean('relocation_support')->default(false);
                $table->text('description')->nullable();
                $table->json('responsibilities')->nullable();
                $table->json('skills')->nullable();
                $table->json('requirements')->nullable();
                $table->json('benefits')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('job_applications')) {
            Schema::drop('job_applications');
        }

        Schema::create('job_applications', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('job_id')->constrained('job_posts')->cascadeOnDelete();
            $table->foreignId('candidate_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('candidate_name');
            $table->string('candidate_email');
            $table->string('method')->default('portal_profile');
            $table->string('status')->default('submitted');
            $table->string('linkedin_url')->nullable();
            $table->string('cv_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_posts');
    }
};
