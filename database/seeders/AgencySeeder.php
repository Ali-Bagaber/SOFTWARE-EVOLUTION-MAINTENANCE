<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManageUserModel\Agency;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default agencies if they don't exist
        Agency::firstOrCreate([
            'agency_name' => 'Malaysian Communications and Multimedia Commission'
        ], [
            'agency_email' => 'contact@mcmc.gov.my',
            'description' => 'Main regulatory body for communications and multimedia services',
            'agency_phone' => '+60-3-8688-8000'
        ]);

        Agency::firstOrCreate([
            'agency_name' => 'Public Inquiry Management'
        ], [
            'agency_email' => 'public@mcmc.gov.my',
            'description' => 'Handles public inquiries and complaints',
            'agency_phone' => '+60-3-8688-8001'
        ]);

        Agency::firstOrCreate([
            'agency_name' => 'Technical Services Division'
        ], [
            'agency_email' => 'technical@mcmc.gov.my',
            'description' => 'Handles technical inquiries and investigations',
            'agency_phone' => '+60-3-8688-8002'
        ]);
    }
}
