# HOSTELCONNECT
# SOFTWARE TEST PLAN AND TEST RESULTS

## 1. Introduction

### 1.1 Purpose

This Software Test Plan describes the testing activities required to evaluate the functionality, reliability, security and performance of the HostelConnect system.

The purpose of testing is to identify defects, verify that system requirements are satisfied and ensure that the application behaves as expected when users perform different operations.

### 1.2 System Overview

HostelConnect is a web-based hostel booking management system designed to connect students with private hostel accommodation around selected universities in Ghana.

The system provides functionality for:

- User registration and authentication.
- Hostel discovery and searching.
- Hostel information management.
- Room information management.
- Room availability checking.
- Accommodation booking.
- Payment record management.
- Role-based access control.

### 1.3 Testing Objectives

The main testing objectives are to:

1. Verify that the system functions according to its requirements.
2. Identify and correct software defects.
3. Verify database connectivity and data integrity.
4. Ensure that users can perform authorized operations.
5. Verify that booking operations handle room availability correctly.
6. Evaluate payment processing and payment status handling.
7. Verify that the application handles invalid inputs appropriately.
8. Confirm that the system is usable and reliable.

## 2. Testing Scope

### 2.1 Features to Be Tested

| No. | Feature | Testing Description |
|---|---|---|
| 1 | User Registration | Verify account creation and input validation. |
| 2 | User Login | Verify authentication using valid and invalid credentials. |
| 3 | Role Management | Verify access permissions for different user roles. |
| 4 | Hostel Management | Verify hostel creation, editing and information retrieval. |
| 5 | Hostel Discovery | Verify hostel browsing and searching. |
| 6 | Room Management | Verify room creation, editing and retrieval. |
| 7 | Room Availability | Verify availability checks against existing bookings. |
| 8 | Booking Management | Verify booking creation and status management. |
| 9 | Payment Management | Verify payment records and payment statuses. |
| 10 | Database Operations | Verify database connectivity and data integrity. |

### 2.2 Features Outside the Confirmed Testing Scope

The following should only be included in final execution results if the corresponding functionality has been implemented:

- Live payment processing.
- Production Paystack integration.
- SMS notifications.
- Online production deployment.
- Advanced analytics.

Paystack Sandbox testing is included as a planned integration test, subject to confirmation that the integration exists in the application.

## 3. Testing Environment

The following environment is used for testing HostelConnect.

| Component | Description |
|---|---|
| Operating System | Windows 11 |
| Web Server | Apache |
| Local Server Environment | XAMPP |
| Programming Language | PHP |
| Database Management System | MySQL |
| Database Name | hostelconnect_db |
| Database Connection | PDO |
| Code Editor | Visual Studio Code |
| Database Administration | phpMyAdmin |
| Web Browser | Google Chrome |
| Version Control | Git and GitHub |
| Automated Testing Framework | PHPUnit 11.5.58 |

## 4. Testing Strategy

Different testing methods will be used to evaluate the system.

### 4.1 Unit Testing

Unit testing examines individual components of the application to ensure that they perform their intended functions.

Examples include:

- User input validation.
- Authentication functions.
- Booking date validation.
- Payment amount validation.
- Room availability checking.

### 4.2 Integration Testing

Integration testing evaluates whether different components work correctly together.

Examples include:

- PHP application and MySQL database connectivity.
- User registration and database storage.
- Booking creation and database updates.
- Payment processing and booking status updates.

### 4.3 Functional Testing

Functional testing verifies whether the system performs the operations specified in its requirements.

Examples include:

- Registering an account.
- Logging into the system.
- Searching for hostels.
- Adding rooms.
- Creating bookings.
- Recording payments.

### 4.4 Security Testing

Security testing evaluates whether the application protects user information and prevents unauthorized access.

Examples include:

- Invalid login attempts.
- Unauthorized page access.
- Role-based access restrictions.
- SQL injection prevention.
- Password protection.

### 4.5 User Acceptance Testing

User Acceptance Testing evaluates whether the system satisfies the needs of its intended users.

Potential participants include:

- Students.
- Hostel owners.
- System administrators.

Participants will perform representative tasks and provide feedback about the system's functionality and usability.

## 5. Functional Test Cases

The following test cases define the operations that should be tested.

