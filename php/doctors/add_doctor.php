<?php
require_once __DIR__ . '/../models/Doctor.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        Response::error('Method not allowed', 405);
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        Response::error('Invalid JSON data');
    }
    
    $doctor = new Doctor();
    if ($doctor->createDoctor($data)) {
        Response::success(null, 'Doctor added successfully');
    } else {
        Response::error('Failed to add doctor');
    }
} catch (Exception $e) {
    Response::error($e->getMessage());
}
?>
