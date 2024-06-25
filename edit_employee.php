<?php
// Include the database connection file
require_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the form data has been submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
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
        $isActive = isset($_POST["isactive"]) ? 1 : 0;

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

        // Update criteria
        $where = array("recid" => $recid);

        // Update data in database using PDO prepared statement
        $success = PDO_UpdateRecord($link_id, "employeefile", $params, $where);

        // Check if update was successful
        if ($success) {
            $response["status"] = "success";
            $response["message"] = "Employee updated successfully";
        } else {
            $response["status"] = "error";
            $response["message"] = "Failed to update employee";
        }
    } catch (PDOException $e) {
        // Handle database errors
        error_log('Database error: ' . $e->getMessage());
        $response["status"] = "error";
        $response["message"] = "Database error. Please try again.";
    } catch (Exception $e) {
        // Handle other unexpected errors
        error_log('Error: ' . $e->getMessage());
        $response["status"] = "error";
        $response["message"] = "An unexpected error occurred. Please try again.";
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
