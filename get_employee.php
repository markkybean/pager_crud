<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve POST data
    $recid = (int)$_POST["recid"];

    // Prepare and execute SQL statement to fetch employee details
    $query = "SELECT * FROM employeefile WHERE recid = ?";
    $stmt = $link_id->prepare($query);
    $stmt->execute([$recid]);

    // Fetch employee data
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if employee data was found
    if ($employee) {
        // Prepare JSON response
        $response = array(
            'recid' => $employee['recid'],
            'fullname' => $employee['fullname'],
            'address' => $employee['address'],
            'birthdate' => $employee['birthdate'],
            'age' => $employee['age'],
            'gender' => $employee['gender'],
            'civilstat' => $employee['civilstat'],
            'contactnum' => $employee['contactnum'],
            'salary' => $employee['salary'],
            'isactive' => $employee['isactive']
        );

        // Send JSON response
        echo json_encode($response);
    } else {
        // Employee not found
        echo json_encode(array('error' => 'Employee not found.'));
    }
} else {
    // If request method is not POST
    echo json_encode(array('error' => 'Invalid request method'));
}
?>