### 5.1 User Registration Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-01 | Register with valid information | Valid name, email and password | Account should be created. |
| TC-02 | Register using an existing email | Previously registered email | System should reject duplicate email. |
| TC-03 | Submit empty required fields | Empty registration form | Validation message should appear. |
| TC-04 | Enter invalid email format | Invalid email address | System should reject the email. |

### 5.2 Login Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-05 | Login with valid credentials | Correct email and password | User should access the system. |
| TC-06 | Login with incorrect password | Correct email, incorrect password | Access should be denied. |
| TC-07 | Login with unregistered email | Unknown email | Access should be denied. |
| TC-08 | Submit empty login fields | Empty credentials | Validation message should appear. |

### 5.3 Hostel Management Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-09 | Add a hostel | Valid hostel information | Hostel should be saved. |
| TC-10 | Edit hostel information | Updated hostel details | Changes should be saved. |
| TC-11 | View hostel information | Existing hostel | Correct details should appear. |
| TC-12 | Submit incomplete hostel information | Missing required information | System should display validation errors. |

### 5.4 Room Management Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-13 | Add a room | Valid room information | Room should be saved. |
| TC-14 | Edit room information | Updated room details | Changes should be saved. |
| TC-15 | Enter duplicate room number in the same hostel | Existing room number | System should reject the duplicate. |
| TC-16 | Enter an invalid room price | Zero or negative price | System should reject the price. |

### 5.5 Booking Management Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-17 | Create a valid booking | Valid room and dates | Booking should be created. |
| TC-18 | Submit invalid booking dates | End date before start date | System should reject the booking. |
| TC-19 | Book an unavailable room | Conflicting booking dates | System should reject the conflicting booking. |
| TC-20 | View booking details | Existing booking | Correct booking information should appear. |
| TC-21 | Create a new booking | Valid booking information | Initial status should be Pending. |

### 5.6 Payment Management Testing

| Test ID | Test Scenario | Test Input | Expected Result |
|---|---|---|---|
| TC-22 | Record a successful payment | Valid payment information | Payment should be recorded as Successful. |
| TC-23 | Record a failed payment | Failed payment result | Payment should have Failed status. |
| TC-24 | Record a pending payment | Pending payment information | Payment should have Pending status. |
| TC-25 | Enter duplicate payment reference | Existing reference | System should reject the duplicate reference. |
| TC-26 | Enter an invalid payment amount | Zero or negative amount | System should reject the amount. |

## 6. Integration Testing

Integration testing verifies communication between the major system components.

| Test ID | Components | Test Description | Expected Result |
|---|---|---|---|
| IT-01 | PHP and MySQL | Establish database connection | Connection should succeed. |
| IT-02 | Registration and Database | Save a new user | User record should be stored. |
| IT-03 | Hostel Management and Database | Save hostel information | Hostel record should be stored. |
| IT-04 | Room Management and Database | Save room information | Room record should be stored. |
| IT-05 | Booking and Database | Create a booking | Booking record should be stored. |
| IT-06 | Payment and Database | Save payment information | Payment record should be stored. |
| IT-07 | Payment and Paystack | Initialize and verify a sandbox payment | Payment result should be verified, if integration is implemented. |

## 7. Security Testing

The following security tests should be performed.

| Test ID | Security Test | Expected Result |
|---|---|---|
| ST-01 | Attempt login with incorrect credentials | Access should be denied. |
| ST-02 | Attempt to access protected pages without authentication | Access should be restricted. |
| ST-03 | Attempt unauthorized administrative operations | Operation should be denied. |
| ST-04 | Submit SQL injection-like input | Application should handle input safely. |
| ST-05 | Inspect password storage | Passwords should be securely hashed. |
| ST-06 | Attempt to access another owner's restricted hostel records | Access should be denied. |

## 8. User Acceptance Testing

User Acceptance Testing will evaluate the system from the perspective of its intended users.

| Test ID | User | Activity | Expected Outcome |
|---|---|---|---|
| UAT-01 | Student | Register and log in | Student can access the system. |
| UAT-02 | Student | Search for a hostel | Matching hostels are displayed. |
| UAT-03 | Student | Select a room and make a booking | Booking is created successfully. |
| UAT-04 | Hostel Owner | Add hostel information | Hostel information is saved. |
| UAT-05 | Hostel Owner | Add room information | Room information is saved. |
| UAT-06 | Administrator | Access administrative functions | Authorized administrative operations are available. |

