# Hospital Management System

A comprehensive web-based Hospital Management System built with PHP, MySQL, and modern frontend technologies.

## Setup Instructions

1. Install XAMPP:
   - Download and install XAMPP from https://www.apachefriends.org/
   - Make sure Apache and MySQL services are running

2. Database Setup:
   - Copy all files to your XAMPP htdocs directory
   - Navigate to http://localhost/hospital-management/config/init.php
   - This will create the database and necessary tables

3. Access the Application:
   - Open http://localhost/hospital-management in your web browser
   - Use the following test credentials:
     * Admin: username: admin, password: admin123
     * Doctor: username: doctor, password: doctor123
     * Nurse: username: nurse, password: nurse123

## Features

- Multi-role user authentication (Admin, Doctor, Nurse)
- Doctor Management
- Patient Management
- Schedule Management
- Modern responsive dashboard
- Form validation and error handling
- Secure database operations

## Directory Structure

```
hospital-management/
├── config/
│   ├── db_config.php
│   ├── init.php
│   └── setup_database.php
├── js/
│   └── forms.js
├── php/
│   ├── add_doctor.php
│   ├── add_patient.php
│   └── add_schedule.php
├── index.html
└── README.md
```

## Technology Stack

- Frontend:
  * HTML5
  * Tailwind CSS
  * JavaScript
  * SweetAlert2 for notifications
  * Font Awesome icons

- Backend:
  * PHP
  * MySQL
  * PDO for database operations

## Security Features

- Prepared statements to prevent SQL injection
- Input validation
- Secure password handling
- Role-based access control
