<?php

// Test the agency routes, including the newly added inquiries route
echo "Agency Routes Test\n";
echo "----------------\n";
echo "1. agency.login: " . route('agency.login') . "\n";
echo "2. agency.password.recover: " . route('agency.password.recover') . "\n";
echo "3. agency.password.update: " . route('agency.password.update') . "\n";
echo "4. agency.profile: " . route('agency.profile') . "\n";
echo "5. agency.inquiries: " . route('agency.inquiries') . "\n";
echo "6. agency.home: " . route('agency.home') . "\n";
echo "7. agency.dashboard: " . route('agency.dashboard') . "\n";

echo "\nAdded missing agency.inquiries route which is referenced in:\n";
echo "- AgencyHoomePage.blade.php (2 places)\n";
echo "- Created inquiries.blade.php view\n";
echo "- Created UserController::showAgencyInquiries() method\n";
echo "- Added route in web.php\n";
?>
