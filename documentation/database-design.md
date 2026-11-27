# HOSTELCONNECT
## DATABASE DESIGN DOCUMENTATION

### 1. Introduction

#### 1.1 Overview

HostelConnect is a web-based hostel booking management system designed to connect students with private hostel accommodation around selected universities in Ghana.

The system provides a centralized platform where students can discover hostels, view room information, make accommodation bookings and manage payment records. Hostel owners can manage their hostel and room information, while administrators oversee system operations according to their assigned permissions.

The database provides the foundation for storing and managing the information required by the system.

#### 1.2 Purpose of the Database

The purpose of the HostelConnect database is to:

- Store user account information.
- Maintain university records.
- Store hostel information.
- Manage room information and prices.
- Record student accommodation bookings.
- Maintain payment transactions and their statuses.
- Establish relationships between related records.
- Maintain data integrity and consistency.

### 2. Database Overview

| Property | Description |
|---|---|
| Database Name | `hostelconnect_db` |
| Database Management System | MySQL |
| Development Environment | XAMPP |
| Server-side Language | PHP |
| Database Access | PHP Data Objects (PDO) |
| Number of Tables | 6 |
| Primary Storage Model | Relational Database |

The database consists of six interconnected tables:

1. `universities`
2. `users`
3. `hostels`
4. `rooms`
5. `bookings`
6. `payments`

### 3. Database Objectives

The database was designed to achieve the following objectives:

1. Provide centralized storage for HostelConnect information.
2. Reduce unnecessary duplication of records.
3. Maintain relationships between universities, users, hostels, rooms, bookings and payments.
4. Support efficient retrieval of hostel and room information.
5. Maintain data accuracy through primary keys, foreign keys and constraints.
6. Support the booking and payment management processes.
7. Provide a foundation for future system improvements.

### 4. Database Tables and Descriptions

#### 4.1 Universities Table

**Table Name:** `universities`

**Purpose:** Stores information about universities supported by HostelConnect.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| university_id | INT | Primary Key, Auto Increment | Unique university identifier |
| university_name | VARCHAR(150) | NOT NULL, UNIQUE | Name of the university |
| location | VARCHAR(150) | NOT NULL | University location |

#### 4.2 Users Table

**Table Name:** `users`

**Purpose:** Stores account information for students, hostel owners and administrators.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| user_id | INT | Primary Key, Auto Increment | Unique user identifier |
| full_name | VARCHAR(150) | NOT NULL | User's full name |
| email | VARCHAR(150) | NOT NULL, UNIQUE | User's email address |
| phone_number | VARCHAR(20) | NULL allowed | User's contact number |
| password | VARCHAR(255) | NOT NULL | User password or stored password hash |
| role | ENUM | NOT NULL | Student, Owner or Admin |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation date and time |

#### 4.3 Hostels Table

**Table Name:** `hostels`

**Purpose:** Stores information about hostels registered on the platform.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| hostel_id | INT | Primary Key, Auto Increment | Unique hostel identifier |
| university_id | INT | Foreign Key, NOT NULL | References universities |
| owner_id | INT | Foreign Key, NOT NULL | References users |
| hostel_name | VARCHAR(150) | NOT NULL | Name of the hostel |
| location | VARCHAR(200) | NOT NULL | Hostel location |
| description | TEXT | NULL allowed | Hostel description |
| contact_number | VARCHAR(20) | NULL allowed | Hostel contact number |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Record creation date and time |

#### 4.4 Rooms Table

**Table Name:** `rooms`

**Purpose:** Stores information about rooms available within registered hostels.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| room_id | INT | Primary Key, Auto Increment | Unique room identifier |
| hostel_id | INT | Foreign Key, NOT NULL | References hostels |
| room_number | VARCHAR(30) | NOT NULL | Room identification number |
| room_type | VARCHAR(80) | NOT NULL | Room category |
| price | DECIMAL(10,2) | NOT NULL | Room price |
| description | TEXT | NULL allowed | Additional room information |

#### 4.5 Bookings Table

**Table Name:** `bookings`

**Purpose:** Records accommodation reservations made by students.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| booking_id | INT | Primary Key, Auto Increment | Unique booking identifier |
| student_id | INT | Foreign Key, NOT NULL | References users |
| room_id | INT | Foreign Key, NOT NULL | References rooms |
| start_date | DATE | NOT NULL | Booking start date |
| end_date | DATE | NOT NULL | Booking end date |
| booking_status | ENUM | NOT NULL, DEFAULT Pending | Pending, Confirmed, Cancelled or Completed |
| booking_date | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Booking creation date and time |

