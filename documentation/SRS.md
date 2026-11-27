I've reviewed the SRS against the **actual six-table HostelConnect database structure** we established earlier.

Your document is well organised, but it contains some inconsistencies that could cost marks if your lecturer compares the SRS with the database and application.

### Important corrections made

1. Removed `room_types`, `notifications` and `activity_logs` because they are not among the six existing database tables.
2. Corrected the database description to reflect the actual columns and relationships.
3. Removed the claim that hostel approval is supported by the current database. There is no approval-status column in the `hostels` table.
4. Clarified that Paystack Sandbox is planned, not yet confirmed as integrated.
5. Clarified that semester pricing is a project requirement, but the current database does not explicitly store a semester or academic year.
6. Distinguished database support from application functionality that still needs testing.
7. Improved the functional requirements so they are specific, consistent and testable.

Below is the corrected version. **Replace your current SRS with this version.**

---

# HOSTELCONNECT
## SOFTWARE REQUIREMENTS SPECIFICATION (SRS)

**Project Title:** Design and Implementation of a Multi-University Web-Based Student Hostel Booking System in Ghana  
**Project Name:** HostelConnect  
**Tagline:** Connecting Students to Their Ideal Accommodation.  
**Project Type:** Web-Based Application  
**Target Universities:** AAMUSTED, KNUST and KTU  
**Programming Language:** PHP  
**Database Management System:** MySQL/MariaDB  
**Frontend Technologies:** HTML, CSS and JavaScript  
**Development Environment:** XAMPP  
**External Payment Service:** Paystack Sandbox (planned integration)

---

# 1.0 INTRODUCTION

## 1.1 Purpose

The purpose of HostelConnect is to develop a web-based student hostel booking system that simplifies the process of finding and reserving private hostel accommodation around selected universities in Ghana.

The system is intended to enable students to explore hostel listings, view room information and prices, and submit accommodation booking requests based on semester-based pricing.

It also provides hostel owners with facilities to submit and manage hostel and room information, while administrators oversee platform activities and manage user accounts.

## 1.2 Project Scope

HostelConnect is designed as a multi-university platform connecting students with private hostel accommodation around:

1. Akenten Appiah-Menka University of Skills Training and Entrepreneurial Development (AAMUSTED).
2. Kwame Nkrumah University of Science and Technology (KNUST).
3. Koforidua Technical University (KTU).

The project focuses on the following main functional areas:

1. **User Management:** Registration, authentication and role-based access for students, hostel owners and administrators.
2. **Hostel Discovery:** Displaying hostel information and enabling students to explore accommodation options.
3. **Hostel and Room Management:** Allowing hostel owners to submit and manage hostel and room information.
4. **Room Booking:** Enabling students to select rooms and submit semester-based accommodation booking requests.
5. **Booking Management:** Allowing students to view their bookings and hostel owners to manage bookings associated with their hostels.
6. **Payment Management:** Maintaining payment records and providing a foundation for future Paystack Sandbox integration.

The application is developed using PHP and MySQL/MariaDB. Paystack Sandbox integration is planned as an external-service component and will only be considered implemented after successful integration and testing.

## 1.3 Intended Users

HostelConnect has three main user categories.

### 1.3.1 Students

Students are the primary users of the system. They are expected to:

- Register for an account.
- Log in and access their dashboard.
- Browse available hostels.
- Explore hostel and room information.
- View room prices.
- Select rooms for booking.
- Submit booking requests.
- View their booking records and statuses.
- Manage their account information.

### 1.3.2 Hostel Owners

Hostel owners use the system to manage their accommodation information. They are expected to:

- Register and log in.
- Access the owner dashboard.
- Submit hostel information.
- Manage hostel details.
- Submit and manage room information.
- View bookings associated with their hostels.
- Manage booking records.

### 1.3.3 Administrators

Administrators oversee the platform and its activities. They are expected to:

- Access the administrator dashboard.
- Manage platform users.
- Oversee hostel listings.
- Monitor platform activities.
- Manage administrative operations.

## 1.4 Overall System Description

HostelConnect is a web-based application developed using PHP and MySQL/MariaDB.

The application follows a structured PHP project architecture, separating application functionality, configuration files, public entry points and user interfaces.

Users access the application through a web browser. The system processes user requests and communicates with the database to store and retrieve information.

The application provides different dashboards and access permissions based on the user's assigned role.

The application is initially developed and tested in a local XAMPP environment, with online deployment intended as a future project activity.

---

# 2.0 FUNCTIONAL REQUIREMENTS

Functional requirements describe the operations that HostelConnect is expected to perform.

