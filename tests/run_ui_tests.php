#!/usr/bin/env php
<?php
/**
 * PDEX UI/UX Test Execution Script
 * Runs PHPUnit tests for authentication and authorization
 */

echo "\n=================================\n";
echo "PDEX UI/UX Authentication Tests\n";
echo "=================================\n\n";

echo "� Running PHPUnit test suite...\n\n";

// Change to project directory
$projectRoot = dirname(__DIR__);
chdir($projectRoot);

$testCommands = [
    "Feature Tests - Student Authentication" => "vendor/bin/phpunit tests/Feature/Auth/StudentAuthenticationTest.php --testdox",
    "Feature Tests - Institution Authentication" => "vendor/bin/phpunit tests/Feature/Auth/InstitutionAuthenticationTest.php --testdox", 
    "Feature Tests - Ministry Authentication" => "vendor/bin/phpunit tests/Feature/Auth/MinistryAuthenticationTest.php --testdox",
    "Feature Tests - Admin Authentication" => "vendor/bin/phpunit tests/Feature/Auth/AdminAuthenticationTest.php --testdox",
    "Unit Tests - Admin Middleware" => "vendor/bin/phpunit tests/Unit/Middleware/AdminMiddlewareTest.php --testdox",
    "Unit Tests - User Roles" => "vendor/bin/phpunit tests/Unit/Models/UserRoleTest.php --testdox",
];

$results = [];
$totalTests = 0;
$passedTests = 0;

foreach ($testCommands as $description => $command) {
    echo "� $description\n";
    echo str_repeat("-", 50) . "\n";
    
    $output = [];
    $returnCode = 0;
    
    exec($command . " 2>&1", $output, $returnCode);
    
    $testOutput = implode("\n", $output);
    echo $testOutput . "\n\n";
    
    // Parse test results
    if (preg_match('/(\d+) tests?, (\d+) assertions?/', $testOutput, $matches)) {
        $tests = (int)$matches[1];
        $totalTests += $tests;
        
        if ($returnCode === 0) {
            $passedTests += $tests;
            $results[] = "✅ $description: $tests tests passed";
        } else {
            $results[] = "❌ $description: Failed";
        }
    } else {
        $results[] = "⚠️  $description: Could not parse results";
    }
}

echo "\n=================================\n";
echo "Test Summary\n";
echo "=================================\n";

foreach ($results as $result) {
    echo $result . "\n";
}

echo "\nOverall: $passedTests/$totalTests tests passed\n";

if ($passedTests === $totalTests && $totalTests > 0) {
    echo "\n🎉 All tests passed!\n";
    $exitCode = 0;
} else {
    echo "\n❌ Some tests failed or could not run.\n";
    $exitCode = 1;
}

echo "\nQuick Commands:\n";
echo "• Run all auth tests: vendor/bin/phpunit tests/Feature/Auth/ tests/Unit/\n";
echo "• Run specific test: vendor/bin/phpunit tests/Feature/Auth/StudentAuthenticationTest.php\n";
echo "• Run with coverage: vendor/bin/phpunit --coverage-html coverage/\n";
echo "• Run in watch mode: vendor/bin/phpunit-watcher watch\n\n";

exit($exitCode);