#### 4.6 Payments Table

**Table Name:** `payments`

**Purpose:** Stores payment records associated with accommodation bookings.

| Column | Data Type | Constraint | Description |
|---|---|---|---|
| payment_id | INT | Primary Key, Auto Increment | Unique payment identifier |
| booking_id | INT | Foreign Key, NOT NULL | References bookings |
| amount | DECIMAL(10,2) | NOT NULL | Payment amount |
| payment_reference | VARCHAR(100) | NOT NULL, UNIQUE | Unique payment reference |
| payment_status | ENUM | NOT NULL, DEFAULT Pending | Pending, Successful or Failed |
| payment_date | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Payment record creation date and time |

### 5. Primary Keys and Foreign Keys

Primary keys uniquely identify records in each table, while foreign keys establish relationships between related tables.

| Table | Primary Key | Foreign Key |
|---|---|---|
| universities | university_id | None |
| users | user_id | None |
| hostels | hostel_id | university_id, owner_id |
| rooms | room_id | hostel_id |
| bookings | booking_id | student_id, room_id |
| payments | payment_id | booking_id |

### 6. Entity Relationships

The database contains the following relationships:

| Relationship | Cardinality | Explanation |
|---|---|---|
| Universities – Hostels | One-to-Many | One university can have multiple hostels. |
| Users – Hostels | One-to-Many | One hostel owner can manage multiple hostels. |
| Hostels – Rooms | One-to-Many | One hostel can contain multiple rooms. |
| Users – Bookings | One-to-Many | One student can make multiple bookings. |
| Rooms – Bookings | One-to-Many | One room can have multiple booking records over time. |
| Bookings – Payments | One-to-Many | One booking can have multiple payment records. |

These relationships are implemented through foreign-key constraints.

### 7. Database Integrity Constraints

The database uses several constraints to maintain data accuracy and consistency.

| Constraint | Application |
|---|---|
| PRIMARY KEY | Ensures each record has a unique identifier. |
| FOREIGN KEY | Maintains relationships between tables. |
| NOT NULL | Prevents required fields from being empty. |
| UNIQUE | Prevents duplicate university names, email addresses, room numbers within a hostel and payment references. |
| CHECK | Ensures room prices and payment amounts are positive and booking end dates are later than start dates. |
| DEFAULT | Automatically assigns default booking and payment statuses and timestamps. |
| ENUM | Restricts roles and statuses to predefined values. |

**Additional database rules:**

- A room number must be unique within its hostel.
- A user's email address must be unique.
- A payment reference must be unique.
- Booking end dates must be later than booking start dates.
- Room prices must be greater than zero.
- Payment amounts must be greater than zero.

### 8. Database Normalization

Normalization is the process of organizing database tables to reduce unnecessary data duplication and improve data integrity.

The HostelConnect database uses a relational structure that separates information into related tables.

#### 8.1 First Normal Form (1NF)

A table satisfies First Normal Form when:

- Each column contains atomic values.
- Each record can be uniquely identified.
- There are no repeating groups of attributes.

The HostelConnect tables use individual columns to store specific values, and each table has a primary key.

#### 8.2 Second Normal Form (2NF)

A table satisfies Second Normal Form when:

- It is already in First Normal Form.
- Every non-key attribute depends on the whole candidate key.

The HostelConnect tables use single-column primary keys. Their non-key attributes are designed to describe the record identified by the primary key.

#### 8.3 Third Normal Form (3NF)

A table satisfies Third Normal Form when:

- It is already in Second Normal Form.
- Non-key attributes do not depend transitively on other non-key attributes.

The database separates university, user, hostel, room, booking and payment information into different tables. This reduces unnecessary duplication and supports independent management of related records.

---

### 9. Database Security and Access Control

Database security is important because HostelConnect stores user account information, booking details and payment records.

The following security measures are relevant to the system:

1. User authentication should be required before accessing protected features.
2. Role-based authorization should restrict access according to user roles.
3. Passwords should be stored using secure password hashing.
4. PDO prepared statements should be used to reduce SQL injection risks.
5. Sensitive payment information should be handled securely.
6. Database credentials should not be exposed publicly.
7. Access to administrative operations should be restricted.

---

### 10. Conclusion

The HostelConnect database provides a structured foundation for managing university, user, hostel, room, booking and payment information.

The six-table relational design establishes the relationships required to support the system's main operations. Primary keys, foreign keys, unique constraints, validation rules and normalization principles contribute to data consistency and reliability.

The database is designed to support the development and future improvement of the HostelConnect hostel booking platform.

---