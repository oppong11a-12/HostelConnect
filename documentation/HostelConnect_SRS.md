HOSTELCONNECT
Software Requirements Specification (SRS)
Project Title: Design and Implementation of a Multi-University Web-Based Student Hostel Booking System in Ghana.

Project Name: HostelConnect

Tagline: Connecting Students to Their Ideal Accommodation.

Project Type: Web-Based Application

Target Universities: AAMUSTED, KNUST and KTU.

## 1.0 Introduction
## 1. Purpose
The purpose of HostelConnect is to develop a web-based student hostel booking system that enables students to discover available hostels, check room availability and make reservations online.

The system will also provide hostel owners with tools to manage their hostel information and bookings, while administrators oversee the platform.

## 1.2 Scope
HostelConnect will provide a central platform connecting students with private hostel accommodation around selected universities in Ghana.

The initial implementation will cover:
- University of Skills Training and Entrepreneurial Development (USTED).
- Kwame Nkrumah University of Science and Technology (KNUST).
- Kumasi Technical University (KTU).

The system will focus on four core features:
1. User Management.
2. Hostel Discovery.
3. Room Booking.
4. Payment and Booking Confirmation.
The system will use Paystack Sandbox for simulated payment transactions. It will not process real payments during development and demonstration.

## 1.3 Intended Users
The system will have three main categories of users:

- Students: Register, log in, search for hostels, view hostel details, check room availability and make bookings.

- Hostel Owners: Register, manage hostel information, manage room availability and view bookings.

- Administrators: Manage platform users, oversee hostel listings and monitor system activities.

## 1.4 Overall System Description
HostelConnect will operate as a web-based application accessible through computers, tablets and smartphones with internet access.

Users will interact with the system through a web interface. The application will communicate with a MySQL database to store and retrieve information. Paystack Sandbox will provide the external payment testing service.


## 2. Functional Requirements
Functional requirements describe what the system must do.

## 2.1 User Management
- FR1: The system shall allow students to create accounts.
- FR2: The system shall allow hostel owners to register.
- FR3: The system shall allow registered users to log in and log out.
- FR4: The system shall provide role-based access for students, hostel owners and administrators.
- FR5: The system shall allow users to manage their account information.

## 2.2 Hostel Discovery
- FR6: The system shall display available hostels.
- FR7: The system shall allow students to search for hostels by name or location.
- FR8: The system shall allow students to filter hostels by university.
- FR9: The system shall display hostel details, including descriptions, prices, locations and available rooms.

## 2.3 Room Booking
- FR10: The system shall display available rooms in registered hostels.
- FR11: The system shall allow students to select a room for booking.
- FR12: The system shall check room availability before confirming a reservation.
- FR13: The system shall record booking information in the database.
- FR14: The system shall allow students to view their booking status.
- FR15: The system shall allow hostel owners to view and manage bookings.

## 2.4 Payment and Booking Confirmation
- FR16: The system shall allow students to initiate payment for a booking.
- FR17: The system shall integrate with Paystack Sandbox for test transactions.
- FR18: The system shall verify payment results before marking a booking as paid.
- FR19: The system shall display payment and booking confirmation information.
- FR20: The system shall maintain payment transaction records.


## 3. Non-Functional Requirements
These describe the quality and performance expected of the system.
## 3.1 Security
- The system shall store user passwords using a secure password-hashing algorithm and shall never store passwords in plain text.
- Users shall only access functions permitted by their roles.
- Payment verification shall be performed securely.

## 3.2 Usability
- The system shall have a simple and user-friendly interface.
- The application shall support mobile and desktop screens.

## 3.3 Performance
- The system shall retrieve and display hostel search results within an acceptable response time under normal operating conditions.
- The system shall process booking requests without unnecessary delays.

## 3.4 Reliability
- The system shall maintain accurate booking records.
- The system shall prevent two students from successfully booking the same unavailable room for overlapping periods.

## 3.5 Maintainability
- The application shall use organised modules and reusable code.
- The database shall use appropriate relationships and constraints.

## 4. System Constraints
- The application will initially support AAMUSTED, KNUST and KTU.
- The application will use PHP and MySQL.
- Paystack Sandbox will be used for simulated payments.
- Internet access will be required for online access and external payment testing.
- The initial implementation will focus on private student hostels rather than official university accommodation allocation.

## 5. Assumptions
- Students and hostel owners will have access to internet-enabled devices.
- Hostel owners will provide accurate hostel and room information.
- Students will provide valid registration information.
- Paystack Sandbox will be available during payment testing.
- The initial system will contain hostel information for the three selected universities.

## 6. Database Requirements
The database must maintain:
- User information.
- University information.
- Hostel information.
- Room information.
- Booking records.
- Payment transactions.