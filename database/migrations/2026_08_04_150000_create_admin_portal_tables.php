<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('employers')) {
            Schema::create('employers', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('industry')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('website_url')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('verification_status')->default('pending');
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->json('notes')->nullable();
            $table->timestamps();
            });
        }

        if (! Schema::hasTable('candidates')) {
            Schema::create('candidates', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('headline')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('current_job_title')->nullable();
            $table->string('preferred_job_category')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('verification_status')->default('unverified');
            $table->string('availability_status')->default('open_to_work');
            $table->unsignedTinyInteger('trust_score')->default(0);
            $table->json('skills')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_categories')) {
            Schema::create('job_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('group')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            });
        }

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

        if (! Schema::hasTable('job_applications')) {
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

        if (! Schema::hasTable('trust_reports')) {
            Schema::create('trust_reports', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->foreignId('reporter_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject_type');
            $table->string('subject_reference')->nullable();
            $table->string('reason');
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->text('description')->nullable();
            $table->text('resolution_notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            });
        }

        if (! Schema::hasTable('media_assets')) {
            Schema::create('media_assets', function (Blueprint $table): void {
            $table->id();
            $table->ulid('public_id')->unique();
            $table->string('name');
            $table->string('collection')->default('general');
            $table->string('disk')->default('public');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('alt_text')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            });
        }

        if (! Schema::hasTable('homepage_sections')) {
            Schema::create('homepage_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('eyebrow')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->json('content')->nullable();
            $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('trust_reports');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_categories');
        Schema::dropIfExists('candidates');
        Schema::dropIfExists('employers');
    }
};
