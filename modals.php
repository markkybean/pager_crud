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

    <!-- Modal for Edit Employee -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing employee -->
                    <form id="editEmployeeFormModal">
                        <input type="hidden" id="edit_recid" name="edit_recid">
                        <div class="mb-3">
                            <label for="edit_txt_fullname" class="form-label">Full Name:</label>
                            <input type="text" class="form-control" name="edit_txtfld[fullname]" id="edit_txt_fullname" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_txt_address" class="form-label">Address:</label>
                            <input type="text" class="form-control" name="edit_txtfld[address]" id="edit_txt_address" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_txt_birthdate" class="form-label">Birth Date:</label>
                            <input type="date" class="form-control" name="edit_txtfld[birthdate]" id="edit_txt_birthdate" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_txt_age" class="form-label">Age:</label>
                            <input type="number" class="form-control" name="edit_txtfld[age]" id="edit_txt_age" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edit_txtfld[gender]" id="edit_rdo_male" value="Male" required>
                                <label class="form-check-label" for="edit_rdo_male">Male</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edit_txtfld[gender]" id="edit_rdo_female" value="Female" required>
                                <label class="form-check-label" for="edit_rdo_female">Female</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="edit_txtfld[gender]" id="edit_rdo_other" value="Other" required>
                                <label class="form-check-label" for="edit_rdo_other">Other</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_cbo_civilstat" class="form-label">Civil Status:</label>
                            <select class="form-select" id="edit_cbo_civilstat" name="edit_txtfld[civilstat]" required>
                                <option selected disabled>Choose...</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Separated">Separated</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_txt_contactnum" class="form-label">Contact Number:</label>
                            <input type="text" class="form-control" name="edit_txtfld[contactnum]" id="edit_txt_contactnum" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_txt_salary" class="form-label">Salary:</label>
                            <input type="number" class="form-control" name="edit_txtfld[salary]" id="edit_txt_salary" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" name="edit_txtfld[isactive]" id="edit_chk_inactive">
                            <label class="form-check-label" for="edit_chk_inactive">Active</label>
                        </div>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_update_modal">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for View Employee -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" aria-labelledby="viewEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewEmployeeModalLabel">View Employee Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form for viewing employee details -->
                <form id="viewEmployeeFormModal">
                    <input type="hidden" id="view_recid" name="view_recid">
                    <div class="mb-3">
                        <label for="view_txt_fullname" class="form-label">Full Name:</label>
                        <input type="text" class="form-control" name="view_txtfld[fullname]" id="view_txt_fullname" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_txt_address" class="form-label">Address:</label>
                        <input type="text" class="form-control" name="view_txtfld[address]" id="view_txt_address" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_txt_birthdate" class="form-label">Birth Date:</label>
                        <input type="date" class="form-control" name="view_txtfld[birthdate]" id="view_txt_birthdate" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_txt_age" class="form-label">Age:</label>
                        <input type="number" class="form-control" name="view_txtfld[age]" id="view_txt_age" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender:</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="view_txtfld[gender]" id="view_rdo_male" value="Male" disabled>
                            <label class="form-check-label" for="view_rdo_male">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="view_txtfld[gender]" id="view_rdo_female" value="Female" disabled>
                            <label class="form-check-label" for="view_rdo_female">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="view_txtfld[gender]" id="view_rdo_other" value="Other" disabled>
                            <label class="form-check-label" for="view_rdo_other">Other</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="view_cbo_civilstat" class="form-label">Civil Status:</label>
                        <input type="text" class="form-control" name="view_txtfld[civilstat]" id="view_txt_civilstat" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_txt_contactnum" class="form-label">Contact Number:</label>
                        <input type="text" class="form-control" name="view_txtfld[contactnum]" id="view_txt_contactnum" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="view_txt_salary" class="form-label">Salary:</label>
                        <input type="number" class="form-control" name="view_txtfld[salary]" id="view_txt_salary" readonly>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" name="view_txtfld[isactive]" id="view_chk_inactive" disabled>
                        <label class="form-check-label" for="view_chk_inactive">Active</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
