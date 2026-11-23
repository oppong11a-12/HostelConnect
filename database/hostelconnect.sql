CREATE DATABASE IF NOT EXISTS hostelconnect_db;

USE hostelconnect_db;

-- 1. UNIVERSITIES
CREATE TABLE universities (
    university_id INT AUTO_INCREMENT PRIMARY KEY,
    university_name VARCHAR(150) NOT NULL UNIQUE,
    location VARCHAR(150) NOT NULL
) ENGINE=InnoDB;


-- 2. USERS
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone_number VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    role ENUM('Student', 'Owner', 'Admin') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- 3. HOSTELS
CREATE TABLE hostels (
    hostel_id INT AUTO_INCREMENT PRIMARY KEY,
    university_id INT NOT NULL,
    owner_id INT NOT NULL,
    hostel_name VARCHAR(150) NOT NULL,
    location VARCHAR(200) NOT NULL,
    description TEXT,
    contact_number VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_hostel_university
        FOREIGN KEY (university_id)
        REFERENCES universities(university_id),

    CONSTRAINT fk_hostel_owner
        FOREIGN KEY (owner_id)
        REFERENCES users(user_id)
) ENGINE=InnoDB;


-- 4. ROOMS
CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    hostel_id INT NOT NULL,
    room_number VARCHAR(30) NOT NULL,
    room_type VARCHAR(80) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,

    CONSTRAINT fk_room_hostel
        FOREIGN KEY (hostel_id)
        REFERENCES hostels(hostel_id),

    CONSTRAINT uq_hostel_room
        UNIQUE (hostel_id, room_number),

    CONSTRAINT chk_room_price
        CHECK (price > 0)
) ENGINE=InnoDB;


-- 5. BOOKINGS
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    room_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    booking_status ENUM(
        'Pending',
        'Confirmed',
        'Cancelled',
        'Completed'
    ) NOT NULL DEFAULT 'Pending',
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_booking_student
        FOREIGN KEY (student_id)
        REFERENCES users(user_id),

    CONSTRAINT fk_booking_room
        FOREIGN KEY (room_id)
        REFERENCES rooms(room_id),

    CONSTRAINT chk_booking_dates
        CHECK (end_date > start_date),

    INDEX idx_booking_room_dates (room_id, start_date, end_date)
) ENGINE=InnoDB;


-- 6. PAYMENTS
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_reference VARCHAR(100) NOT NULL UNIQUE,
    payment_status ENUM(
        'Pending',
        'Successful',
        'Failed'
    ) NOT NULL DEFAULT 'Pending',
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_payment_booking
        FOREIGN KEY (booking_id)
        REFERENCES bookings(booking_id),

    CONSTRAINT chk_payment_amount
        CHECK (amount > 0)
) ENGINE=InnoDB;


-- INITIAL UNIVERSITY RECORDS
INSERT INTO universities (university_name, location)
VALUES
('USTED', 'Kumasi, Ghana'),
('KNUST', 'Kumasi, Ghana'),
('KTU', 'Koforidua, Ghana');

UPDATE universities
SET university_name = 'AAMUSTED'
WHERE university_name = 'USTED';