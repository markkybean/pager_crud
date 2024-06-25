<?php

/**
 * Inserts a record into the specified table using PDO prepared statements.
 *
 * @param PDO $pdo PDO connection instance.
 * @param string $tablename Table name.
 * @param array $record_parameters Associative array of field names and values to insert.
 * @param bool $debug Whether to enable debug mode (default: false).
 * @return bool True on success, false on failure.
 */
function PDO_InsertRecord($pdo, $tablename, $record_parameters, $debug = false)
{
    // Validate input parameters
    if (!is_array($record_parameters) || empty($record_parameters)) {
        if ($debug) {
            echo "No valid record parameters supplied.<br>";
        }
        return false;
    }

    // Prepare fields and values for the SQL query
    $fields = implode(', ', array_keys($record_parameters));
    $placeholders = implode(', ', array_fill(0, count($record_parameters), '?'));

    $sql = "INSERT INTO $tablename ($fields) VALUES ($placeholders)";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the statement with record parameters
    $stmt->execute(array_values($record_parameters));

    // Optional debug output
    if ($debug) {
        echo '<hr><br>Statement:<br>';
        var_dump($stmt);
        echo '<br>Error Information:<br>';
        var_dump($stmt->errorInfo());
        echo '<br>Parameters:<br>';
        var_dump($record_parameters);
    }

    return true;
}

/**
 * Updates records in the specified table using PDO prepared statements.
 *
 * @param PDO $pdo PDO connection instance.
 * @param string $tablename Table name.
 * @param array $record_parameters Associative array of field names and new values to update.
 * @param string $condition SQL WHERE condition (default: 'true').
 * @param array $condition_parameters Parameters for the WHERE condition.
 * @param bool $debug Whether to enable debug mode (default: false).
 * @return bool True on success, false on failure.
 */
function PDO_UpdateRecord($pdo, $tablename, $record_parameters, $condition = 'true', $condition_parameters = array(), $debug = false)
{
    // Validate input parameters
    if (!is_array($record_parameters) || empty($record_parameters)) {
        if ($debug) {
            echo "No valid record parameters supplied.<br>";
        }
        return false;
    }

    // Prepare update fields for the SQL query
    $update_fields = array();
    foreach ($record_parameters as $field => $value) {
        $update_fields[] = "$field = ?";
    }
    $update_fields_string = implode(', ', $update_fields);

    // Prepare condition parameters
    $update_parameters = array_values($record_parameters);
    $update_parameters = array_merge($update_parameters, $condition_parameters);

    // Construct the SQL query
    $sql = "UPDATE $tablename SET $update_fields_string WHERE $condition";

    // Prepare the SQL statement
    $stmt = $pdo->prepare($sql);

    // Execute the statement with update and condition parameters
    $stmt->execute($update_parameters);

    // Optional debug output
    if ($debug) {
        echo '<hr><br>Statement:<br>';
        var_dump($stmt);
        echo '<br>Error Information:<br>';
        var_dump($stmt->errorInfo());
        echo '<br>Parameters:<br>';
        var_dump($update_parameters);
    }

    return true;
}

/**
 * Refreshes the auto-increment value of a table after reordering the records.
 *
 * @param PDO $pdo PDO connection instance.
 * @param string $tablename Table name to refresh.
 * @return void
 */
function PDO_Refreshid($pdo, $tablename)
{
    $xcount = 0;

    // Select all records ordered by recid
    $sql_select = "SELECT recid FROM $tablename ORDER BY recid";
    $stmt_select = $pdo->prepare($sql_select);
    $stmt_select->execute();

    // Loop through selected records and update recid sequentially
    while ($rs = $stmt_select->fetch()) {
        $xcount++;

        // Update recid for each record
        $sql_update = "UPDATE $tablename SET recid = ? WHERE recid = ?";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute(array($xcount, $rs['recid']));
    }

    $xcount++;

    // Reset auto-increment value for the table
    $sql_alter = "ALTER TABLE $tablename AUTO_INCREMENT = $xcount";
    $stmt_alter = $pdo->prepare($sql_alter);
    $stmt_alter->execute();
}

/**
 * Logs user activity into a dedicated table using PDO prepared statements.
 *
 * @param PDO $pdo PDO connection instance.
 * @param string $tablename Table name where activity will be logged.
 * @param string $usercode User code or identifier.
 * @param string $activity Activity description.
 * @param string $remarks Remarks or additional details.
 * @param string $webpage Web page where activity occurred.
 * @param string $action Action performed (e.g., 'insert', 'update', 'delete').
 * @param string $module Program module or section.
 * @param bool $success Success status of the activity (default: true).
 * @return void
 */
function PDO_UserActivityLog($pdo, $tablename, $usercode, $activity, $remarks, $webpage, $action, $module, $success = true)
{
    $params = [
        'usrname' => $usercode,
        'empcode' => $usercode,
        'usrdte' => date("Y-m-d H:i:s"),
        'logdte_client' => date("Y-m-d H:i:s"),
        'logdte_server' => date("Y-m-d H:i:s"),
        'usrtim' => date("H:i:s"),
        'module' => $module,
        'activity' => $activity,
        'remarks' => $remarks,
        'status' => $success ? "Success" : "Failed",
        'log_tablename' => $tablename,
        'osversion' => php_uname(),
        'ipaddress' => $_SERVER['REMOTE_ADDR'],
        'pagename' => $webpage
    ];

    PDO_InsertRecord($pdo, 'useractivitylogfile', $params, false);

    // Perform maintenance on the user activity log table if necessary (e.g., limit records)
    // Uncomment and adjust as needed
    /*
    $sql_count = "SELECT COUNT(*) AS xcount FROM useractivitylogfile";
    $stmt_count = $pdo->prepare($sql_count);
    $stmt_count->execute();
    $row_count = $stmt_count->fetch();

    $max_records = 10000; // Example: maximum records to retain
    if ($row_count['xcount'] > $max_records) {
        $excess_records = $row_count['xcount'] - $max_records;
        $sql_delete = "DELETE FROM useractivitylogfile LIMIT $excess_records";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute();

        PDO_Refreshid($pdo, 'useractivitylogfile');
    }
    */
}
?>
