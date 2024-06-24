<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css"/>
</head>

<body class="bg-success p-2 bg-opacity-25">

    <?php
    // Include navbar.php assuming it contains your navigation bar code
    include "navbar.php";
    ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-end">
            <a href="#" class="btn btn-dark mb-4" id="btn_add">Add New</a>
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
                <tbody id="employeeTableBody">
                    <?php
                    require_once('db_config.php');

                    $xqry = "SELECT * FROM employeefile";
                    $xstmt = $link_id->prepare($xqry);
                    $xstmt->execute();

                    while ($xrs = $xstmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($xrs["recid"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["fullname"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["address"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["birthdate"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["age"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["gender"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["civilstat"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["contactnum"]) ?></td>
                            <td><?php echo htmlspecialchars($xrs["salary"]) ?></td>
                            <td><?php echo $xrs["isactive"] ? 'Active' : 'Inactive' ?></td>
                            <td>
                                <a href="edit_employee.php?recid=<?php echo $xrs['recid'] ?>" class="btn btn-info btn-sm">Edit</a>
                                <button class="btn btn-danger btn-sm" onclick="delete_click(<?php echo $xrs['recid'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Add New Employee -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding new employee -->
                    <form id="addEmployeeFormModal">
                        <div class="mb-3">
                            <label for="txt_fullname" class="form-label">Full Name:</label>
                            <input type="text" class="form-control" name="txtfld[fullname]" id="txt_fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="txt_address" class="form-label">Address:</label>
                            <input type="text" class="form-control" name="txtfld[address]" id="txt_address" required>
                        </div>
                        <div class="mb-3">
                            <label for="txt_birthdate" class="form-label">Birth Date:</label>
                            <input type="date" class="form-control" name="txtfld[birthdate]" id="txt_birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="txt_age" class="form-label">Age:</label>
                            <input type="number" class="form-control" name="txtfld[age]" id="txt_age" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_male" value="Male" required>
                                <label class="form-check-label" for="rdo_male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_female" value="Female" required>
                                <label class="form-check-label" for="rdo_female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="txtfld[gender]" id="rdo_other" value="Other" required>
                                <label class="form-check-label" for="rdo_other">Other</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cbo_civilstat" class="form-label">Civil Status:</label>
                            <select class="form-select" id="cbo_civilstat" name="txtfld[civilstat]" required>
                                <option selected disabled>Choose...</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Separated">Separated</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="txt_contactnum" class="form-label">Contact Number:</label>
                            <input type="text" class="form-control" name="txtfld[contactnum]" id="txt_contactnum" required>
                        </div>
                        <div class="mb-3">
                            <label for="txt_salary" class="form-label">Salary:</label>
                            <input type="number" class="form-control" name="txtfld[salary]" id="txt_salary" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="txtfld[isactive]" id="chk_inactive">
                            <label class="form-check-label" for="chk_inactive">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_save_modal">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            // Add New button click handler to open modal
            $("#btn_add").click(function() {
                $('#addEmployeeModal').modal('show');
            });

            // Save button click handler in modal
            $("#btn_save_modal").click(function() {
                // Gather form data
                var fullName = $("#txt_fullname").val();
                var address = $("#txt_address").val();
                var birthdate = $("#txt_birthdate").val();
                var age = $("#txt_age").val();
                var gender = $("input[name='txtfld[gender]']:checked").val();
                var civilStatus = $("#cbo_civilstat").val();
                var contactNumber = $("#txt_contactnum").val();
                var salary = $("#txt_salary").val();
                var isActive = $("#chk_inactive").prop("checked") ? 1 : 0;

                // AJAX request
                $.ajax({
                    url: "add_employee.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        fullName: fullName,
                        address: address,
                        birthdate: birthdate,
                        age: age,
                        gender: gender,
                        civilStatus: civilStatus,
                        contactNumber: contactNumber,
                        salary: salary,
                        isactive: isActive
                    },
                    success: function(response) {
                        // Handle success response
                        if (response.status == 'success') {
                            alertify.alert(response.message, function(){
                                location.reload();
                            });
                        } else {
                            // Handle error response
                            alertify.alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors
                        console.error(xhr.responseText);
                        alertify.alert("An error occurred while processing your request.");
                    }
                });

                // Close modal
                $('#addEmployeeModal').modal('hide');
            });

            // Delete button click handler
            window.delete_click = function(xrecid) {
                alertify.confirm("Delete employee?", function() {
                    var xdata = "recid=" + xrecid + "&event_action=delete_emp";
                    $.ajax({
                        url: "delete_employee.php",
                        type: "POST",
                        dataType: "json",
                        data: xdata,
                        success: function(xres) {
                            alertify.alert(xres["msg"], function() {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors
                            console.error(xhr.responseText);
                            alertify.alert("An error occurred while processing your request.");
                        }
                    });
                });
            };
        });
    </script>
</body>

</html>