## 2.1 User Management

The system shall support user account management and role-based access.

- **FR-01:** The system shall allow users to register accounts by providing the required personal and account information.
- **FR-02:** The system shall support three user roles: Student, Owner and Admin.
- **FR-03:** The system shall allow registered users to log in and log out.
- **FR-04:** The system shall provide dashboards appropriate to the user's assigned role.
- **FR-05:** The system shall restrict access to protected sections according to assigned user roles.
- **FR-06:** The system shall store user account information in the database.
- **FR-07:** The system shall allow users to manage relevant account information.

## 2.2 Hostel Discovery

The hostel discovery functionality shall allow students to explore accommodation information.

- **FR-08:** The system shall display hostel listings available on the platform.
- **FR-09:** The system shall associate hostel listings with their respective universities.
- **FR-10:** The system shall display relevant hostel information, including hostel name, location and description.
- **FR-11:** The system shall allow students to view individual hostel details.
- **FR-12:** The system shall display room information associated with selected hostels, including room number, room type and price.

## 2.3 Hostel and Room Management

This functionality shall allow hostel owners to submit and manage accommodation information.

- **FR-13:** The system shall allow hostel owners to submit hostel information.
- **FR-14:** The system shall associate each hostel with its registered owner.
- **FR-15:** The system shall associate each hostel with a supported university.
- **FR-16:** The system shall allow hostel owners to manage their hostel information.
- **FR-17:** The system shall allow hostel owners to submit room information.
- **FR-18:** The system shall maintain room details, including room number, room type, description and price.
- **FR-19:** The system shall associate each room with its respective hostel.
- **FR-20:** The system shall prevent duplicate room numbers within the same hostel.

## 2.4 Room Booking

The booking functionality shall allow students to reserve accommodation.

- **FR-21:** The system shall allow students to select rooms for booking.
- **FR-22:** The system shall record booking information in the database.
- **FR-23:** The system shall associate each booking with the relevant student and room.
- **FR-24:** The system shall record the start date and end date of each booking.
- **FR-25:** The system shall validate booking dates to ensure that the end date is later than the start date.
- **FR-26:** The system shall check room availability before accepting a booking request.
- **FR-27:** The system shall prevent overlapping confirmed bookings for the same room.
- **FR-28:** The system shall maintain booking statuses, including Pending, Confirmed, Cancelled and Completed.
- **FR-29:** The system shall allow students to view their booking records and statuses.
- **FR-30:** The system shall allow hostel owners to view bookings associated with their hostels.
- **FR-31:** The system shall support booking cancellation and status management through the available booking functionality.

## 2.5 Payment Management

The payment functionality shall maintain payment-related information associated with bookings.

- **FR-32:** The system shall maintain payment records associated with bookings.
- **FR-33:** The system shall record payment amounts and unique payment references.
- **FR-34:** The system shall maintain payment statuses, including Pending, Successful and Failed.
- **FR-35:** The system shall associate payment records with their corresponding bookings.
- **FR-36:** The system shall support the planned integration of Paystack Sandbox for simulated payment transactions.
- **FR-37:** Following successful integration, the system shall verify payment transactions before updating the corresponding payment status.

**Note:** FR-36 and FR-37 describe planned integration requirements. They must not be presented as completed functionality until the Paystack integration has been implemented and tested.

---

# 3.0 NON-FUNCTIONAL REQUIREMENTS

Non-functional requirements describe the quality and operational characteristics expected of the application.

## 3.1 Security

- The system shall require authentication before granting access to protected user areas.
- The system shall apply role-based access restrictions.
- The system shall use secure password-handling practices.
- The system shall restrict users from accessing functions outside their assigned permissions.
- Sensitive configuration information shall be protected.
- The application shall validate user inputs to reduce invalid or malicious data submissions.

## 3.2 Usability

- The system shall provide a user-friendly interface.
- The system shall provide separate dashboards for the different user roles.
- Navigation shall be understandable and consistent.
- The system shall present hostel, room and booking information clearly.
- The system shall provide understandable feedback for successful and unsuccessful operations.

## 3.3 Performance

- The system shall retrieve information from the database efficiently.
- The system shall process normal user requests without unnecessary delays.
- The system shall support multiple system operations under normal development and testing conditions.

## 3.4 Reliability

- The system shall maintain accurate user, hostel, room and booking records.
- The system shall maintain appropriate relationships between database records.
- The system shall preserve booking information during normal application operations.
- The system shall support appropriate booking status management.
- The system shall handle invalid booking requests appropriately.

## 3.5 Maintainability

