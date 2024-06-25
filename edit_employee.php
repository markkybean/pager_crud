<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve POST data
    $recid = (int)$_POST["recid"];
    $fullName = htmlspecialchars($_POST["fullName"]);
    $address = htmlspecialchars($_POST["address"]);
    $birthdate = date_format(date_create($_POST["birthdate"]), "Y-m-d");
    $age = (int)$_POST["age"];
    $gender = htmlspecialchars($_POST["gender"]);
    $civilStatus = htmlspecialchars($_POST["civilStatus"]);
    $contactNumber = htmlspecialchars($_POST["contactNumber"]);
    $salary = floatval($_POST["salary"]);

    $isActive = isset($_POST['edit_txtfld']['isactive']) ? 1 : 0;


    // Prepare data for update
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

    // Build the SET part of the SQL query dynamically
    $setPart = "";
    foreach ($params as $key => $value) {
        $setPart .= "$key = :$key, ";
    }
    // Remove the trailing comma and space
    $setPart = rtrim($setPart, ", ");

    // Prepare the SQL statement with placeholders for parameters
    $query = "UPDATE employeefile SET $setPart WHERE recid = :recid";
    $stmt = $link_id->prepare($query); // Prepare the statement

    // Add the record ID to the parameters array
    $params["recid"] = $recid;

    try {
        // Execute the prepared statement with the parameters
        $stmt->execute($params);

        // Check if any rows were updated
        if ($stmt->rowCount() > 0) {
            $response = array('status' => 'success', 'message' => 'Employee updated successfully.');
        } else {
            // No rows were affected, indicating either no changes or invalid ID
            $response = array('status' => 'error', 'message' => 'No changes made or invalid employee ID.');
        }
    } catch (PDOException $e) {
        // Handle any errors that occurred during the execution
        $response = array('status' => 'error', 'message' => 'Database error. Please try again.');
    }

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // If request method is not POST, return an error response
    $response["status"] = "error";
    $response["message"] = "Invalid request method";

    // Send the JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
