# HOSTELCONNECT
## FINAL PROJECT REPORT

**Project title:** Design and Implementation of a Multi-University Web-Based Student Hostel Booking System in Ghana  
**Project name:** HostelConnect  
**Project type:** PHP and MySQL/MariaDB web application  
**Development environment:** XAMPP  
**External service:** Paystack Sandbox (test mode)

---

## 1. Executive Summary

HostelConnect is a web-based student hostel booking system designed to help tertiary students discover private hostel accommodation, review room information and submit booking requests. The platform also provides hostel owners with tools to manage hostel and room listings and provides administrative access for platform oversight.

The project was developed using PHP, MySQL/MariaDB, HTML, CSS and JavaScript. It includes user authentication, role-based access, hostel and room management, booking management and Paystack Sandbox payment processing.

## 2. Problem Statement

Students may find it difficult to identify suitable accommodation, compare available rooms and confirm whether a room can be booked for their intended period. HostelConnect addresses this problem by placing hostel listings, room information and booking functions in one web application.

## 3. Project Objectives

The project objectives were to:

1. Provide account registration and authentication for system users.
2. Allow students to browse hostels and view room details.
3. Allow hostel owners to manage hostel and room information.
4. Support room booking with date validation and availability checks.
5. Maintain booking and payment records.
6. Integrate Paystack Sandbox to support simulated payment transactions.
7. Apply role-based access to protected application functions.

## 4. Project Scope and Users

The project database contains university records for AAMUSTED, KNUST and KTU. The application supports three user roles:

- **Student:** registers, signs in, browses hostels, views rooms, makes bookings, initiates payments and checks booking/payment records.
- **Hostel Owner:** manages hostel details and rooms and views bookings associated with the owner's hostels.
- **Administrator:** accesses the administrative area and oversees platform users and activities.

The implemented scope covers authentication, hostel discovery and management, room management, booking workflows and test-mode payment processing. Online deployment and live payment processing are outside the current local development configuration.

## 5. Development Approach

The team organised work using an Agile approach and an eight-week project schedule divided into three sprints: requirements and project foundation; core development and integration; and testing, stabilisation and final submission. GitHub branches, commits and pull requests were used to coordinate contributions and integrate changes.

### 5.1 Team Roles and Responsibilities

The project was completed through collaboration among four team members, with responsibilities assigned to support project coordination, system development, testing and documentation.

- **Project Manager:** Coordinated project activities, monitored progress, managed repository integration and supervised the overall project.
- **Lead Developer:** Led the implementation of the application and contributed to the system's technical development.
- **Tester:** Prepared the software test plan, conducted tests, documented test results and supported defect identification.
- **Documentation Lead:** Prepared and organised the Software Requirements Specification, system design documents, diagrams and final project documentation.

GitHub was used to manage source-code contributions through branches, commits and pull requests.

## 6. System Design and Technology

HostelConnect follows a web-based client-server structure. The browser presents the user interface, PHP processes application requests and MySQL/MariaDB stores system records. PDO is used for database access. The application separates authentication, role checks, hostel management, room management, bookings and payments into organised project modules.

The main technologies are:

- PHP for server-side processing.
- MySQL/MariaDB for relational data storage.
- HTML, CSS and JavaScript for the interface.
- XAMPP for local development and testing.
- Git and GitHub for source control and team collaboration.
- Paystack Sandbox for simulated payment transactions.

## 7. Database Design

The supplied SQL schema defines six tables:

1. `universities` – supported university names and locations.
2. `users` – user details, credentials and role.
3. `hostels` – hostel details linked to a university and owner.
4. `rooms` – room details, hostel relationship and price.
5. `bookings` – student, room, booking dates and booking status.
6. `payments` – booking reference, amount, payment reference and payment status.

Primary keys and foreign keys maintain relationships between the records. The schema also defines constraints for unique user email addresses, unique room numbers within a hostel, positive room prices, positive payment amounts and valid booking date order.

Room prices are treated as semester-based charges. The payment initialization code uses the room price stored in the database. The current schema does not store a separate semester or academic-year field.

## 8. Implemented System Features

### 8.1 User Authentication and Access Control

The application provides registration, login and logout functions. Protected modules use role checks to restrict access according to the signed-in user's role.

### 8.2 Hostel and Room Management

Hostel owners can create and manage hostel information and maintain room records. Hostel records are associated with a university and an owner, while room records are associated with a hostel.

### 8.3 Hostel Discovery

Students can browse hostel listings and view hostel and room information to support accommodation selection.

### 8.4 Booking Management

Students can submit room booking requests with start and end dates. The application validates date values and checks existing bookings when processing a request. Booking records use Pending, Confirmed, Cancelled and Completed statuses.

### 8.5 Paystack Sandbox Payments

The payment modules initialize Paystack test-mode transactions, create local pending payment records, redirect students to the checkout flow and verify payment references. The verification process updates payment and booking records according to the verified result. Payment configuration is loaded from environment variables rather than embedding secret credentials in application source files.

## 9. Testing and Results

The project includes a native PHP automated booking test covering five date-validation and booking-overlap scenarios. The test was executed successfully during project work.

| Test | Expected outcome | Recorded result |
|---|---|---|
| Valid booking dates | Accepted | PASS |
| Invalid booking dates | Rejected | PASS |
| Overlapping booking periods | Detected | PASS |
| Back-to-back booking periods | Allowed | PASS |
| Separate booking periods | No overlap | PASS |

**Automated test summary:** 5 passed, 0 failed; 100% pass rate for these five test cases.

The automated test covers standalone booking-date and overlap logic. It does not by itself verify every database operation, authentication path or payment scenario. The team also reported successful local end-to-end checks of the student/owner workflows and a successful Paystack Sandbox checkout and verification during project testing.

## 10. Project Documentation

The documentation package includes the Software Requirements Specification, project risk register, sprint plans, use-case diagram, class diagram, sequence diagram, database design notes, database testing notes and this final report. The diagrams describe system actors, core data classes and the booking/payment interaction sequence.

## 11. Limitations and Future Improvements

The current project is configured for local development using XAMPP. The following improvements remain outside the current completed scope or require further deployment work:

- Deploying the application to a publicly accessible hosting environment.
- Configuring production Paystack credentials and validating live transactions.
- Adding a dedicated SMS or email notification service.
- Recording semester and academic-year information as structured database data if semester history is required.
- Expanding automated test coverage to database integration, authentication, authorization, booking workflows and payment verification.
- Conducting broader browser, mobile-device, security and performance testing.

## 12. Conclusion

HostelConnect delivers a PHP and MySQL/MariaDB web application for student hostel discovery and booking. The project brings together user authentication, role-based access, hostel and room management, booking records and Paystack Sandbox payment processing. The relational database provides structured storage for the platform's main records, while the project documentation and GitHub workflow support team coordination and maintenance.

The five automated booking tests passed during execution. Further deployment, production payment configuration and expanded testing can strengthen the system beyond its current local project scope.