Feedback from participants should be documented and used to improve the application.

## 9. Automated Testing

Automated testing is used to verify important system functions and reduce repetitive manual testing.

### 9.1 Testing Framework

PHPUnit 11.5.58 was used as the automated testing framework.

Testing environment:

- PHPUnit: 11.5.58
- PHP: 8.2.12
- Operating System: Windows 11
- Database: MySQL
- Local Server: XAMPP

### 9.2 Automated Test Areas

The automated testing activities include:

- Booking date validation.
- Booking period validation.
- Booking overlap logic.

### 9.3 Booking Date Validation Tests

The booking date validation logic was extracted into:

`config/booking_validation.php`

The tests were implemented in:

`tests/BookingDateTest.php`

The following tests were executed:

| Test ID | Test Description | Expected Result | Actual Result | Status |
|---|---|---|---|---|
| AT-01 | End date after start date | Accepted | Accepted | PASS |
| AT-02 | Equal start and end dates | Rejected | Rejected | PASS |
| AT-03 | End date before start date | Rejected | Rejected | PASS |
| AT-04 | Missing booking date | Rejected | Rejected | PASS |
| AT-05 | Invalid date format | Rejected | Rejected | PASS |

### 9.4 Automated Test Execution Results

Command executed:

`.\vendor\bin\phpunit tests/BookingDateTest.php`

Execution summary:

- PHPUnit version: 11.5.58
- PHP version: 8.2.12
- Tests executed: 5
- Assertions: 5
- Passed: 5
- Failed: 0

Result: All five booking date validation tests passed.

### 9.5 Booking Overlap Testing

Booking overlap testing is intended to verify that two bookings for the same room cannot occupy conflicting date periods.

The following scenarios are included:

| Test ID | Test Description | Expected Result |
|---|---|---|
| AT-06 | Overlapping booking periods | Overlap detected |
| AT-07 | Identical booking periods | Overlap detected |
| AT-08 | Non-overlapping booking periods | Allowed |
| AT-09 | Back-to-back booking periods | Allowed |

These scenarios must only be marked as passed after their automated tests have been executed successfully.

### 9.6 Testing Limitations

The completed automated date-validation tests verify the booking date validation functions. They do not independently verify the complete database booking process, concurrent booking requests, payment integration, or user authentication.

Database integration and full application testing should be recorded separately.

## 10. Test Results

This section records the actual outcome of testing.

| Test ID | Test Description | Actual Result | Status | Remarks |
|---|---|---|---|---|
| AT-01 | End date after start date | Accepted | PASS | Automated |
| AT-02 | Equal booking dates | Rejected | PASS | Automated |
| AT-03 | End date before start date | Rejected | PASS | Automated |
| AT-04 | Missing booking date | Rejected | PASS | Automated |
| AT-05 | Invalid date format | Rejected | PASS | Automated |
| AT-06 | Overlapping booking periods | Pending execution | Not Run | |
| AT-07 | Identical booking periods | Pending execution | Not Run | |
| AT-08 | Non-overlapping booking periods | Pending execution | Not Run | |
| AT-09 | Back-to-back booking periods | Pending execution | Not Run | |

### Status Definitions

- **Passed:** Actual result matches expected result.
- **Failed:** Actual result does not match expected result.
- **Not Run:** Test has not yet been executed.
- **Blocked:** Test cannot be executed because a required feature or dependency is unavailable.

## 11. Defect Reporting and Management

Any defects discovered during testing should be documented and assigned appropriate priorities.

| Defect ID | Description | Severity | Priority | Status |
|---|---|---|---|---|
| DEF-01 | Description of discovered defect | High/Medium/Low | High/Medium/Low | Open/Resolved |
| DEF-02 | Description of discovered defect | High/Medium/Low | High/Medium/Low | Open/Resolved |

## 12. Conclusion

The HostelConnect Software Test Plan establishes a structured approach for evaluating the functionality, integration, security and usability of the system.

Automated booking date validation tests have been executed successfully. Further testing of booking overlap logic and complete database booking operations should be conducted and documented before final project submission.

Testing results will continue to be updated as the development team executes the planned test cases.