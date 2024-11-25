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
    $patient_id = $data['patient_ID'];
    $name = $data['name'];
    $age = $data['age'];
    $gender = $data['gender'];
    $address = $data['address'];
    $contact_no = $data['contact_no'];
    $date_of_birth = $data['date_of_birth'];
    $consultant_name = $data['consultant_name'];
    $date_of_consultancy = $data['date_of_consultancy'];
    $department = $data['department'];
    $consultancy_fees = $data['consultancy_fees'];
    
    // Validate required fields
    if (empty($patient_id) || empty($name) || empty($age) || empty($gender)) {
        $response["success"] = false;
        $response["message"] = "Required fields cannot be empty.";
        echo json_encode($response);
        exit;
    }
    
    // Prepare an insert statement
    $sql = "INSERT INTO patients (patient_id, name, age, gender, address, contact_no, date_of_birth, 
            consultant_name, date_of_consultancy, department, consultancy_fees) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "isisssssssd", $patient_id, $name, $age, $gender, $address, 
                             $contact_no, $date_of_birth, $consultant_name, $date_of_consultancy, 
                             $department, $consultancy_fees);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $response["success"] = true;
            $response["message"] = "Patient added successfully.";
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
