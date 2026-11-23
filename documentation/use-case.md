# 4.2 HOSTELCONNECT USE CASE DIAGRAM

## 4.2.1 Introduction

The Use Case Diagram is a UML diagram that illustrates how different users interact with the HostelConnect system. It identifies the users of the system, the functions available to them and the relationship between users and those functions.

The diagram provides a general overview of the activities that can be performed within HostelConnect. It also helps the development team understand the responsibilities of each user and the services the system is expected to provide.

HostelConnect has three main actors: **Student, Hostel Owner and Administrator.**

## 4.2.2 Actors of the System

### 1. Student

The student is the primary user of HostelConnect. Students use the platform to search for accommodation around their preferred universities and make room bookings.

The student is expected to perform the following activities:

- Register an account.
- Log in and log out.
- Search for available hostels.
- Filter hostels according to university.
- View hostel information.
- View available rooms and semester prices.
- Select a suitable room.
- Make a room booking.
- Initiate payment through Paystack Sandbox.
- View booking and payment status.

### 2. Hostel Owner

The hostel owner is responsible for providing accommodation information through the platform. The owner uses HostelConnect to manage hostel details, room information and student bookings.

The hostel owner is expected to perform the following activities:

- Register an account.
- Log in and log out.
- Add hostel information.
- Update hostel details.
- Add and manage rooms.
- Update room availability.
- View student bookings.
- Manage booking information.

### 3. Administrator

The administrator oversees the general operation of HostelConnect. The administrator helps ensure that the platform and its users are properly managed.

The administrator is expected to perform the following activities:

- Log in and log out.
- Manage user accounts.
- Monitor hostel listings.
- Oversee system activities.


## 4.2.3 Explanation of the Use Case Diagram

The diagram illustrates how the three main actors interact with the HostelConnect system.

The large rectangle represents the system boundary. It separates the functions provided by HostelConnect from the external users who interact with the platform.

The stick figures represent the actors, while the ovals represent the different functions available within the system. The connecting lines show which activities each actor can perform.

The functions are organised into five major areas.

![HostelConnect Use Case Diagram](use-case-diagram.png)

### A. User Management

This section deals with account creation and access to the platform.

Students and hostel owners can register accounts, log in and log out. Administrators can also log in and manage user accounts.

The purpose of this section is to ensure that users can access the system according to their assigned roles.

### B. Hostel Discovery

This section allows students to find suitable accommodation.

Students can search for hostels, filter the available listings according to their preferred university, view hostel details and check available rooms.

For example, a student from KNUST can search for hostels associated with KNUST and examine the available accommodation options.

### C. Room Booking

This section handles the process of reserving accommodation.

Students can select a room and make a booking. Before the booking is accepted, the system checks whether the selected room is available.

Students can also view the status of their bookings.

### D. Payment and Confirmation

This section represents the payment process.

After making a booking, the student can initiate payment through Paystack Sandbox. The system verifies the payment response before updating the payment status.

The Sandbox integration is intended for simulated transactions during development and demonstration.

Students can subsequently view their payment status.

### E. Hostel Management

This section provides functions for hostel owners and administrators.

Hostel owners can manage hostel information, update room details and view student bookings.

Administrators can monitor hostel listings and oversee activities on the platform.

## 4.2.4 Explanation of the Use Case Relationships

The diagram contains two `<<include>>` relationships.

**1. Make Booking includes Check Room Availability**

This relationship indicates that checking room availability is a required part of the booking process.

For example, when a student selects a room, the system checks whether it is available before proceeding with the booking.

This helps prevent multiple students from successfully reserving the same room for an overlapping booking period.

**2. Initiate Payment includes Verify Payment**

This relationship indicates that payment verification forms part of the payment process.

When a student initiates payment, the system must verify the transaction response before treating the payment as successful.

This helps maintain accurate payment records and prevents unverified transactions from being treated as completed payments.

## 4.2.5 Importance of the Use Case Diagram to HostelConnect

The Use Case Diagram provides a clear understanding of the functions and responsibilities within HostelConnect.

It helps the development team identify the services that must be provided to students, hostel owners and administrators.

It also supports software testing because each use case can be used to identify activities that need to be tested. For example, the tester can verify whether students can search for hostels, whether room availability is checked during booking and whether payment verification works correctly.

The diagram also helps ensure that the system's user roles and functional requirements are properly represented in the project documentation.

## 4.2.6 Conclusion

The Use Case Diagram provides an overview of how students, hostel owners and administrators interact with HostelConnect. It identifies the major system functions and illustrates the relationship between the users and the services provided by the platform.

It therefore serves as an important design document that guides system development, implementation and testing.