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
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
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
        });
    }
};
