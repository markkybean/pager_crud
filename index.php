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

    <div class="container mt-4">
        <div class="d-flex justify-content-end">
            <a href="add_employee.php" class="btn btn-dark mb-4">Add New</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Birth Date</th>
                        <th scope="col">Age</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Civil Status</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Salary</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    include "dbConnection.php";

                    // Fetch all employees
                    $sql = "SELECT * FROM `employeefile`";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // Check if there are any records
                    if ($result->num_rows > 0) {
                        // Display each employee as a table row
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['recid']); ?></td>
                                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><?php echo htmlspecialchars($row['birthdate']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['civilstat']); ?></td>
                                <td><?php echo htmlspecialchars($row['contactnum']); ?></td>
                                <td><?php echo htmlspecialchars($row['salary']); ?></td>
                                <td><?php echo $row['isactive'] ? 'Active' : 'Inactive'; ?></td>
                                <td>
                                    <a href="edit_employee.php?recid=<?php echo $row['recid'] ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="delete_employee.php?recid=<?php echo $row['recid'] ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        // Display a message if no records are found
                        echo '<tr><td colspan="11" class="text-center">No employees found.</td></tr>';
                    }
                   
                    $stmt->close();
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
