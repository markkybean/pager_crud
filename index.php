<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css" />
</head>
<body class="bg-success p-2 bg-opacity-25">

    <?php include "header.php"; ?>

    <div class="container mt-4">
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
                                <button class="btn btn-info btn-sm" onclick="edit_click(<?php echo $xrs['recid'] ?>)">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="delete_click(<?php echo $xrs['recid'] ?>)">Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include "modals.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            // Add New button click handler to open add modal
            $("#btn_add").click(function() {
                $('#addEmployeeModal').modal('show');
            });

            // Save button click handler in add modal
            $("#btn_save_modal").click(function() {
                var formData = {
                    fullName: $("#txt_fullname").val(),
                    address: $("#txt_address").val(),
                    birthdate: $("#txt_birthdate").val(),
                    age: $("#txt_age").val(),
                    gender: $("input[name='txtfld[gender]']:checked").val(),
                    civilStatus: $("#cbo_civilstat").val(),
                    contactNumber: $("#txt_contactnum").val(),
                    salary: $("#txt_salary").val(),
                    isactive: $("#chk_inactive").prop("checked") ? 1 : 0
                };

                $.ajax({
                    url: "add_employee.php",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        if (response.status == 'success') {
                            alertify.alert(response.message, function() {
                                location.reload();
                            });
                        } else {
                            alertify.alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alertify.alert("An error occurred while processing your request.");
                    }
                });

                $('#addEmployeeModal').modal('hide');
            });

            // Edit button click handler
            window.edit_click = function(recid) {
                $.ajax({
                    url: "get_employee.php",
                    type: "POST",
                    dataType: "json",
                    data: { recid: recid },
                    success: function(response) {
                        $("#edit_recid").val(response.recid);
                        $("#edit_txt_fullname").val(response.fullname);
                        $("#edit_txt_address").val(response.address);
                        $("#edit_txt_birthdate").val(response.birthdate);
                        $("#edit_txt_age").val(response.age);
                        $("input[name='edit_txtfld[gender]'][value='" + response.gender + "']").prop("checked", true);
                        $("#edit_cbo_civilstat").val(response.civilstat);
                        $("#edit_txt_contactnum").val(response.contactnum);
                        $("#edit_txt_salary").val(response.salary);
                        $("#edit_chk_inactive").prop("checked", response.isactive == 1);

                        $('#editEmployeeModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alertify.alert("An error occurred while processing your request.");
                    }
                });
            };

            // Update button click handler in edit modal
            $("#btn_update_modal").click(function() {
                var formData = {
                    recid: $("#edit_recid").val(),
                    fullName: $("#edit_txt_fullname").val(),
                    address: $("#edit_txt_address").val(),
                    birthdate: $("#edit_txt_birthdate").val(),
                    age: $("#edit_txt_age").val(),
                    gender: $("input[name='edit_txtfld[gender]']:checked").val(),
                    civilStatus: $("#edit_cbo_civilstat").val(),
                    contactNumber: $("#edit_txt_contactnum").val(),
                    salary: $("#edit_txt_salary").val(),
                    isactive: $("#edit_chk_inactive").prop("checked") ? 1 : 0
                };

                $.ajax({
                    url: "edit_employee.php",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        if (response.status == 'success') {
                            alertify.alert(response.message, function() {
                                location.reload();
                            });
                        } else {
                            alertify.alert(response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alertify.alert("An error occurred while processing your request.");
                    }
                });

                $('#editEmployeeModal').modal('hide');
            });

            // Delete button click handler
            window.delete_click = function(recid) {
                alertify.confirm("Delete employee?", function() {
                    $.ajax({
                        url: "delete_employee.php",
                        type: "POST",
                        dataType: "json",
                        data: { recid: recid, event_action: 'delete_emp' },
                        success: function(response) {
                            alertify.alert(response.msg, function() {
                                location.reload();
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                            alertify.alert("An error occurred while processing your request.");
                        }
                    });
                });
            };
        });

        function print_click(type) {
            var form = document.forms.myform;
            form.method = 'POST';
            form.target = '_blank';
            form.action = 'pdf_employeelist.php';
            form.txt_repoutput.value = type;
            form.submit();
        }
    </script>
</body>
</html>
