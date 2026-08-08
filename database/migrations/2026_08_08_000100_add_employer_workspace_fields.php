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
            if (! Schema::hasColumn('employers', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('public_id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('employers', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('website_url');
            }

            if (! Schema::hasColumn('employers', 'cover_path')) {
                $table->string('cover_path')->nullable()->after('logo_path');
            }

            if (! Schema::hasColumn('employers', 'company_size')) {
                $table->string('company_size')->nullable()->after('industry');
            }

            if (! Schema::hasColumn('employers', 'social_links')) {
                $table->json('social_links')->nullable()->after('notes');
            }

            if (! Schema::hasColumn('employers', 'is_published')) {
                $table->boolean('is_published')->default(false)->after('status');
            }

            if (! Schema::hasColumn('employers', 'billing_email')) {
                $table->string('billing_email')->nullable()->after('contact_phone');
            }

            if (! Schema::hasColumn('employers', 'billing_plan')) {
                $table->string('billing_plan')->default('free')->after('billing_email');
            }

            if (! Schema::hasColumn('employers', 'premium_status')) {
                $table->string('premium_status')->default('not_upgraded')->after('billing_plan');
            }

            if (! Schema::hasColumn('employers', 'advertising_enabled')) {
                $table->boolean('advertising_enabled')->default(false)->after('premium_status');
            }

            if (! Schema::hasColumn('employers', 'learning_enabled')) {
                $table->boolean('learning_enabled')->default(false)->after('advertising_enabled');
            }
        });

        Schema::table('job_posts', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_posts', 'applicant_questions')) {
                $table->json('applicant_questions')->nullable()->after('benefits');
            }

            if (! Schema::hasColumn('job_posts', 'promotion_status')) {
                $table->string('promotion_status')->default('not_promoted')->after('is_urgent');
            }

            if (! Schema::hasColumn('job_posts', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('status');
            }
        });

        Schema::table('job_applications', function (Blueprint $table): void {
            if (! Schema::hasColumn('job_applications', 'internal_notes')) {
                $table->text('internal_notes')->nullable()->after('admin_notes');
            }

            if (! Schema::hasColumn('job_applications', 'interview_requested_at')) {
                $table->timestamp('interview_requested_at')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table): void {
            foreach (['interview_requested_at', 'internal_notes'] as $column) {
                if (Schema::hasColumn('job_applications', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('job_posts', function (Blueprint $table): void {
            foreach (['published_at', 'promotion_status', 'applicant_questions'] as $column) {
                if (Schema::hasColumn('job_posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('employers', function (Blueprint $table): void {
            foreach ([
                'learning_enabled',
                'advertising_enabled',
                'premium_status',
                'billing_plan',
                'billing_email',
                'is_published',
                'social_links',
                'company_size',
                'cover_path',
                'logo_path',
            ] as $column) {
                if (Schema::hasColumn('employers', $column)) {
                    $table->dropColumn($column);
                }
            }

            if (Schema::hasColumn('employers', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};
