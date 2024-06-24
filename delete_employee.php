<?php
include_once 'db_config.php';

// Initialize response array
$response = array();

// Check if the event_action is delete_emp and recid is set
if ($_POST["event_action"] == "delete_emp" && isset($_POST["recid"])) {
    // Prepare and execute DELETE query
    $xqry = "DELETE FROM employeefile WHERE recid=?";
    $xstmt = $link_id->prepare($xqry);
    $xstmt->execute(array($_POST["recid"]));

    // Check if deletion was successful
    if ($xstmt) {
        $response["success"] = true;
        $response["msg"] = "Successfully deleted";
    } else {
        $response["success"] = false;
        $response["msg"] = "Failed to delete employee";
    }
} else {
    // If event_action or recid is not set
    $response["success"] = false;
    $response["msg"] = "Invalid request";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
