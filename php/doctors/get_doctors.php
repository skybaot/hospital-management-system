<?php
require_once __DIR__ . '/../models/Doctor.php';
require_once '../../config/db_config.php';
header('Content-Type: application/json');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        Response::error('Method not allowed', 405);
    }
    
    $doctor = new Doctor();
    
    // Handle search query if present
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $doctors = $doctor->searchDoctors($_GET['search']);
    } 
    // Handle single doctor fetch if ID is provided
    else if (isset($_GET['id'])) {
        $doctorData = $doctor->getDoctorById($_GET['id']);
        if (!$doctorData) {
            Response::notFound('Doctor not found');
        }
        Response::success([$doctorData]);
    }
    // Get all doctors
    else {
        $doctors = $doctor->getAllDoctors();
    }
    
    Response::success($doctors);
} catch (Exception $e) {
    Response::error($e->getMessage());
}

mysqli_close($conn);
?>
