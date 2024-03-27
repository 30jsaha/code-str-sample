<?php
function printContent()
{
    $employee_auto_id = EMPLOYEE_DEFAULT_ID;
    $getEmpId = getData(Table::EMPLOYEE_DETAILS, [
        EMPLOYEE_DETAILS::EMPLOYEE_ID
    ], [
        EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID]
    ],[],[],[EMPLOYEE_DETAILS::EMPLOYEE_ID], "DESC", [0,1]);
    if (count($getEmpId)>0) {
        $no = $getEmpId[0][EMPLOYEE_DETAILS::EMPLOYEE_ID];
        $employee_auto_id = (((int)$no) + 1);
    }
    $getdname = getData(Table::DESIGNATIONS, [
        DESIGNATIONS::DESIGNATION_TITLE,
        DESIGNATIONS::ID
    ], [
        DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
        DESIGNATIONS::STATUS => ACTIVE_STATUS,
        DESIGNATIONS::ACTIVE => 1
    ]);
    $getDeptData = getData(Table::DEPARTMENTS, [
        DEPARTMENTS::ID,
        DEPARTMENTS::DEPARTMENT_NAME
    ], [
        DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
        DEPARTMENTS::STATUS => ACTIVE_STATUS
    ]);
    $designations = '<option value="0" disabled selected>Select Designations</option>';
    $departments = '<option value="0" disabled selected>Select Departments</option>';
    if (count($getdname) > 0) {
        foreach ($getdname as $key => $dval) {
            $designations .= '<option value="' . $dval[DESIGNATIONS::ID] . '">' . altRealEscape($dval[DESIGNATIONS::DESIGNATION_TITLE]) . '</option>';
        }
    }
    if (count($getDeptData) > 0) {
        foreach ($getDeptData as $key => $v) {
            $departments .= '<option value="' . $v[DEPARTMENTS::ID] . '">' . altRealEscape($v[DEPARTMENTS::DEPARTMENT_NAME]) . '</option>';
        }
    }
    $user_types = '';
    if (count(USERS)>0) {
        foreach (USERS as $k => $v) {
            if ($_SESSION[USER_TYPE] == ADMIN) {
                if (($k == ADMIN) || ($k == SADMIN)) {
                    continue;
                }
            }
            $selected = '';
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                    if ($k == EMPLOYEE) {
                        $selected = 'selected';
                    }
                    break;
                case SADMIN:
                    if ($k == ADMIN) {
                        $selected = 'selected';
                    }
                    break;
            }
            $user_types .= '<option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }
    }
    $userTypeIn = [SADMIN, ADMIN, MANAGER];
    $reporting_manager = '<option value="0" selected disabled>--- Select Reporting Manager ---</option>';
    $reporting_times = '<option value="0" selected disabled>--- Select Reporting Time ---</option>';
    // Assigning Reporting Manager
    $getReportManagerSql = "SELECT ". 
    Users::ID .", ".
    Users::EMPLOYEE_ID .", ".
    Users::NAME .", ".
    Users::USER_TYPE." FROM ".
    Table::USERS." WHERE ". 
    Users::CLIENT_ID ." = ". 
    $_SESSION[CLIENT_ID]. " AND ".
    Users::STATUS ." = '". ACTIVE_STATUS ."' AND ". 
    Users::ACTIVE ." = 1 AND ". 
    Users::USER_TYPE ." IN (". implode(", ", $userTypeIn). ") AND ". 
    Users::EMPLOYEE_ID ." NOT IN (0)";
    $getReportManager = getCustomData($getReportManagerSql);
    // rip($getReportManager);
    // exit;
    if (count($getReportManager)>0) {
        foreach ($getReportManager as $k => $v) {

            $reporting_manager .= '<option value="'. $v[Users::ID] .'">'. $v[Users::NAME] .' -- ['. USERS[$v[Users::USER_TYPE]] .']</option>';
        }
    }
    if (count(EMPLOYEE_REPORTING_TIMES)>0) {
        for ($i=0; $i < count(EMPLOYEE_REPORTING_TIMES); $i++) {
            $value = EMPLOYEE_REPORTING_TIMES[$i] .':00 ';
            $value .= (EMPLOYEE_REPORTING_TIMES[$i] == '12') ? 'PM' : 'AM';
            $reporting_times .= '<option value="'. EMPLOYEE_REPORTING_TIMES[$i] .'">'. $value .'</option>';
        }
    }
?>

    <h4 class="text-center font-weight-bold">Add / Update Employees Here</h4>

    <div class="card mt-5">
        <?= getSpinner(true, "emp_loader") ?>
        <div class="card-body" style="padding: 15px;">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <?php include BASE_DIR.'includes/employee_form.php'; ?>
                    <!-- Action Button row start -->
                    <div class="row mt-4">
                        <div class="col-lg-6 col-md-6 col-sm-12 noselect">

                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 text-right">
                            <button type="button" class="btn btn-success sbmt" data-action="add_employee" id="emp_add_btn">
                                Add
                            </button>
                        </div>
                    </div>
                    <!-- Action Button row start -->
                </div>
            </div>
        </div>
    </div>

<?php } ?>