- The application shall use an organised PHP project structure.
- Database configuration shall be separated from application functionality.
- The system shall use reusable application components where appropriate.
- Project source code shall be maintained through GitHub version control.
- Project documentation shall be updated to reflect significant system changes.

## 3.6 Compatibility

- The system shall be accessible through supported web browsers.
- The interface shall support commonly used desktop and mobile screen sizes.
- The application shall operate within its configured PHP and MySQL/MariaDB environment.

---

# 4.0 SYSTEM CONSTRAINTS

The development and operation of HostelConnect are subject to the following constraints:

1. The application is initially designed for AAMUSTED, KNUST and KTU.
2. The backend is developed using PHP.
3. The database uses MySQL/MariaDB.
4. XAMPP is used as the local development environment.
5. The initial implementation focuses on private student hostel accommodation.
6. The application requires a configured web server and database environment.
7. Accommodation pricing is intended to follow a fixed semester-based model rather than monthly or nightly charges.
8. Paystack Sandbox integration remains a planned external-service integration until completed and verified.
9. The system depends on accurate hostel, room and pricing information supplied by hostel owners.

---

# 5.0 ASSUMPTIONS

The project is based on the following assumptions:

1. Students and hostel owners have access to internet-enabled devices.
2. Hostel owners provide accurate hostel and room information.
3. Students provide valid registration information.
4. Hostel owners maintain accurate room availability information.
5. The selected universities have private hostels suitable for listing on the platform.
6. The system administrator is responsible for overseeing platform activities.
7. The application will be deployed online after the development and testing stages are completed.
8. Users have basic knowledge of operating web-based applications.

---

# 6.0 DATABASE REQUIREMENTS

HostelConnect uses a MySQL/MariaDB relational database to store and manage application information.

The current database structure consists of six tables.

| No. | Database Table | Purpose |
|---|---|---|
| 1 | `universities` | Stores supported university information. |
| 2 | `users` | Stores user account information and assigned roles. |
| 3 | `hostels` | Stores hostel information and ownership details. |
| 4 | `rooms` | Stores individual room information, prices and descriptions. |
| 5 | `bookings` | Stores student room booking information and booking statuses. |
| 6 | `payments` | Stores payment-related information associated with bookings. |

## 6.1 Users Table

The `users` table maintains user account information, including:

- User ID
- Full name
- Email address
- Phone number
- Password
- User role
- Account creation date

The role field supports Student, Owner and Admin accounts.

## 6.2 Universities Table

The `universities` table stores information about universities supported by the platform.

It includes:

- University ID
- University name
- Location

The table contains the university information required to associate hostels with their respective institutions.

## 6.3 Hostels Table

The `hostels` table maintains hostel information submitted by hostel owners.

It includes:

- Hostel ID
- University ID
- Owner ID
- Hostel name
- Location
- Description
- Contact number
- Creation date

The table uses foreign keys to associate each hostel with a university and a registered owner.

## 6.4 Rooms Table

The `rooms` table stores individual room information.

It includes:

- Room ID
- Hostel ID
- Room number
- Room type
- Price
- Description

Each room is associated with a hostel through its hostel ID.

The database also enforces unique room numbers within the same hostel and requires room prices to be greater than zero.

## 6.5 Bookings Table

The `bookings` table maintains student accommodation booking information.

It includes:

- Booking ID
- Student ID
- Room ID
- Start date
- End date
- Booking status
- Booking date

The booking status supports Pending, Confirmed, Cancelled and Completed.

Foreign keys associate each booking with the relevant student and room.

The database also requires the end date to be later than the start date.

## 6.6 Payments Table

The `payments` table maintains payment-related information associated with bookings.

It includes:

- Payment ID
- Booking ID
- Amount
- Payment reference
- Payment status
- Payment date

The payment status supports Pending, Successful and Failed.

Each payment record is associated with a booking through its booking ID.

## 6.7 Database Relationships

The database uses relational structures to associate users, universities, hostels, rooms, bookings and payments.

The main relationships are:

| Relationship | Description |
|---|---|
| Universities – Hostels | One university can have many hostels. |
| Users – Hostels | One hostel owner can manage multiple hostels. |
| Hostels – Rooms | One hostel can contain multiple rooms. |
| Users – Bookings | One student can make multiple bookings. |
| Rooms – Bookings | One room can have multiple booking records over time. |
| Bookings – Payments | One booking can have multiple payment records. |

Primary keys and foreign keys are used to maintain relationships and data integrity.

The database also includes constraints and indexes to support data consistency and efficient record retrieval.

