<?php 
function printContent() {
    $att_tr = '';
    $month = getToday(false, 'm');
    $year = getToday(false, 'Y');
    $emp_id = 0;

    $method = $_SERVER['REQUEST_METHOD'];
    $request_data = $_REQUEST;
    switch ($method) {
        case 'GET':
            $request_data = $_GET;
            break;
        case 'POST':
            $request_data = $_POST;
            break;
        default:
            $request_data = $_REQUEST;
            break;
    }

    $getAttDetails = getData(Table::ATTENDANCE, ['*'], [
        ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
        ATTENDANCE::STATUS => ACTIVE_STATUS,
        ATTENDANCE::ATTENDANCE_MONTH => $month,
        ATTENDANCE::ATTENDANCE_YEAR => $year,
        ATTENDANCE::EMPLOYEE_ID => $emp_id
    ]);
    $getEmployee = getData(Table::EMPLOYEE_DETAILS, [
        EMPLOYEE_DETAILS::ID,
        EMPLOYEE_DETAILS::EMPLOYEE_NAME,
        EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID
    ], [
        EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
        EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
        EMPLOYEE_DETAILS::ACTIVE => 1
    ]);
    $employees = '';
    if (count($getEmployee)>0) {
        $employees = '<option value="0" disabled selected>Select Employee</option>';
        foreach ($getEmployee as $key => $empl_val) {
            $getDesig = getData(Table::DESIGNATIONS,[
                DESIGNATIONS::DESIGNATION_TITLE
            ], [
                DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                DESIGNATIONS::STATUS => ACTIVE_STATUS,
                DESIGNATIONS::ID => $empl_val[EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID]
            ]);
            $getUserData = getData(Table::USERS, [
                Users::USER_TYPE
            ], [
                Users::ACTIVE => 1,
                Users::STATUS => ACTIVE_STATUS,
                Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                Users::EMPLOYEE_ID => $empl_val[EMPLOYEE_DETAILS::ID]
            ]);
            $userType = 0;
            if (count($getUserData)>0) {
                $userType = $getUserData[0][Users::USER_TYPE];
            }
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                    if ($userType != ADMIN) {
                        $emp_desig = ((count($getDesig)>0) && ($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE] != "")) ? "  ---  ".altRealEscape(ucwords($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE])) : "";
                        $employees .= '<option value="'.$empl_val[EMPLOYEE_DETAILS::ID].'" >'.altRealEscape($empl_val[EMPLOYEE_DETAILS::EMPLOYEE_NAME]).$emp_desig.'</option>';
                    }
                    break;
                
                default:
                    $emp_desig = ((count($getDesig)>0) && ($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE] != "")) ? "  ---  ".altRealEscape(ucwords($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE])) : "";
                    $employees .= '<option value="'.$empl_val[EMPLOYEE_DETAILS::ID].'" >'.altRealEscape($empl_val[EMPLOYEE_DETAILS::EMPLOYEE_NAME]).$emp_desig.'</option>';
                    break;
            }
        }
    } else {
        $employees = '<option value="0" disabled selected>No Employees Found</option>';
    }










    // rip($getAttDetails);
    if (count($getAttDetails)>0) {
        foreach ($getAttDetails as $k => $v) {
            $userType = 0;
            $employee_id = '';
            $getUserData = getData(Table::USERS, [
                Users::USER_TYPE
            ], [
                Users::ACTIVE => 1,
                Users::STATUS => ACTIVE_STATUS,
                Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                Users::EMPLOYEE_ID => $v[ATTENDANCE::EMPLOYEE_ID]
            ]);
            $getEmpDataSql = "SELECT emp.". 
            EMPLOYEE_DETAILS::EMPLOYEE_NAME.", emp.". 
            EMPLOYEE_DETAILS::EMPLOYEE_ID.", desig.". 
            DESIGNATIONS::DESIGNATION_TITLE.", dept.".
            DEPARTMENTS::DEPARTMENT_NAME." FROM ". 
            Table::EMPLOYEE_DETAILS." AS emp INNER JOIN ". 
            Table::DESIGNATIONS." AS desig ON (emp.". 
            EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID." = desig.". 
            DESIGNATIONS::ID.") INNER JOIN ". 
            Table::DEPARTMENTS." AS dept ON (dept.". 
            DEPARTMENTS::ID." = emp.". 
            EMPLOYEE_DETAILS::DEPARTMENT_ID.") WHERE emp.". 
            EMPLOYEE_DETAILS::CLIENT_ID." = ". $_SESSION[CLIENT_ID]." AND emp.". 
            EMPLOYEE_DETAILS::ID." = ". $v[ATTENDANCE::EMPLOYEE_ID];
            // echo $getEmpDataSql;
            // exit;
            // $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
            //     EMPLOYEE_DETAILS::EMPLOYEE_NAME
            // ], [
            //     EMPLOYEE_DETAILS::ID => $v[ATTENDANCE::EMPLOYEE_ID],
            //     EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            //     EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
            // ]);
            $getEmpData = getCustomData($getEmpDataSql);
            // rip($getUserData);
            // exit;
            if (count($getUserData)>0) {
                $userType = $getUserData[0][Users::USER_TYPE];
            }
            // echo $userType;
            // exit;
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                    if ($userType != ADMIN) {
                        $sl = ($k+1);
                        $emp_id = $getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_ID];
                        $emp_details = '<b>Name: </b>'.$getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME];
                        $emp_details .= '<br><b>Department: </b>'.$getEmpData[0][DEPARTMENTS::DEPARTMENT_NAME];
                        $emp_details .= '<br><b>Designation: </b>'.$getEmpData[0][DESIGNATIONS::DESIGNATION_TITLE];
                        $date = $v[ATTENDANCE::ATTENDANCE_DATE];
                        $report_time = getFormattedDateTime($v[ATTENDANCE::REPORTING_TIME], LONG_TIME_FORMAT);
                        $logOff_time = ($v[ATTENDANCE::LOG_OFF_TIME] != '') ? getFormattedDateTime($v[ATTENDANCE::LOG_OFF_TIME], LONG_TIME_FORMAT) : 'NOT YET';
                        $workingHours = ($v[ATTENDANCE::WORKING_HOURS] != '') ? $v[ATTENDANCE::WORKING_HOURS] : (($v[ATTENDANCE::REPORTING_TIME] != '') ? getWorkingHrs(getFormattedDateTime($v[ATTENDANCE::REPORTING_TIME], 'H:i'), getToday(true, '', 'H:i')) : 00);
                        $lateReason = ($v[ATTENDANCE::LATE_ENTRY_REASON] != '') ? $v[ATTENDANCE::LATE_ENTRY_REASON] : EMPTY_VALUE;
                        $earlyReason = ($v[ATTENDANCE::EARLY_LOG_OFF_REASON] != '') ? $v[ATTENDANCE::EARLY_LOG_OFF_REASON] : EMPTY_VALUE;

                        $att_tr .= '<tr id="att_list_'.$v[ATTENDANCE::ID].'">
                            <td>'.$sl.'</td>
                            <td>'.$emp_id.'</td>
                            <td class="text-left">'.$emp_details.'</td>
                            <td>'.$date.'</td>
                            <td>'.$report_time.'</td>
                            <td>'.$logOff_time.'</td>
                            <td>'.$workingHours.'</td>
                            <td>'.$lateReason.'</td>
                            <td>'.$earlyReason.'</td>
                        </tr>';
                    }
                    break;
            }
        }
    } else {
        $att_tr = '<tr class="animated fadeInDown">
        <td colspan="9">
            <div class="alert alert-danger" role="alert">
                No Attendance found ! Please select the particulars to get records.
            </div>
        </td>
        </tr>';
    }
