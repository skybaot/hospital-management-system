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
    $id = $data['id'];
    $name = $data['name'];
    $age = $data['age'];
    $dob = $data['dob'];
    $mobile = $data['mobile'];
    $specialization = $data['specialization'];
    $fees = $data['fees'];
    $experience = $data['experience'];
    
    // Validate required fields
    if (empty($id) || empty($name) || empty($specialization)) {
        $response["success"] = false;
        $response["message"] = "Required fields cannot be empty.";
        echo json_encode($response);
        exit;
    }
    
    // Prepare an insert statement
    $sql = "INSERT INTO doctors (id, name, age, dob, mobile, specialization, fees, experience) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "isisssdi", $id, $name, $age, $dob, $mobile, $specialization, $fees, $experience);
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $response["success"] = true;
            $response["message"] = "Doctor added successfully.";
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