---

# 7.0 SYSTEM ARCHITECTURE AND TECHNOLOGIES

## 7.1 System Architecture

HostelConnect follows a web-based client-server architecture.

The main components are:

**1. Presentation Layer**

Provides the user interface through which students, hostel owners and administrators interact with the application.

**2. Application Layer**

Processes user requests, authentication, hostel management, room management and booking operations using PHP.

**3. Database Layer**

Uses MySQL/MariaDB to store and retrieve application information.

## 7.2 Development Technologies

| Technology | Purpose |
|---|---|
| PHP | Server-side application development. |
| MySQL/MariaDB | Relational database management. |
| HTML | Webpage structure. |
| CSS | Interface styling and layout. |
| JavaScript | Client-side interactions. |
| XAMPP | Local development and testing environment. |
| GitHub | Source-code version control and team collaboration. |
| Paystack Sandbox | Planned simulated payment integration. |

---

# 8.0 USER ROLES AND ACCESS CONTROL

HostelConnect provides three main user roles.

| User Role | Responsibilities |
|---|---|
| Student | Register, log in, browse hostels, view rooms, make bookings and check booking status. |
| Hostel Owner | Submit and manage hostel information, manage rooms and view bookings associated with their hostels. |
| Administrator | Manage users, oversee hostel listings and monitor platform activities. |

The application uses role-based access control to ensure that users access the appropriate sections of the system.

---

# 9.0 SYSTEM LIMITATIONS AND FUTURE IMPROVEMENTS

The current implementation provides the foundation for a multi-university hostel booking platform. Some functionalities remain subject to further development, integration and testing.

Potential future improvements include:

1. Completing Paystack Sandbox integration and transaction verification.
2. Deploying the application to an online hosting environment.
3. Expanding automated and integration testing.
4. Introducing a dedicated notification system.
5. Enhancing reporting and administrative monitoring.
6. Improving application performance and security.
7. Extending support to additional universities.
8. Introducing a dedicated hostel approval workflow.
9. Enhancing room availability management.

---

Let's proceed with the next part of the **HostelConnect documentation: Use Case Specifications and Acceptance Criteria**.

These will extend the corrected SRS and provide a foundation for the UML diagrams. They are written around the existing six-table database structure and the agreed project scope.

# 10.0 Use Case Specifications

## 10.1 System Actors

| Actor | Description |
|---|---|
| Student | Registers, logs in, searches for hostels, views rooms, makes bookings and completes payments. |
| Hostel Owner | Registers, logs in, manages hostel information and room details, and views bookings associated with their hostels. |
| Administrator | Oversees system users and system activities according to assigned administrative permissions. |
| Paystack Sandbox | External payment service intended to process and verify test payments. |

**Important:** Paystack Sandbox is an intended external integration. Its actual implementation must be verified in the PHP source code.

## 10.2 Main Use Cases

| ID | Use Case | Primary Actor |
|---|---|---|
| UC-01 | Register Account | Student / Hostel Owner |
| UC-02 | Log In | Student / Hostel Owner / Administrator |
| UC-03 | Manage User Account | Student / Hostel Owner |
| UC-04 | Browse Hostels | Student |
| UC-05 | Search for Hostels | Student |
| UC-06 | View Hostel Details | Student |
| UC-07 | Manage Hostel Information | Hostel Owner |
| UC-08 | Manage Room Information | Hostel Owner |
| UC-09 | Check Room Availability | Student |
| UC-10 | Book a Room | Student |
| UC-11 | Make Payment | Student |
| UC-12 | Verify Payment | System / Paystack Sandbox |
| UC-13 | View Booking Details | Student / Hostel Owner |
| UC-14 | Manage Users | Administrator |

## 10.3 Fully Dressed Use Cases

### UC-01: Register Account

| Item | Description |
|---|---|
| Primary Actor | Student / Hostel Owner |
| Goal | Create an account on HostelConnect. |
| Preconditions | User does not already have an account with the same email address. |
| Trigger | User selects the registration option. |
| Main Flow | 1. User opens the registration page. 2. User enters their full name, email, phone number and password. 3. User selects an account role. 4. System validates the submitted information. 5. System checks whether the email already exists. 6. System securely stores the account information. 7. System confirms successful registration. |
| Alternative Flow | If the email already exists or the information is invalid, the system displays an appropriate error message. |
| Postconditions | A new user account is stored in the database. |

### UC-02: Log In