?>
<h2 class="text-center">Employees Attendance</h2>



<div class="card mt-5">
    <?=getSpinner(true, "attendance_list_loader");?>
    <div class="card_body" style="padding: 15px;">
        <div class="row">
            <div class="col-md-4 col-lg-4 col-sm-6">
                <label class="form_label" for="att_year_select">Select Year</label><?=getAsterics();?>
                <select id="att_year_select" class="form-control" onchange="getAttRecord();">
                    <option value='0' selected disabled>--Select Year--</option>
                    <?php 
                        $year = 2024;
                        for ($i=0; $i <= 1; $i++) { 
                            $y = ($year + $i);
                            $selected = ($y == getToday(false, 'Y')) ? 'selected' : '';
                            echo "<option ".$selected." value='". $y ."'>".$y."</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6">
                <label for="att_month_select" class="form_label">Select Month</label>
                <select class="form-control" id="att_month_select" onchange="getAttRecord();">
                <?php 
                    $month = getToday(false,'m');
                    for ($i=0; $i <= count(ALL_MONTHS_NAME); $i++) { 
                        $m = ($i+1);
                        $selected = ($m == $month) ? 'selected' : '';
                        echo "<option ".$selected." value='". $m ."'>".ALL_MONTHS_NAME[$m]."</option>";
                    }
                ?>
                </select>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6">
                <label for="att_emp_select" class="form_label">Select Employee</label>
                <select class="form-control" id="att_emp_select" onchange="getAttRecord();">
                    <?=$employees;?>
                </select>
            </div>
        </div>
        <div class="row mt-2 animated fadeInDown" id="employee_details_row" style="display: none;">
            <div class="col-12">
            <fieldset class="fldset mt-3">
                <legend>Employee Details</legend>
                <span id="employee_details_span"></span>
            </fieldset>
            </div>
        </div>
        <div class="row mt-2" id="list_attendance_row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center attendance_list_table" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase; font-size: 12px;">
                                <th class="cursor-pointer">SL.</th>
                                <!-- <th class="cursor-pointer">Name</th>
                                <th class="cursor-pointer">Designation</th>
                                <th class="cursor-pointer">Department</th> -->
                                <th class="cursor-pointer">Date</th>
                                <!-- <th class="cursor-pointer">Employee Details</th> -->
                                <th class="cursor-pointer">Reporting Time</th>
                                <th class="cursor-pointer">Log Off Time</th>
                                <th class="cursor-pointer">Working Hours</th>
                                <th class="cursor-pointer">Late Mints</th>
                                <th class="cursor-pointer">Early Log Off Reason</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                           <?=$att_tr;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
}
?>