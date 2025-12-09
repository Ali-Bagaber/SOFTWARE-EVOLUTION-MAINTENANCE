<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Call the seeders
        $this->call([
            AgencySeeder::class,
            InquirySeeder::class,
        ]);

        // Create an admin user
        User::updateOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin',
            'password' => bcrypt('adminpassword'), // Change to your desired password
            'user_role' => 'admin',
            'contact_number' => '0123456789', // Add required fields if needed
        ]);

        // Create an agency user (optional example)
        User::updateOrCreate([
            'email' => 'agency@example.com',
        ], [
            'name' => 'Agency',
            'password' => bcrypt('agencypassword'),
            'user_role' => 'agency',
            'password_needs_update' => true,
            'contact_number' => '0123456789', // Add required fields if needed
        ]);

        // Optionally, create a test public user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
