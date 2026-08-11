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
            if (! Schema::hasColumn('candidates', 'languages')) {
                $table->json('languages')->nullable()->after('skills');
            }

            if (! Schema::hasColumn('candidates', 'preferred_locations')) {
                $table->json('preferred_locations')->nullable()->after('preferred_job_category');
            }

            if (! Schema::hasColumn('candidates', 'employment_type_preference')) {
                $table->string('employment_type_preference')->nullable()->after('preferred_locations');
            }

            if (! Schema::hasColumn('candidates', 'work_mode_preference')) {
                $table->string('work_mode_preference')->nullable()->after('employment_type_preference');
            }

            if (! Schema::hasColumn('candidates', 'work_authorization')) {
                $table->string('work_authorization')->nullable()->after('work_mode_preference');
            }

            if (! Schema::hasColumn('candidates', 'visa_requirements')) {
                $table->string('visa_requirements')->nullable()->after('work_authorization');
            }

            if (! Schema::hasColumn('candidates', 'relocation_preference')) {
                $table->string('relocation_preference')->nullable()->after('visa_requirements');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table): void {
            foreach ([
                'relocation_preference',
                'visa_requirements',
                'work_authorization',
                'work_mode_preference',
                'employment_type_preference',
                'preferred_locations',
                'languages',
            ] as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
