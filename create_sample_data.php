<?php

// Quick test script to create sample inquiry data
// Run this once to populate the database with test data

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Sample inquiry data
$inquiries = [
    [
        'public_user_id' => 1, // Assuming user ID 1 exists
        'title' => 'Internet Speed Issues in Rural Areas',
        'content' => 'I am experiencing very slow internet speeds in my rural location. The connection frequently drops and makes it difficult to work from home. This has been an ongoing issue for several months.',
        'category' => 'technical',
        'status' => 'Pending',
        'date_submitted' => Carbon::now()->subDays(2),
        'evidence_url' => 'https://speedtest.net/result/12345',
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'public_user_id' => 1,
        'title' => 'Billing Discrepancy on Monthly Statement',
        'content' => 'There appears to be an error on my monthly billing statement. I was charged for services I did not request or use. I need assistance in resolving this billing issue.',
        'category' => 'billing',
        'status' => 'In Progress',
        'date_submitted' => Carbon::now()->subDays(5),
        'evidence_url' => null,
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'public_user_id' => 1,
        'title' => 'Mobile Network Coverage Problem',
        'content' => 'The mobile network coverage in my area has been very poor lately. Calls are frequently dropped and data speeds are extremely slow. Multiple neighbors have the same issue.',
        'category' => 'network',
        'status' => 'Resolved',
        'date_submitted' => Carbon::now()->subDays(10),
        'evidence_url' => null,
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'public_user_id' => 1,
        'title' => 'Unauthorized Charges on Account',
        'content' => 'I noticed several unauthorized charges on my account that I did not approve. These appear to be premium services that I never signed up for. I need immediate assistance.',
        'category' => 'billing',
        'status' => 'Pending',
        'date_submitted' => Carbon::now()->subDays(1),
        'evidence_url' => null,
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'public_user_id' => 1,
        'title' => 'Service Outage in Commercial District',
        'content' => 'There has been a complete service outage in our commercial district for the past 3 days. This is affecting multiple businesses and causing significant revenue loss.',
        'category' => 'technical',
        'status' => 'In Progress',
        'date_submitted' => Carbon::now()->subDays(3),
        'evidence_url' => 'https://downdetector.com/status/provider/map/',
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'public_user_id' => 1,
        'title' => 'Data Privacy Concerns',
        'content' => 'I have concerns about how my personal data is being handled and stored. I would like to know what information is collected and how it is used.',
        'category' => 'privacy',
        'status' => 'Pending',
        'date_submitted' => Carbon::now()->subDays(7),
        'evidence_url' => null,
        'media_attachment' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ],
];

// Insert the data
DB::table('inquiries')->insert($inquiries);

echo "Sample inquiry data has been inserted successfully!\n";
echo "Total inquiries created: " . count($inquiries) . "\n";
