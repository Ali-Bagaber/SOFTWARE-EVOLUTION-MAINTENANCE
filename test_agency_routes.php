<?php

// Test the agency routes, including the newly added profile route
echo "Agency Routes Test\n";
echo "----------------\n";
echo "1. agency.login: " . route('agency.login') . "\n";
echo "2. agency.password.recover: " . route('agency.password.recover') . "\n";
echo "3. agency.password.update: " . route('agency.password.update') . "\n";
echo "4. agency.profile: " . route('agency.profile') . "\n";
echo "5. agency.home: " . route('agency.home') . "\n";
echo "6. agency.dashboard: " . route('agency.dashboard') . "\n";

echo "\nAdded missing agency.profile route which is referenced in:\n";
echo "- AgencyHoomePage.blade.php (3 places)\n";
echo "- Created UserController::showAgencyProfile() method\n";
echo "- Route now points to the ManageUser.agency.Manageprofile view\n";
?>
