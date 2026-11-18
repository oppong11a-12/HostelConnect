# HostelConnect Test Plan

## 1. Introduction
This document describes the testing activities for the HostelConnect hostel booking system.

## 2. Testing Objectives
- Verify that booking dates are validated correctly.
- Check whether overlapping booking periods are detected.
- Confirm that non-overlapping booking periods are allowed.
- Identify errors in the system's functionality.

## 3. Testing Environment
- Operating System: Windows 11
- Programming Language: PHP
- Database: MySQL
- Local Server: XAMPP
- Code Editor: Visual Studio Code
- Testing Method: Native PHP automated testing

## 4. Test Cases

| No. | Test Description | Expected Result |
| 1 | Valid booking dates | Accepted |
| 2 | Invalid booking dates | Rejected |
| 3 | Overlapping booking periods | Overlap detected |
| 4 | Back-to-back booking periods | Allowed |
| 5 | Separate booking periods | No overlap detected |

## 5. Testing Approach
Automated tests are executed using PHP to evaluate booking date validation and booking overlap detection logic.

## 6. Limitations
The current automated tests focus on standalone booking date and overlap logic. They do not yet test the complete booking process, database operations, payment integration, or user authentication.

## 7. Conclusion
Testing helps ensure that HostelConnect performs its intended functions correctly and reliably.