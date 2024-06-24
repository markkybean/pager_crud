<?php
include "dbConnection.php";
$recid = $_GET['recid'];
$sql = "DELETE FROM `employeefile` WHERE recid = $recid";
$result = mysqli_query($conn, $sql);
if($result){
    header("Location: index.php?msg=Employee deleted");
}
else{
    echo "Failed: " .mysqli_error($conn);
}
?>
