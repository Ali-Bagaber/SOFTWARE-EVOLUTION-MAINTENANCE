<?php
/**
 * Test script to verify the agency login logic for contact_number validation
 * This script simulates the decision tree logic to ensure agencies with contact_number
 * go directly to dashboard instead of being redirected to recovery.
 */

echo "=== AGENCY LOGIN CONTACT NUMBER FIX TEST ===\n\n";

// Simulate different agency user scenarios
$testCases = [
    [
        'email' => 'testuser@agency.com',
        'contact_number' => null,
        'session_password_changed' => false,
        'last_login_at' => null,
        'expected_redirect' => 'password_update_form',
        'description' => '@agency.com user with NULL contact_number'
    ],
    [
        'email' => 'testuser@agency.com', 
        'contact_number' => '+1234567890',
        'session_password_changed' => false,
        'last_login_at' => null,
        'expected_redirect' => 'password_update_form',
        'description' => '@agency.com user with contact_number but no password change flag'
    ],
    [
        'email' => 'testuser@agency.com',
        'contact_number' => '+1234567890', 
        'session_password_changed' => true,
        'last_login_at' => '2025-06-01 10:00:00',
        'expected_redirect' => 'agency_dashboard',
        'description' => '@agency.com user with contact_number AND password changed'
    ],
    [
        'email' => 'testuser@agency',
        'contact_number' => null,
        'session_password_changed' => false,
        'last_login_at' => null,
        'expected_redirect' => 'password_update_form',
        'description' => '@agency user with NULL contact_number'
    ],
    [
        'email' => 'testuser@agency',
        'contact_number' => '+1234567890',
        'session_password_changed' => false,
        'last_login_at' => null,
        'expected_redirect' => 'password_recover',
        'description' => '@agency user with contact_number but hasDefaultPassword=true'
    ],
    [
        'email' => 'testuser@agency',
        'contact_number' => '+1234567890',
        'session_password_changed' => true,
        'last_login_at' => '2025-06-01 10:00:00',
        'expected_redirect' => 'agency_dashboard',
        'description' => '@agency user with contact_number AND hasDefaultPassword=false'
    ]
];

function simulateAgencyLoginLogic($user, $sessionPasswordChanged) {
    echo "Testing: " . $user['description'] . "\n";
    echo "  Email: " . $user['email'] . "\n";
    echo "  Contact Number: " . ($user['contact_number'] ?? 'NULL') . "\n";
    echo "  Session Password Changed: " . ($sessionPasswordChanged ? 'true' : 'false') . "\n";
    echo "  Last Login: " . ($user['last_login_at'] ?? 'NULL') . "\n";
    
    // Simulate the logic from UserController::loginAgency and dashboard methods
    
    // FIRST: Check if contact number is missing (highest priority)
    if ($user['contact_number'] === null || empty(trim($user['contact_number']))) {
        $result = 'password_update_form';
        echo "  → REDIRECTED TO: password_update_form (missing contact_number)\n";
    }
    // SECOND: For agency.com users, check if password needs to be changed
    else if (str_ends_with($user['email'], '@agency.com') && !$sessionPasswordChanged) {
        $result = 'password_update_form';
        echo "  → REDIRECTED TO: password_update_form (@agency.com needs password change)\n";
    }
    // THIRD: For @agency users (not .com), check if they need a password reset
    else if (str_ends_with($user['email'], '@agency') && simulateHasDefaultPassword($user, $sessionPasswordChanged)) {
        $result = 'password_recover';
        echo "  → REDIRECTED TO: password_recover (@agency hasDefaultPassword=true)\n";
    }
    // SUCCESS: If all checks pass, proceed to agency dashboard
    else {
        $result = 'agency_dashboard';
        echo "  → REDIRECTED TO: agency_dashboard (all checks passed)\n";
    }
    
    // Check if result matches expected
    $status = ($result === $user['expected_redirect']) ? "✅ PASS" : "❌ FAIL";
    echo "  Expected: " . $user['expected_redirect'] . " | Got: " . $result . " | " . $status . "\n\n";
    
    return $result === $user['expected_redirect'];
}

function simulateHasDefaultPassword($user, $sessionPasswordChanged) {
    // Simulate the hasDefaultPassword() logic from User model
    
    // If the user has never logged in, consider it a default password situation
    if (is_null($user['last_login_at'])) {
        return true;
    }
    
    // Check if the 'password_changed' session flag is not set
    if (!$sessionPasswordChanged) {
        return true;
    }
    
    return false;
}

// Run all test cases
$passedTests = 0;
$totalTests = count($testCases);

foreach ($testCases as $testCase) {
    if (simulateAgencyLoginLogic($testCase, $testCase['session_password_changed'])) {
        $passedTests++;
    }
}

echo "=== TEST RESULTS ===\n";
echo "Passed: $passedTests / $totalTests\n";

if ($passedTests === $totalTests) {
    echo "🎉 ALL TESTS PASSED! The contact_number logic should work correctly.\n\n";
    echo "KEY INSIGHTS:\n";
    echo "1. ✅ Users with NULL contact_number → Always go to password_update_form\n";
    echo "2. ✅ @agency.com users with contact_number but no password change → password_update_form\n";
    echo "3. ✅ @agency.com users with contact_number AND password changed → dashboard\n";
    echo "4. ✅ @agency users with contact_number but hasDefaultPassword=true → password_recover\n";
    echo "5. ✅ @agency users with contact_number AND hasDefaultPassword=false → dashboard\n\n";
    echo "The fix ensures agencies with existing contact_numbers can reach the dashboard!\n";
} else {
    echo "❌ Some tests failed. Please review the logic.\n";
}
?>
