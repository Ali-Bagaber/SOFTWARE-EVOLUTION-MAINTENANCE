<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Add assigned_agency_id column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'assigned_agency_id')) {
                $table->unsignedBigInteger('assigned_agency_id')->nullable();
                $table->foreign('assigned_agency_id')->references('agency_id')->on('agencies')->onDelete('set null');
            }

            // Add assignment_notes column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'assignment_notes')) {
                $table->text('assignment_notes')->nullable();
            }

            // Add assigned_by column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'assigned_by')) {
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->foreign('assigned_by')->references('user_id')->on('users')->onDelete('set null');
            }

            // Add assigned_at column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'assigned_at')) {
                $table->timestamp('assigned_at')->nullable();
            }

            // Add accepted_at column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'accepted_at')) {
                $table->timestamp('accepted_at')->nullable();
            }

            // Add acceptance_notes column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'acceptance_notes')) {
                $table->text('acceptance_notes')->nullable();
            }

            // Add rejected_at column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable();
            }

            // Add rejection_reason column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'rejection_reason')) {
                $table->string('rejection_reason', 100)->nullable();
            }

            // Add rejection_comments column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'rejection_comments')) {
                $table->text('rejection_comments')->nullable();
            }

            // Add priority column if it doesn't exist
            if (!Schema::hasColumn('inquiries', 'priority')) {
                $table->string('priority', 20)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            // Drop columns only if they exist
            if (Schema::hasColumn('inquiries', 'assigned_agency_id')) {
                $table->dropForeign(['assigned_agency_id']);
                $table->dropColumn('assigned_agency_id');
            }

            if (Schema::hasColumn('inquiries', 'assignment_notes')) {
                $table->dropColumn('assignment_notes');
            }

            if (Schema::hasColumn('inquiries', 'assigned_by')) {
                $table->dropForeign(['assigned_by']);
                $table->dropColumn('assigned_by');
            }

            if (Schema::hasColumn('inquiries', 'assigned_at')) {
                $table->dropColumn('assigned_at');
            }

            if (Schema::hasColumn('inquiries', 'accepted_at')) {
                $table->dropColumn('accepted_at');
            }

            if (Schema::hasColumn('inquiries', 'acceptance_notes')) {
                $table->dropColumn('acceptance_notes');
            }

            if (Schema::hasColumn('inquiries', 'rejected_at')) {
                $table->dropColumn('rejected_at');
            }

            if (Schema::hasColumn('inquiries', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }

            if (Schema::hasColumn('inquiries', 'rejection_comments')) {
                $table->dropColumn('rejection_comments');
            }

            if (Schema::hasColumn('inquiries', 'priority')) {
                $table->dropColumn('priority');
            }
        });
    }
};
