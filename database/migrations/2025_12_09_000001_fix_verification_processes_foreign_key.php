<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, delete any verification_processes records with invalid staff_agency_id
        // that don't correspond to existing agencies
        DB::statement('DELETE FROM verification_processes 
                       WHERE staff_agency_id NOT IN (SELECT agency_id FROM agencies)');

        // Get the foreign key name
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'verification_processes' 
            AND COLUMN_NAME = 'staff_agency_id'
            AND REFERENCED_TABLE_NAME = 'users'
        ");

        if (!empty($foreignKeys)) {
            $foreignKeyName = $foreignKeys[0]->CONSTRAINT_NAME;
            DB::statement("ALTER TABLE verification_processes DROP FOREIGN KEY `{$foreignKeyName}`");
        }

        // Add new foreign key that references agencies table instead of users
        Schema::table('verification_processes', function (Blueprint $table) {
            $table->foreign('staff_agency_id')
                ->references('agency_id')
                ->on('agencies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_processes', function (Blueprint $table) {
            // Drop the new foreign key
            $table->dropForeign(['staff_agency_id']);

            // Restore the old foreign key to users table
            $table->foreign('staff_agency_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
