<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('country')->nullable()->after('phone_verified_at');
            $table->string('city')->nullable()->after('country');
            $table->string('nationality')->nullable()->after('city');
            $table->string('current_job_title')->nullable()->after('nationality');
            $table->string('preferred_job_category')->nullable()->after('current_job_title');
            $table->string('linkedin_url')->nullable()->after('preferred_job_category');
            $table->string('portfolio_url')->nullable()->after('linkedin_url');
            $table->string('personal_website_url')->nullable()->after('portfolio_url');
            $table->string('github_url')->nullable()->after('personal_website_url');
            $table->string('behance_url')->nullable()->after('github_url');
            $table->string('youtube_url')->nullable()->after('behance_url');
            $table->string('tiktok_url')->nullable()->after('youtube_url');
            $table->string('visa_work_permit_status')->nullable()->after('tiktok_url');
            $table->json('preferred_work_countries')->nullable()->after('visa_work_permit_status');
            $table->boolean('willing_to_relocate')->default(false)->after('preferred_work_countries');
            $table->boolean('available_for_remote_work')->default(false)->after('willing_to_relocate');

            $table->index('country');
            $table->index('preferred_job_category');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex(['country']);
            $table->dropIndex(['preferred_job_category']);
            $table->dropColumn([
                'country',
                'city',
                'nationality',
                'current_job_title',
                'preferred_job_category',
                'linkedin_url',
                'portfolio_url',
                'personal_website_url',
                'github_url',
                'behance_url',
                'youtube_url',
                'tiktok_url',
                'visa_work_permit_status',
                'preferred_work_countries',
                'willing_to_relocate',
                'available_for_remote_work',
            ]);
        });
    }
};
