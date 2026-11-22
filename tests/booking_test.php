<?php

declare(strict_types=1);

/**
 * HostelConnect Automated Booking Tests
 *
 * Tests booking date validation and overlapping
 * booking detection.
 */

// Display test results
$passed = 0;
$failed = 0;

function runTest(string $name, bool $condition): void
{
    global $passed, $failed;

    if ($condition) {
        echo "[PASS] $name" . PHP_EOL;
        $passed++;
    } else {
        echo "[FAIL] $name" . PHP_EOL;
        $failed++;
    }
}

/**
 * Check whether booking dates are valid.
 */
function validBookingDates(
    string $checkIn,
    string $checkOut
): bool {
    return strtotime($checkOut) > strtotime($checkIn);
}

/**
 * Check whether two booking periods overlap.
 */
function bookingsOverlap(
    string $existingStart,
    string $existingEnd,
    string $newStart,
    string $newEnd
): bool {

    return strtotime($newStart) < strtotime($existingEnd)
        && strtotime($newEnd) > strtotime($existingStart);
}


// TEST 1: Valid booking dates

runTest(
    "Valid booking dates are accepted",
    validBookingDates("2026-12-01", "2026-12-05")
);


// TEST 2: Invalid booking dates

runTest(
    "Invalid booking dates are rejected",
    !validBookingDates("2026-12-05", "2026-12-01")
);


// TEST 3: Overlapping bookings

runTest(
    "Overlapping bookings are detected",
    bookingsOverlap(
        "2026-12-01",
        "2026-12-10",
        "2026-12-05",
        "2026-12-15"
    )
);


// TEST 4: Non-overlapping bookings

runTest(
    "Non-overlapping bookings are allowed",
    !bookingsOverlap(
        "2026-12-01",
        "2026-12-05",
        "2026-12-05",
        "2026-12-10"
    )
);


// TEST 5: Completely separate bookings

runTest(
    "Separate booking periods do not overlap",
    !bookingsOverlap(
        "2026-12-01",
        "2026-12-05",
        "2026-12-10",
        "2026-12-15"
    )
);


// DISPLAY SUMMARY

echo PHP_EOL;
echo "========== TEST SUMMARY ==========" . PHP_EOL;
echo "Passed: $passed" . PHP_EOL;
echo "Failed: $failed" . PHP_EOL;
echo "Total:  " . ($passed + $failed) . PHP_EOL;

if ($failed === 0) {
    echo "RESULT: ALL TESTS PASSED" . PHP_EOL;
} else {
    echo "RESULT: SOME TESTS FAILED" . PHP_EOL;
}
