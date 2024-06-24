<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve POST data
    $fullName = htmlspecialchars($_POST["fullName"]);
    $address = htmlspecialchars($_POST["address"]);
    $birthdate = date_format(date_create($_POST["birthdate"]), "Y-m-d");
    $age = (int)$_POST["age"];
    $gender = htmlspecialchars($_POST["gender"]);
    $civilStatus = htmlspecialchars($_POST["civilStatus"]);
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $salary = floatval($_POST["salary"]);
    $isActive = isset($_POST["isactive"]) ? 1 : 0;

    // Prepare data for insertion
    $params = array(
        "fullname" => $fullName,
        "address" => $address,
        "birthdate" => $birthdate,
        "age" => $age,
        "gender" => $gender,
        "civilstat" => $civilStatus,
        "contactnum" => $contactNumber,
        "salary" => $salary,
        "isactive" => $isActive
    );

    // Insert data into database
    $success = PDO_InsertRecord($link_id, "employeefile", $params, false);

    // Check if insertion was successful
    if ($success) {
        $response["status"] = "success";
        $response["message"] = "Employee added successfully";
    } else {
        $response["status"] = "error";
        $response["message"] = "Failed to add employee";
    }
} else {
    // If request method is not POST
    $response["status"] = "error";
    $response["message"] = "Invalid request method";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>

