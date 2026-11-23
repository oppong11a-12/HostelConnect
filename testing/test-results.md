# HostelConnect Automated Test Results

## 1. Testing Method
Native PHP Automated Testing

## 2. Test File
tests/booking_test.php

## 3. Test Results

| No. | Test Description | Expected Result | Actual Result | Status |
|---|---|---|---|---|
| 1 | Valid booking dates | Accepted | Accepted | PASS |
| 2 | Invalid booking dates | Rejected | Rejected | PASS |
| 3 | Overlapping booking periods | Detected | Detected | PASS |
| 4 | Back-to-back booking periods | Allowed | Allowed | PASS |
| 5 | Separate booking periods | No overlap | No overlap | PASS |

## 4. Test Summary

- Total Tests: 5
- Passed: 5
- Failed: 0
- Success Rate: 100%

## 5. Conclusion
All five automated tests executed successfully.


