<?php 
function printContent() {
    $dept_tr = "";
    $getDepartmentData = getData(Table::DEPARTMENTS, [
        DEPARTMENTS::DEPARTMENT_NAME,
        DEPARTMENTS::ID
    ], [
        DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
        DEPARTMENTS::STATUS => ACTIVE_STATUS
    ]);
    if (count($getDepartmentData)>0) {
        foreach ($getDepartmentData as $key => $v) {
            $slno = ($key + 1);
            $dname = ((!empty($v[DEPARTMENTS::DEPARTMENT_NAME])) || ($v[DEPARTMENTS::DEPARTMENT_NAME] != "")) ? altRealEscape(ucwords($v[DEPARTMENTS::DEPARTMENT_NAME])) : EMPTY_VALUE;
            $action = '
            <div class="row">
                <div class="col-12 text-danger cursor-pointer" onclick="initiateDelete('. $v[DEPARTMENTS::ID] .', \'department\')" style="padding: 5px; display:flex; justify-content: center;">
                    <i class="fas fa-trash-alt cursor-pointer"></i>
                </div>
            </div>
            ';

            $dept_tr .= '<tr id="department_'.$v[DEPARTMENTS::ID].'">
            <td>'.$slno.'</td>
            <td>'.$dname.'</td>
            <td>'.$action.'</td>
        </tr>';
        }
    } else {
        $dept_tr = '<tr class="animated fadeInDown">
        <td colspan="3">
            <div class="alert alert-danger" role="alert">
                No Departments found !
            </div>
        </td>
        </tr>';
    }
?>
<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 text-center">
        <h2 class="text-center">Departments</h2>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "department_loader");?>
    <div class="card_body" style="padding: 15px;">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
            <h5 class="text-left font-weight-bold">Add / Update Designations</h5>
                <div class="row pt-2">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <input type="text" class="form-control" name="Department Name" id="department_name" onkeypress="enterEventListner($(this), $('#department_submit'));"/>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <button class="btn btn-primary" id="department_submit">Add</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center department_table" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase;">
                                <th>SL.</th>
                                <th>Department Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?=$dept_tr;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>