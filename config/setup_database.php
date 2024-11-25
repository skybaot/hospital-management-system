<?php
require_once 'db_config.php';

// SQL to create doctors table
$sql_doctors = "CREATE TABLE IF NOT EXISTS doctors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    specialization VARCHAR(100) NOT NULL,
    experience INT NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    status ENUM('Active', 'Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

// SQL to create patients table
$sql_patients = "CREATE TABLE IF NOT EXISTS patients (
    patient_id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    address TEXT NOT NULL,
    contact_no VARCHAR(15) NOT NULL,
    date_of_birth DATE NOT NULL,
    consultant_name VARCHAR(100) NOT NULL,
    date_of_consultancy DATE NOT NULL,
    department VARCHAR(100) NOT NULL,
    consultancy_fees DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// SQL to create schedules table
$sql_schedules = "CREATE TABLE IF NOT EXISTS schedules (
    schedule_id INT AUTO_INCREMENT PRIMARY KEY,
    doctor_id INT NOT NULL,
    doctor_name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room_number VARCHAR(10) NOT NULL,
    max_patients INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
)";

// Execute the SQL statements
if ($conn->query($sql_doctors) === TRUE) {
    echo "Doctors table created successfully<br>";
} else {
    echo "Error creating doctors table: " . $conn->error . "<br>";
}

if ($conn->query($sql_patients) === TRUE) {
    echo "Patients table created successfully<br>";
} else {
    echo "Error creating patients table: " . $conn->error . "<br>";
}

if ($conn->query($sql_schedules) === TRUE) {
    echo "Schedules table created successfully<br>";
} else {
    echo "Error creating schedules table: " . $conn->error . "<br>";
}

$conn->close();
?>
