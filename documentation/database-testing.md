# HOSTELCONNECT
## DATABASE TESTING AND SQL VERIFICATION REPORT

### 1. Introduction

Database testing was conducted to verify the structure, relationships and integrity of the HostelConnect database.

The testing focused on confirming that the six database tables exist, their structures are correctly defined and their foreign-key relationships correspond to the database design.

### 2. Testing Objectives

The objectives were to:

1. Confirm that the `hostelconnect_db` database exists.
2. Verify the existence of all six database tables.
3. Examine the columns and data types of each table.
4. Verify primary-key definitions.
5. Verify foreign-key relationships.
6. Confirm that the database structure agrees with the database design documentation.

### 3. Testing Environment

| Component | Description |
|---|---|
| Database | hostelconnect_db |
| DBMS | MySQL |
| Development Environment | XAMPP |
| Database Administration Tool | phpMyAdmin |
| Application Language | PHP |
| Database Connection | PDO |

### 4. Database Testing Procedures

The following SQL commands were used to examine the database.

**Test 1: Database Table Verification**

```sql
USE hostelconnect_db;
SHOW TABLES;
```

Purpose: To confirm that all six required tables exist.

**Expected result:** Six tables should be displayed.

**Test 2: Table Structure Verification**

```sql
DESCRIBE universities;
DESCRIBE users;
DESCRIBE hostels;
DESCRIBE rooms;
DESCRIBE bookings;
DESCRIBE payments;
```

Purpose: To examine the columns, data types, nullability, keys and default values of each table.

**Expected result:** The table structures should correspond to the approved database schema.

**Test 3: Foreign-Key Verification**

```sql
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'hostelconnect_db'
AND REFERENCED_TABLE_NAME IS NOT NULL
ORDER BY TABLE_NAME;
```

Purpose: To identify and verify relationships established through foreign keys.

**Expected result:** The query should display the expected foreign-key relationships.

### 5. Database Test Results

Based on the verification performed in phpMyAdmin, the following results were confirmed.

| Test ID | Test Description | Expected Result | Reported Result |
|---|---|---|---|
| DB-01 | Database table verification | Six tables exist | Passed |
| DB-02 | Universities structure | Correct columns and data types | Passed |
| DB-03 | Users structure | Correct columns and data types | Passed |
| DB-04 | Hostels structure | Correct columns and data types | Passed |
| DB-05 | Rooms structure | Correct columns and data types | Passed |
| DB-06 | Bookings structure | Correct columns and data types | Passed |
| DB-07 | Payments structure | Correct columns and data types | Passed |
| DB-08 | Foreign-key verification | Expected relationships exist | Passed |

**Result:** All eight structural verification checks were reported as successful.

### 6. Verified Database Relationships

The following relationships were confirmed:

| No. | Parent Table | Child Table | Foreign-Key Relationship |
|---|---|---|---|
| 1 | universities | hostels | university_id |
| 2 | users | hostels | owner_id |
| 3 | hostels | rooms | hostel_id |
| 4 | users | bookings | student_id |
| 5 | rooms | bookings | room_id |
| 6 | bookings | payments | booking_id |

### 7. Database Integrity Considerations

The database uses several integrity mechanisms:

- Primary keys for unique record identification.
- Foreign keys for maintaining relationships.
- Unique constraints for preventing duplicate values.
- NOT NULL constraints for required information.
- CHECK constraints for applicable value validation.
- ENUM fields for predefined roles and statuses.

Further application-level testing is required to establish that the PHP application correctly handles invalid inputs, conflicting bookings and payment verification.

### 8. Conclusion

The database verification exercise confirmed that the HostelConnect database contains the six required tables and that their structures and foreign-key relationships correspond to the documented database design.

The results provide evidence that the database foundation is correctly structured for the system's core operations.

Additional testing of application-level functionality, booking transactions and payment processing remains necessary.

---