<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inquiry;
use App\Models\User;
use Carbon\Carbon;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users if they don't exist
        $users = User::take(5)->get();
        
        if ($users->count() < 3) {
            // Create some sample users for testing
            $sampleUsers = [
                [
                    'email' => 'user1@test.com',
                    'contact_number' => '0123456789',
                    'user_type' => 'Public User',
                    'password' => bcrypt('password123'),
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'date_of_birth' => '1990-01-01',
                    'gender' => 'Male',
                    'address' => '123 Test Street',
                    'city' => 'Test City',
                    'state' => 'Test State',
                    'postcode' => '12345',
                    'email_verified_at' => now(),
                ],
                [
                    'email' => 'user2@test.com',
                    'contact_number' => '0123456788',
                    'user_type' => 'Public User',
                    'password' => bcrypt('password123'),
                    'first_name' => 'Jane',
                    'last_name' => 'Smith',
                    'date_of_birth' => '1992-05-15',
                    'gender' => 'Female',
                    'address' => '456 Sample Ave',
                    'city' => 'Sample City',
                    'state' => 'Sample State',
                    'postcode' => '54321',
                    'email_verified_at' => now(),
                ],
                [
                    'email' => 'user3@test.com',
                    'contact_number' => '0123456787',
                    'user_type' => 'Public User',
                    'password' => bcrypt('password123'),
                    'first_name' => 'Bob',
                    'last_name' => 'Johnson',
                    'date_of_birth' => '1988-10-20',
                    'gender' => 'Male',
                    'address' => '789 Demo Blvd',
                    'city' => 'Demo City',
                    'state' => 'Demo State',
                    'postcode' => '67890',
                    'email_verified_at' => now(),
                ]
            ];
            
            foreach ($sampleUsers as $userData) {
                User::create($userData);
            }
            
            $users = User::take(3)->get();
        }
        
        // Sample inquiry data
        $inquiries = [
            [
                'title' => 'Internet Speed Issues in Rural Areas',
                'content' => 'I am experiencing very slow internet speeds in my rural location. The connection frequently drops and makes it difficult to work from home. This has been an ongoing issue for several months.',
                'category' => 'technical',
                'status' => 'Pending',
                'date_submitted' => Carbon::now()->subDays(2),
                'evidence_url' => 'https://speedtest.net/result/12345',
            ],
            [
                'title' => 'Billing Discrepancy on Monthly Statement',
                'content' => 'There appears to be an error on my monthly billing statement. I was charged for services I did not request or use. I need assistance in resolving this billing issue.',
                'category' => 'billing',
                'status' => 'In Progress',
                'date_submitted' => Carbon::now()->subDays(5),
                'evidence_url' => null,
            ],
            [
                'title' => 'Mobile Network Coverage Problem',
                'content' => 'The mobile network coverage in my area has been very poor lately. Calls are frequently dropped and data speeds are extremely slow. Multiple neighbors have the same issue.',
                'category' => 'network',
                'status' => 'Resolved',
                'date_submitted' => Carbon::now()->subDays(10),
                'evidence_url' => null,
            ],
            [
                'title' => 'Unauthorized Charges on Account',
                'content' => 'I noticed several unauthorized charges on my account that I did not approve. These appear to be premium services that I never signed up for. I need immediate assistance.',
                'category' => 'billing',
                'status' => 'Pending',
                'date_submitted' => Carbon::now()->subDays(1),
                'evidence_url' => null,
            ],
            [
                'title' => 'Service Outage in Commercial District',
                'content' => 'There has been a complete service outage in our commercial district for the past 3 days. This is affecting multiple businesses and causing significant revenue loss.',
                'category' => 'technical',
                'status' => 'In Progress',
                'date_submitted' => Carbon::now()->subDays(3),
                'evidence_url' => 'https://downdetector.com/status/provider/map/',
            ],
            [
                'title' => 'Data Privacy Concerns',
                'content' => 'I have concerns about how my personal data is being handled and stored. I would like to know what information is collected and how it is used.',
                'category' => 'privacy',
                'status' => 'Pending',
                'date_submitted' => Carbon::now()->subDays(7),
                'evidence_url' => null,
            ],
            [
                'title' => 'Equipment Malfunction',
                'content' => 'The modem provided by the company is constantly overheating and needs to be replaced. It shuts down randomly throughout the day.',
                'category' => 'technical',
                'status' => 'Resolved',
                'date_submitted' => Carbon::now()->subDays(14),
                'evidence_url' => null,
            ],
            [
                'title' => 'Customer Service Complaint',
                'content' => 'I received very poor customer service when I called to resolve an issue. The representative was unhelpful and rude. I expect better service quality.',
                'category' => 'service',
                'status' => 'Closed',
                'date_submitted' => Carbon::now()->subDays(20),
                'evidence_url' => null,
            ],
        ];
        
        // Create inquiries with random user assignments
        foreach ($inquiries as $inquiryData) {
            $user = $users->random();
            
            Inquiry::create([
                'public_user_id' => $user->user_id,
                'title' => $inquiryData['title'],
                'content' => $inquiryData['content'],
                'category' => $inquiryData['category'],
                'status' => $inquiryData['status'],
                'date_submitted' => $inquiryData['date_submitted'],
                'evidence_url' => $inquiryData['evidence_url'],
                'media_attachment' => null,
            ]);
        }
        
        $this->command->info('Created ' . count($inquiries) . ' sample inquiries');
    }
}
