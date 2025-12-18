-- Create database
CREATE DATABASE IF NOT EXISTS travel_adda;
USE travel_adda;

-- =======================
-- Table: customers
-- =======================
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(100),
    password VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =======================
-- Table: admins
-- =======================
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) UNIQUE,
    email VARCHAR(100),
    password VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =======================
-- Table: agents
-- =======================
CREATE TABLE agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(15),
    employee_count INT,
    password VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =======================
-- Table: packages
-- =======================
CREATE TABLE packages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    agent_id INT,
    title VARCHAR(255),
    description TEXT,
    duration VARCHAR(50),
    start_date DATE,
    end_date DATE,
    price DECIMAL(10,2),
    status ENUM('active','inactive'),
    itinerary TEXT,
    image VARCHAR(255),
    image1 VARCHAR(255),
    image2 VARCHAR(255),
    image3 VARCHAR(255),
    image4 VARCHAR(255)
);

-- =======================
-- Table: bookings
-- =======================
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    package_id INT,
    booking_date DATETIME,
    status VARCHAR(50),
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

-- =======================
-- Table: feedback
-- =======================
CREATE TABLE feedback (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT,
    package_id INT,
    rating INT,
    comment TEXT,
    status ENUM('pending','approved','rejected'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);
-- Insert sample admin
INSERT INTO admins (username, email, password) VALUES ('admin', 'admin@traveladda.com', 'admin123');

-- Insert sample customer
INSERT INTO customers (username, email, password) VALUES ('john_doe', 'john@example.com', 'cust123');

-- Insert sample agent
INSERT INTO agents (company_name, email, phone, employee_count, password) 
VALUES ('Skyline Tours', 'agent@skyline.com', '9876543210', 5, 'agent123');
