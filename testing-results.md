# Automated Testing Results

## Testing Framework
Native PHP Automated Testing

## Test File
booking_test.php

## Test Cases

# | No. | Test Description        | Expected Result     | Actual Result           | Status |
  | 1 | Valid booking dates       | Accepted            | Accepted                | PASS |
  | 2 | Invalid booking dates     | Rejected            | Rejected                | PASS |
  | 3 | Overlapping bookings      | Detected            | Detected                | PASS |
  | 4 | Non-overlapping bookings  | Allowed             | Allowed                 | PASS |
  | 5 | Separate booking periods  | No overlap detected | No overlap detected     | PASS |

## Test Summary

- Total Tests: 5
- Passed: 5
- Failed: 0
- Success Rate: 100%

## Conclusion

All five automated test cases executed successfully.
The tests confirmed that the booking date validation
and overlapping period detection logic behaved as expected.
