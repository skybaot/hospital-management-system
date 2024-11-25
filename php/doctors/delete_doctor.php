<?php
require_once __DIR__ . '/../models/Doctor.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
        Response::error('Method not allowed', 405);
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id'])) {
        Response::error('Invalid JSON data or missing doctor ID');
    }
    
    $doctor = new Doctor();
    if ($doctor->deleteDoctor($data['id'])) {
        Response::success(null, 'Doctor deleted successfully');
    } else {
        Response::error('Failed to delete doctor');
    }
} catch (Exception $e) {
    Response::error($e->getMessage());
}
?>