| Item | Description |
|---|---|
| Primary Actor | Student / Hostel Owner / Administrator |
| Goal | Access the system using valid credentials. |
| Preconditions | User has an existing account. |
| Trigger | User submits the login form. |
| Main Flow | 1. User opens the login page. 2. User enters email and password. 3. System validates the credentials. 4. System identifies the user's role. 5. System grants access to the appropriate interface. |
| Alternative Flow | Invalid credentials result in an error message. |
| Postconditions | An authenticated session is established. |

### UC-05: Search for Hostels

| Item | Description |
|---|---|
| Primary Actor | Student |
| Goal | Find hostels associated with a selected university or location. |
| Preconditions | Hostel records exist in the database. |
| Trigger | Student opens the hostel discovery page. |
| Main Flow | 1. Student enters a search term or selects a university. 2. System receives the search criteria. 3. System retrieves matching hostel records. 4. System displays the results. 5. Student selects a hostel to view its details. |
| Alternative Flow | If no matching hostels exist, the system displays a no-results message. |
| Postconditions | Matching hostel information is displayed. |

### UC-10: Book a Room

| Item | Description |
|---|---|
| Primary Actor | Student |
| Goal | Reserve an available hostel room for a specified period. |
| Preconditions | Student is logged in and the selected room exists. |
| Trigger | Student selects the booking option. |
| Main Flow | 1. Student selects a hostel. 2. Student selects a room. 3. Student enters the intended start and end dates. 4. System checks room availability. 5. System validates the booking dates. 6. System creates a booking with Pending status. 7. System displays the booking details. |
| Alternative Flow | If the room is unavailable or the dates are invalid, the system rejects the booking and displays an appropriate message. |
| Postconditions | A booking record is stored with its initial status. |

### UC-11: Make Payment

| Item | Description |
|---|---|
| Primary Actor | Student |
| Supporting Actor | Paystack Sandbox |
| Goal | Pay for a room booking. |
| Preconditions | Student has an existing booking. |
| Trigger | Student selects the payment option. |
| Main Flow | 1. Student selects a pending booking. 2. System determines the amount payable. 3. System initiates payment through the payment service. 4. Student completes the payment process. 5. System verifies the payment result. 6. System records the payment reference and status. 7. Booking status is updated according to the verified payment result. |
| Alternative Flow | If payment fails or verification is unsuccessful, the system records or displays the appropriate payment status. |
| Postconditions | Payment information is stored in the payments table. |

**Implementation note:** The actual payment flow, payment verification and booking-status update must be checked against the PHP code before being described as completed functionality.

# 11.0 Acceptance Criteria

Acceptance criteria define the conditions that must be satisfied for the system's core features to be considered acceptable.

| ID | Feature | Acceptance Criteria |
|---|---|---|
| AC-01 | Registration | The system must create an account when all required information is valid. |
| AC-02 | Registration | The system must reject duplicate email addresses. |
| AC-03 | Authentication | Users must be able to log in with valid credentials. |
| AC-04 | Authentication | Invalid login credentials must not grant access. |
| AC-05 | Role Management | Users must only access functions permitted by their assigned roles. |
| AC-06 | Hostel Discovery | Students must be able to view available hostel records. |
| AC-07 | Hostel Search | Search results must correspond to the supplied search criteria. |
| AC-08 | Hostel Management | Hostel owners must be able to manage their hostel information, subject to authorization. |
| AC-09 | Room Management | Hostel owners must be able to manage room information, subject to authorization. |
| AC-10 | Availability | The system must check existing bookings before accepting a new booking. |
| AC-11 | Booking | The system must reject invalid booking dates. |
| AC-12 | Booking | The system must prevent conflicting confirmed bookings for the same room. |
| AC-13 | Booking | New bookings must initially have a Pending status. |
| AC-14 | Pricing | The system must apply the agreed semester-based room pricing. |
| AC-15 | Payment | The system must store a unique payment reference. |
| AC-16 | Payment | The system must distinguish Pending, Successful and Failed payments. |
| AC-17 | Payment | Booking confirmation must depend on verified successful payment, where payment is required. |

---

# 12.0 CONCLUSION

HostelConnect is a PHP and MySQL/MariaDB-based web application developed to simplify the process of discovering and booking private student hostel accommodation around selected universities in Ghana.

The system is designed to support students, hostel owners and administrators through user management, hostel discovery, hostel and room management, semester-based accommodation booking and booking management.

The relational database provides structured storage for users, universities, hostels, rooms, bookings and payments.

The project provides a foundation for further improvements, including external payment integration, online deployment and expanded system testing.

This Software Requirements Specification documents the system scope, functional requirements, non-functional requirements, database structure and technologies associated with the HostelConnect project.

---
