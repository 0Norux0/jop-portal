<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            if (! Schema::hasColumn('candidates', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('public_id');
            }

            if (! Schema::hasColumn('candidates', 'video_path')) {
                $table->string('video_path')->nullable()->after('cv_path');
            }

            if (! Schema::hasColumn('candidates', 'expected_salary')) {
                $table->string('expected_salary')->nullable()->after('availability_status');
            }

            if (! Schema::hasColumn('candidates', 'notice_period')) {
                $table->string('notice_period')->nullable()->after('expected_salary');
            }

            if (! Schema::hasColumn('candidates', 'is_public')) {
                $table->boolean('is_public')->default(false)->after('notice_period');
            }

            if (! Schema::hasColumn('candidates', 'external_links')) {
                $table->json('external_links')->nullable()->after('skills');
            }
        });

        if (! Schema::hasTable('candidate_portfolio_items')) {
            Schema::create('candidate_portfolio_items', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('type')->default('link');
                $table->string('url')->nullable();
                $table->string('file_path')->nullable();
                $table->text('description')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('candidate_projects')) {
            Schema::create('candidate_projects', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
                $table->string('title');
                $table->string('role')->nullable();
                $table->string('url')->nullable();
                $table->text('description')->nullable();
                $table->json('skills')->nullable();
                $table->date('started_on')->nullable();
                $table->date('ended_on')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('candidate_certificates')) {
            Schema::create('candidate_certificates', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('issuer')->nullable();
                $table->string('credential_number')->nullable();
                $table->string('credential_url')->nullable();
                $table->string('file_path')->nullable();
                $table->date('issued_on')->nullable();
                $table->date('expires_on')->nullable();
                $table->string('verification_status')->default('uploaded');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('candidate_educations')) {
            Schema::create('candidate_educations', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
                $table->string('school');
                $table->string('degree')->nullable();
                $table->string('field')->nullable();
                $table->date('started_on')->nullable();
                $table->date('ended_on')->nullable();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('candidate_experiences')) {
            Schema::create('candidate_experiences', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('candidate_id')->constrained()->cascadeOnDelete();
                $table->string('company');
                $table->string('title');
                $table->string('location')->nullable();
                $table->date('started_on')->nullable();
                $table->date('ended_on')->nullable();
                $table->boolean('is_current')->default(false);
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('job_alerts')) {
            Schema::create('job_alerts', function (Blueprint $table): void {
                $table->id();
                $table->ulid('public_id')->unique();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('keyword')->nullable();
                $table->string('country')->nullable();
                $table->string('category')->nullable();
                $table->string('frequency')->default('weekly');
                $table->boolean('is_active')->default(true);
                $table->timestamp('last_sent_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_alerts');
        Schema::dropIfExists('candidate_experiences');
        Schema::dropIfExists('candidate_educations');
        Schema::dropIfExists('candidate_certificates');
        Schema::dropIfExists('candidate_projects');
        Schema::dropIfExists('candidate_portfolio_items');

        Schema::table('candidates', function (Blueprint $table): void {
            foreach (['external_links', 'is_public', 'notice_period', 'expected_salary', 'video_path', 'slug'] as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
