<?php
include_once 'dbConnection.php';

// Fetch the existing data of the employee to be edited
if (isset($_GET['recid'])) {
    $recid = $_GET['recid'];
    $sql = "SELECT * FROM `employeefile` WHERE `recid` = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $recid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
} else {
    die("Error: Employee ID not provided.");
}

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recid'])) {
    // Retrieve the form data
    $recid = $_POST['recid'];
    $fullName = $_POST['fullName'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $civilstat = $_POST['civilStatus'];
    $contactNumber = $_POST['contactNumber'];
    $salary = $_POST['salary'];
    $active = isset($_POST['active']) ? 1 : 0; 

    
    $sql = "UPDATE `employeefile` SET `fullname`=?, `address`=?, `birthdate`=?, `age`=?, `gender`=?, `civilstat`=?, `contactnum`=?, `salary`=?, `isactive`=? WHERE `recid`=?";

  
    $stmt = mysqli_prepare($conn, $sql);

   
    mysqli_stmt_bind_param($stmt, "sssisssdii", $fullName, $address, $birthdate, $age, $gender, $civilstat, $contactNumber, $salary, $active, $recid);

    
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        header("Location: index.php?msg=Employee updated successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
    }

   
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-success p-2 bg-opacity-25">

    <?php
    include "navbar.php";
    ?>

    <div class="container-fluid mt-4">
        <div class="mb-4">
            <h3>Edit Employee</h3>

            <div class="container d-flex justify-content-center">
                <form action="" method="post">

                    <div class="mb-5 p-5 border shadow-lg" style="background: #d8d8d8; padding: 20px; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <label for="fullName">Full Name:</label>
                                <input type="text" class="form-control" id="fullName" name="fullName" value="<?php echo htmlspecialchars($row['fullname']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="address">Address:</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($row['address']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="birthdate">Birth Date:</label>
                                <input type="date" class="form-control" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($row['birthdate']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="age">Age:</label>
                                <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($row['age']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="gender">Gender:</label>
                                <div>
                                    <input type="radio" id="male" name="gender" value="Male" <?php echo ($row['gender'] == 'Male') ? 'checked' : ''; ?> required />
                                    <label for="male">Male</label>
                                </div>
                                <div>
                                    <input type="radio" id="female" name="gender" value="Female" <?php echo ($row['gender'] == 'Female') ? 'checked' : ''; ?> required />
                                    <label for="female">Female</label>
                                </div>
                                <div>
                                    <input type="radio" id="other" name="gender" value="Other" <?php echo ($row['gender'] == 'Other') ? 'checked' : ''; ?> required />
                                    <label for="other">Other</label>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="civilStatus">Civil Status:</label>
                                <select class="form-select" id="civilstat" name="civilStatus" required>
                                    <option selected disabled>Choose...</option>
                                    <option value="Single" <?php echo ($row['civilstat'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                                    <option value="Married" <?php echo ($row['civilstat'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                                    <option value="Separated" <?php echo ($row['civilstat'] == 'Separated') ? 'selected' : ''; ?>>Separated</option>
                                    <option value="Widowed" <?php echo ($row['civilstat'] == 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="contactNumber">Contact Number:</label>
                                <input type="number" class="form-control" id="contactNumber" name="contactNumber" value="<?php echo htmlspecialchars($row['contactnum']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <label for="salary">Salary:</label>
                                <input type="number" class="form-control" id="salary" name="salary" value="<?php echo htmlspecialchars($row['salary']); ?>" required />
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="active" name="active" <?php echo ($row['isactive']) ? 'checked' : ''; ?> />
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="recid" value="<?php echo htmlspecialchars($row['recid']); ?>">
                        <div class="float-end">
                            <button class="btn btn-success" type="submit">Save</button>
                            <a href="index.php" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>