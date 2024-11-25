<?php
require_once '../config/db_config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $response = array();
    
    // Get JSON data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if ($data === null) {
        $response["success"] = false;
        $response["message"] = "Invalid JSON data received.";
        echo json_encode($response);
        exit;
    }
    
    // Collect input data
    $doctor_id = $data['doctor_id'];
    $doctor_name = $data['doctor_name'];
    $department = $data['department'];
    $day_of_week = $data['day'];
    $start_time = $data['start_time'];
    $end_time = $data['end_time'];
    $room_number = $data['room_number'];
    $max_patients = $data['max_patients'];
    
    // Validate required fields
    if (empty($doctor_id) || empty($doctor_name) || empty($department) || empty($day_of_week)) {
        $response["success"] = false;
        $response["message"] = "Required fields cannot be empty.";
        echo json_encode($response);
        exit;
    }
    
    // Prepare an insert statement
    $sql = "INSERT INTO schedules (doctor_id, doctor_name, department, day_of_week, start_time, 
            end_time, room_number, max_patients) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "issssssi", $doctor_id, $doctor_name, $department, $day_of_week,
                             $start_time, $end_time, $room_number, $max_patients);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $response["success"] = true;
            $response["message"] = "Schedule added successfully.";
        } else {
            $response["success"] = false;
            $response["message"] = "Error: " . mysqli_error($conn);
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        $response["success"] = false;
        $response["message"] = "Error: Could not prepare query.";
    }
    
    // Close connection
    mysqli_close($conn);
    
    echo json_encode($response);
} else {
    $response["success"] = false;
    $response["message"] = "Invalid request method.";
    echo json_encode($response);
}
?>
