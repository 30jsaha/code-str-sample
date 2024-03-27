<?php 
function printContent() {
    $isPresent = false;
    // echo getToday(true, '', 'H:i');
    // exit;
    $attCycle = CYCLE_NOT_STARTED;
    $reportingTime = $logOffTime = $earlyLogOffReason = $late_comment = $late_approval = $report_time = $late_mints = $dashboard_notification = '';
    $birthday_emp_id = $birthday_employees = [];
    $workingHours = getWorkingHrs('10:00', getToday(true, '', 'H:i'));
    $getAttData = getData(Table::ATTENDANCE, [
        ATTENDANCE::ID,
        ATTENDANCE::REPORTING_TIME,
        ATTENDANCE::LOG_OFF_TIME,
        ATTENDANCE::ATTENDANCE_MONTH,
        ATTENDANCE::ATTENDANCE_YEAR,
        ATTENDANCE::ATTENDANCE_DATE,
        ATTENDANCE::WORKING_HOURS,
        ATTENDANCE::EARLY_LOG_OFF_REASON,
        ATTENDANCE::ACTIVE,
        ATTENDANCE::IS_LATE_ENTRY,
        ATTENDANCE::ADMIN_APPROVAL_FOR_LATE_ENTRY,
        ATTENDANCE::LATE_MINTS
    ], [
        ATTENDANCE::ATTENDANCE_DATE => getToday(false),
        ATTENDANCE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID],
        ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
        ATTENDANCE::STATUS => ACTIVE_STATUS,
        ATTENDANCE::ATTENDANCE_MONTH => getToday(false, 'm'),
        ATTENDANCE::ATTENDANCE_YEAR => getToday(false, 'Y')
    ]);
    if (count($getAttData)) {
        $attData = $getAttData[0];
        switch ($attData[ATTENDANCE::ACTIVE]) {
            case CYCLE_ACTIVE:
                $isPresent = true;
                $attCycle = CYCLE_ACTIVE;
                $reportingTime = $attData[ATTENDANCE::REPORTING_TIME];
                // rip(explode(":", $reportingTime));
                // echo '<br>'. count(explode(":", $reportingTime));
                // exit;
                $workingHours = getWorkingHrs($reportingTime, getToday(true, '', 'H:i'));
                $late_mints = ($attData[ATTENDANCE::IS_LATE_ENTRY]) ? $attData[ATTENDANCE::LATE_MINTS] : '';
                $late_approval = (($attData[ATTENDANCE::IS_LATE_ENTRY]) && ($attData[ATTENDANCE::ADMIN_APPROVAL_FOR_LATE_ENTRY] == 1)) ? ' <i class="text-success">[APPROVED]</i>' : '';
                break;
            case CYCLE_CLOSED:
                $reportingTime = $attData[ATTENDANCE::REPORTING_TIME];
                $logOffTime = $attData[ATTENDANCE::LOG_OFF_TIME];
                $attCycle = CYCLE_CLOSED;
                $isPresent = true;
                $earlyLogOffReason = $attData[ATTENDANCE::EARLY_LOG_OFF_REASON];
                $workingHours = $attData[ATTENDANCE::WORKING_HOURS];
                $late_mints = ($attData[ATTENDANCE::IS_LATE_ENTRY]) ? $attData[ATTENDANCE::LATE_MINTS] : '';
                $late_approval = (($attData[ATTENDANCE::IS_LATE_ENTRY]) && ($attData[ATTENDANCE::ADMIN_APPROVAL_FOR_LATE_ENTRY] == 1)) ? ' <i class="text-success">[APPROVED]</i>' : '';
                break;
        }
    }
    $getBirthdayDetails = getData(Table::EMPLOYEE_DETAILS, [
        EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH,
        EMPLOYEE_DETAILS::ID,
        EMPLOYEE_DETAILS::EMPLOYEE_NAME
    ],[
        EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
        EMPLOYEE_DETAILS::ACTIVE => 1,
        EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
    ]);
    if (count($getBirthdayDetails)>0) {
        foreach ($getBirthdayDetails as $bk => $bv) {
            if ($bv[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH] != "") {
                $bd = getFormattedDateTime($bv[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH], 'd');
                $bm = getFormattedDateTime($bv[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH], 'm');
                if ((getToday(false, 'd') == $bd) && (getToday(false, 'm') == $bm)) {
                    $birthday_emp_id[] = $bv[EMPLOYEE_DETAILS::ID];
                    $birthday_employees[] = $bv[EMPLOYEE_DETAILS::EMPLOYEE_NAME];
                }
            }
        }
    }
    // rip($birthday_employees);
    switch ($_SESSION[USER_TYPE]) {
        case SADMIN:
            if (count($birthday_emp_id)>0) {
                // $dashboard_notification .= "Cheer up your Employee for the day!";
                // foreach ($birthday_employees as $kk => $vv) {
                //     if ($vv != $_SESSION[USERNAME]) {
                //         $dashboard_notification .= "<b>".$vv;
                //         if (($kk + 1) != (count($birthday_employees))) {
                //             $dashboard_notification .= ", ";
                //         }
                //         $dashboard_notification .= "</b>";
                //     }
                // }
                // $dashboard_notification .= " for a very Happy Birth Day !";

                $dashboard_notification .= " Let's come together to make ";
                    // $dashboard_notification .= ((count($birthday_emp_id))>1) ? "s: " : ": ";
                    $other_arr = [];
                    foreach ($birthday_employees as $kk => $vv) {
                        $other_arr[] = $vv;
                        // $dashboard_notification .= "<b>".$vv;
                        // if (($kk + 1) != (count($birthday_employees))) {
                        //     $dashboard_notification .= ", ";
                        // }
                        // $dashboard_notification .= "</b>";
                    }
                    $dashboard_notification .= "<b>".implode(", ",$other_arr)."</b>";
                    $dashboard_notification .= "'s birthday extra special and memorable with warm wishes!";
            }
            break;
        
        default:
            if (count($birthday_emp_id)>0) {
                if (in_array($_SESSION[EMPLOYEE_ID], $birthday_emp_id)) {
                    $dashboard_notification = "Wishing you a day filled with joy and success, <b>Happy Birthday!</b>";
                    if (count($birthday_emp_id)>1) {
                        $dashboard_notification .= " Let's come together to make ";
                        // $dashboard_notification .= ((count($birthday_emp_id)-1)>1) ? "s: " : ": ";
                        $other_arr = [];
                        foreach ($birthday_employees as $kk => $vv) {
                            if ($vv != $_SESSION[USERNAME]) {
                                // $dashboard_notification .= "<b>".implode(", ",$birthday_employees);
                                $other_arr[] = $vv;
                            }
                            // if ((($kk+1) != (count($birthday_employees))) && (($birthday_employees[($kk + 1)]) != $_SESSION[USERNAME]) && ((($birthday_employees[$kk]) != $_SESSION[USERNAME]) && (($kk+1) < (count($birthday_employees)))) ) {
                            //     $dashboard_notification .= ", ";
                            // }
                            // $dashboard_notification .= " </b>";
                        }
                        $dashboard_notification .= "<b>".implode(", ",$other_arr)."</b>";
                        $dashboard_notification .= "'s birthday extra special and memorable with warm wishes as well!";
                    }
                } else {
                    $dashboard_notification .= "Let's come together to make ";
                    // $dashboard_notification .= ((count($birthday_emp_id))>1) ? "s: " : ": ";
                    $other_arr = [];
                    foreach ($birthday_employees as $kk => $vv) {
                        $other_arr[] = $vv;
                        // $dashboard_notification .= "<b>".$vv;
                        // if (($kk + 1) != (count($birthday_employees))) {
                        //     $dashboard_notification .= ", ";
                        // }
                        // $dashboard_notification .= "</b>";
                    }
                    $dashboard_notification .= "<b>".implode(", ",$other_arr)."</b>";
                    $dashboard_notification .= "'s birthday extra special and memorable with warm wishes!";
                }
            }
            break;
    }
?>
<!-- Page Wrapper -->
<div id="wrapper">

        
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column mr-0">

    <!-- Main Content -->
    <div id="content mt-0">
        
        <!-- Begin Page Content -->
        <div class="container-fluid pr-1">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                        class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
            </div>
        <?php if(getToday(false) == SOFTWARE_UPDATE_DATE): ?>
        <!-- <div class="alert alert-success" role="alert">
            Check it out! The software has been <b>updated.</b>
        </div> -->

        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Check it out! CRM has been <b>updated.</b>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <?php if($dashboard_notification != ""): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert" id="dashboard_notification">
            <?=$dashboard_notification;?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
            <?php if($_SESSION[USER_TYPE] != SADMIN): ?>
            <!-- Attendance row start here -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #1E1D1D;">Attendance</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <?=getSpinner(true, "attendance_loader")?>
                            <div class="chart-area d-none">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                            <div class="row" id="attendance_row">
                                <?php 
                                    switch ($attCycle) {
                                        case CYCLE_NOT_STARTED:
                                ?>
                                            <div class="col-md-3 col-lg-3 col-sm-12">
                                                <button class="btn btn-success" type="button" id="attendance_btn" onclick="recordAttendance();" data-att="<?=$attCycle?>" style="
                                                    border-radius: 50%;
                                                    padding: 42px;
                                                    padding-left: 30px;
                                                    padding-right: 30px;
                                                ">LOG IN</button>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-12" style="padding: 30px;">
                                                <i class="fas fa-2x fa-arrow-circle-left"></i>
                                            </div>
                                            <div class="col-md-7 col-lg-7 col-sm-12 text-left" style="padding: 30px;">
                                                <span id="live_date_time"><?=getFormattedDateTime(getToday(), LONG_DATE_TIME_FORMAT);?></span><br>
                                                <small class="text-muted" id="attendance_msg">
                                                    <b>*</b>&nbsp;<i class="text-danger">Click on the button to record your attendance.</i>
                                                </small>
                                            </div>
                                <?php
                                            break;
                                        case CYCLE_ACTIVE:
                                ?>
                                            <div class="col-md-3 col-lg-3 col-sm-12">
                                                <button class="btn btn-danger" type="button" id="attendance_btn" onclick="recordAttendance();" data-att="<?=$attCycle?>" style="
                                                    border-radius: 50%;
                                                    padding: 50px;
                                                    padding-left: 30px;
                                                    padding-right: 30px;
                                                ">LOG OFF</button>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-sm-12" style="padding: 30px; margin-top: 10px;">
                                                <i class="fas fa-2x fa-arrow-circle-left"></i>
                                            </div>
                                            <div class="col-md-7 col-lg-7 col-sm-12 text-left">
                                                <span id="live_date_time"><?=getFormattedDateTime(getToday(), LONG_DATE_TIME_FORMAT);?></span><br>
                                                <small class="text-muted" id="attendance_msg">
                                                    <b>*</b>&nbsp;<i class="text-danger">Click on the button to Log Off.</i>
                                                </small><br>
                                                <small class="text-muted" id="reporting_time">
                                                    <b>Reporting Time:</b>&nbsp;<i class="text-success"><?=getFormattedDateTime($reportingTime, LONG_TIME_FORMAT)?></i>
                                                </small><br>
                                                <?php if($late_mints != ''): ?>
                                                    <small class="text-muted" id="late_mints_row">
                                                        <b class="text-danger">You are Late of:</b>&nbsp;<i class="text-danger"><?=$late_mints?></i><?=$late_approval?>
                                                    </small><br>
                                                <?php endif; ?>
                                                <small class="text-muted" id="working_hours">
                                                    <b>Working Hours:</b>&nbsp;<i class="text-success"><?=$workingHours?> Hrs.</i>
                                                </small>
                                            </div>
                                <?php
                                            break;
                                        case CYCLE_CLOSED:
                                ?>
                                            <div class="col-md-2 col-lg-2 col-sm-12" style="padding: 30px;">
                                                <!-- <i class="fas fa-2x fa-arrow-circle-left"></i> -->
                                            </div>
                                            <div class="col-md-3 col-lg-3 col-sm-12">
                                                <button class="btn btn-success" type="button" onclick="javascript:void(0);" data-att="<?=$attCycle?>" style="
                                                    border-radius: 50%;
                                                    padding: 30px;
                                                    padding-left: 30px;
                                                    padding-right: 30px;
                                                "><i class="fas fa-2x fa-check"></i></button>
                                            </div>
                                            <div class="col-md-7 col-lg-7 col-sm-12 text-left" style="margin-top: 10px;">
                                                <span id="live_date_time"><?=getFormattedDateTime(getToday(), LONG_DATE_TIME_FORMAT);?></span><br>
                                                <small class="text-muted" id="attendance_msg">
                                                    <b>*</b>&nbsp;<i class="text-success">Your Attendance has been recorded for today.</i>
                                                </small><br>
                                                <small class="text-muted" id="working_hours">
                                                    <b>Working Hours:</b>&nbsp;<i class="text-success"><?=$workingHours?> Hrs.</i>
                                                </small>
                                            </div>
                                <?php
                                            break;
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Attendance row end here -->
            <?php endif; ?>
            <!-- Content Row -->
            <div class="row">
                <?php 
                switch ($_SESSION[USER_TYPE]) {
                    case ADMIN:
                    case SADMIN:
                    $recent_leave_count = $monthly_pending_leave_count = $uploaded_payslip_monthly = $active_payslip_monthly = $active_employees = $uploaded_payslip_percent = 0;
                    $recent_leave_tr = "";
                    $getLeaveData = getData(Table::LEAVE, [
                        LEAVE::LEAVE_SUBJECT,
                        LEAVE::LEAVE_APPLY_DATE,
                        LEAVE::LEAVE_DATES,
                        LEAVE::LEAVE_MONTH,
                        LEAVE::LEAVE_YEAR,
                        LEAVE::EMPLOYEE_ID,
                        LEAVE::ACTION_TAKEN_STATUS,
                        LEAVE::ADMIN_ACTION_TAKEN_STATUS
                    ],[
                        LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                        LEAVE::STATUS => ACTIVE_STATUS,
                        LEAVE::ACTION_TAKEN_STATUS => APPLIED
                    ],[],[],[LEAVE::ID],"DESC");
                    $getPayslipData = getData(Table::PAY_SLIP, [
                        PAY_SLIP::ACTIVE,
                        PAY_SLIP::CREATION_DATE
                    ], [
                        PAY_SLIP::STATUS => ACTIVE_STATUS,
                        PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID]
                    ]);
                    $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
                        EMPLOYEE_DETAILS::ACTIVE
                    ], [
                        EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
                        EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID]
                    ]);
                    if (count($getLeaveData)>0) {
                        foreach ($getLeaveData as $key => $value) {
                            //calculating new leave requests (within past 3 days)
                            if (((getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y')))) && ($value[LEAVE::ACTION_TAKEN_STATUS] == APPLIED) && ($value[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == 0)) {
                                $recent_leave_count ++;
                                $new = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">New</span>';
                                $employee_details = $employee_name = $employee_department = $employee_designation = "";
                                $getEmpDetails = getData(Table::EMPLOYEE_DETAILS, [
                                    EMPLOYEE_DETAILS::ID,
                                    EMPLOYEE_DETAILS::DEPARTMENT_ID,
                                    EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID,
                                    EMPLOYEE_DETAILS::EMPLOYEE_NAME
                                ], [
                                    EMPLOYEE_DETAILS::ID => $value[LEAVE::EMPLOYEE_ID],
                                    EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                                    EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
                                ]);
                                if (count($getEmpDetails)>0) {
                                    $employee_name = $getEmpDetails[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME];
                                    $getDepartment = getData(Table::DEPARTMENTS, [
                                        DEPARTMENTS::DEPARTMENT_NAME
                                    ], [
                                        DEPARTMENTS::ID => $getEmpDetails[0][EMPLOYEE_DETAILS::DEPARTMENT_ID],
                                        DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
                                        DEPARTMENTS::STATUS => ACTIVE_STATUS
                                    ]);
                                    if (count($getDepartment)>0) {
                                        $employee_department = $getDepartment[0][DEPARTMENTS::DEPARTMENT_NAME];
                                    }
                                    $getDesignation = getData(Table::DESIGNATIONS, [
                                        DESIGNATIONS::DESIGNATION_TITLE
                                    ], [
                                        DESIGNATIONS::ID => $getEmpDetails[0][EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID],
                                        DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                                        DESIGNATIONS::STATUS => ACTIVE_STATUS
                                    ]);
                                    if (count($getDesignation)>0) {
                                        $employee_designation = $getDesignation[0][DESIGNATIONS::DESIGNATION_TITLE];
                                    }
                                }
                                $employee_details = '<span>Employee Name: <b>'.$employee_name.'</b></span><br>';
                                $employee_details .= '<span>Department: <b>'.$employee_department.'</b></span><br>';
                                $employee_details .= '<span>Designation: <b>'.$employee_designation.'</b></span>';
                                $subject = (!empty($value[LEAVE::LEAVE_SUBJECT])) ? altRealEscape($value[LEAVE::LEAVE_SUBJECT]) : EMPTY_VALUE;
                                $leave_dates = (!empty($value[LEAVE::LEAVE_DATES])) ? altRealEscape($value[LEAVE::LEAVE_DATES]).' of '.ALL_MONTHS_NAME[$value[LEAVE::LEAVE_MONTH]].', '.$value[LEAVE::LEAVE_YEAR] : EMPTY_VALUE;
                                $leave_apply_date = getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE], LONG_DATE_TIME_FORMAT);
                                $recent_leave_tr .= '<tr style="font-size: 12px; cursor: pointer;">
                                    <td>'.($key + 1).$new.'</td>
                                    <td>'.$subject.'</td>
                                    <td>'.$leave_dates.'</td>
                                    <td style="font-size: 11px; font-weight: bold;">'.$leave_apply_date.'</td>
                                    <td class="text-left" style="font-size: smaller;">'.$employee_details.'</td>
                                </tr>';
                            } else {
                                $recent_leave_tr = '<tr class="animated fadeInDown">
                                <td colspan="5">
                                    <div class="alert alert-danger" role="alert">
                                        No New Leaves Applied !
                                    </div>
                                </td>
                            </tr>';
                            }
                            //calculating pending leave requests (within current month)
                            if ((getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($value[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y')))) {
                                $monthly_pending_leave_count ++;
                            }
                            
                        }
                    } else {
                        $recent_leave_tr = '<tr class="animated fadeInDown">
                        <td colspan="5">
                            <div class="alert alert-danger" role="alert">
                                No New Leaves Applied !
                            </div>
                        </td>
                    </tr>';
                    }
                    if (count($getPayslipData)>0) {
                        foreach ($getPayslipData as $key => $psv) {
                            if ((getFormattedDateTime($psv[PAY_SLIP::CREATION_DATE],'m')) == getToday(false, 'm') && (getFormattedDateTime($psv[PAY_SLIP::CREATION_DATE],'Y') == (getToday(false, 'Y')))) {
                                $uploaded_payslip_monthly++;
                                if ($psv[PAY_SLIP::ACTIVE] == 1) {
                                    $active_payslip_monthly++;
                                }
                            }
                        }
                    }
                    if (count($getEmpData)>0) {
                        foreach ($getEmpData as $key => $empv) {
                            if ($empv[EMPLOYEE_DETAILS::ACTIVE] == 1) {
                                $active_employees++;
                            }
                        }
                    }
                    if (($uploaded_payslip_monthly != 0) && ($active_employees != 0)) {
                        $uploaded_payslip_percent = (((int)($uploaded_payslip_monthly) * 100) / (int)($active_employees));
                    }
                    if($_SESSION[USER_TYPE] != SADMIN):
                ?>
                <!-- Leave Applications (Recent) Card  -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?>manage-leaves');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1 <?php if($recent_leave_count > 0): ?>blinking <?php endif; ?>">
                                        New Leave Applications</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">&nbsp;<?=$recent_leave_count?></div>
                                </div>
                                <div class="col-auto">
                                    <!-- <i class="fas fa-comments fa-2x text-gray-300"></i> -->
                                    <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($_SESSION[USER_TYPE] == SADMIN):
                    $msg_count = $present_emp = $att_percent = 0;
                    $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                    ], [
                        NEW_MESSAGE_LOG::NEW_MSG => 1,
                        NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                        NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID]
                    ]);
                    $getAttData = getData(Table::ATTENDANCE, [
                        ATTENDANCE::STATUS
                    ], [
                        ATTENDANCE::ACTIVE => CYCLE_ACTIVE,
                        ATTENDANCE::ATTENDANCE_DATE => getToday(false),
                        ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
                        ATTENDANCE::STATUS => ACTIVE_STATUS
                    ]);
                    if (count($getNewMsg)>0) {
                        if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID])) {
                            $msg_count = count($getNewMsg);
                        }
                    }
                    if (count($getAttData)>0) {
                        $getActiveEmp = getData(Table::EMPLOYEE_DETAILS, [
                            EMPLOYEE_DETAILS::EMPLOYEE_NAME
                        ], [
                            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
                            EMPLOYEE_DETAILS::ACTIVE => 1
                        ]);
                        $present_emp = count($getAttData);
                        $att_percent = (((int)(count($getAttData)) * 100) / (int)(count($getActiveEmp)));
                    }
                ?>
                <!-- Message Card  -->
                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center" onclick="window.open('<?=HOST_URL?>sadmin-chat');">
                                <div class="col mr-2" style="cursor: pointer;">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        New Message</div>
                                    <div class="dashboard_chat_count h5 mb-0 font-weight-bold text-gray-800<?php if($msg_count>0): ?> blinking<?php endif; ?>">&nbsp;<?=$msg_count?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    <!-- <i class="fas fa-calendar-day fa-2x text-gray-300"></i> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Attendance Card start-->
                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?>sadmin-datewise-attendance');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Attendance 
                                        <span class="text-danger"><?=getToday(false, LONG_DATE_FORMAT)?></span>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$att_percent?>%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: <?=$att_percent?>%" aria-valuenow="<?=$att_percent?>" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="text-xs font-weight-bold text-success text-uppercase" style="font-size: 11px;">
                                                Present Employees</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$present_emp;?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <!-- Attendance Card end-->
                <?php endif; ?>

                <!-- Leave Applications (Monthly) Card  -->
                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open(<?php if($_SESSION[USER_TYPE] == SADMIN): ?>'<?=HOST_URL?>sadmin-manage-leaves'<?php else: ?>'<?=HOST_URL?>manage-leaves'<?php endif; ?>);">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 14px;">
                                        Pending Leave Applications (Monthly)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"></i>&nbsp;<?=$monthly_pending_leave_count?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if($_SESSION[USER_TYPE] != SADMIN): ?>
                <!-- Uploaded Payslip -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?>payroll');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Uploaded Payslip
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?=$uploaded_payslip_percent?>%</div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-info" role="progressbar"
                                                    style="width: <?=$uploaded_payslip_percent?>%" aria-valuenow="<?=$uploaded_payslip_percent?>" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <div class="text-xs font-weight-bold text-success text-uppercase" style="font-size: 11px;">
                                                Active Payslips</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$active_payslip_monthly;?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Active Employees -->
                <div class="col-xl-3 col-lg-3 col-md-3 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open(<?php if($_SESSION[USER_TYPE] == SADMIN): ?>'<?=HOST_URL?>sadmin-employees'<?php else: ?>'<?=HOST_URL?>employees'<?php endif; ?>);">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Active Employees</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$active_employees;?></div>
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 11px;">
                                        Total Employees</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?=count($getEmpData);?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        break;
                    case EMPLOYEE:
                    case MANAGER:
                    $new_leave_response = $leave_monthly = $leave_yearly = $payslip = $notice = $msg_count = 0;
                    $notice_tr = $new = "";
                    $getleavedata = getData(Table::LEAVE, [
                        LEAVE::LEAVE_DATES,
                        LEAVE::LEAVE_SUBJECT,
                        LEAVE::LEAVE_APPLY_DATE,
                        LEAVE::RESPONSE,
                        LEAVE::ACTION_TAKEN_STATUS
                    ], [
                        LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                        LEAVE::STATUS => ACTIVE_STATUS,
                        LEAVE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID]
                    ]);
                    $getPayslipData = getData(Table::PAY_SLIP, [
                        PAY_SLIP::LAST_ACTIVE_DATE
                    ], [
                        PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
                        PAY_SLIP::ACTIVE => 1,
                        PAY_SLIP::STATUS => ACTIVE_STATUS,
                        PAY_SLIP::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID]
                    ]);
                    $getNotice = getData(Table::NOTICES, [
                        NOTICES::LAST_ACTIVE_DATE,
                        NOTICES::NOTICE_SUBJECT,
                        NOTICES::NOTICE_FILE
                    ], [
                        NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID],
                        NOTICES::ACTIVE => 1,
                        NOTICES::STATUS => ACTIVE_STATUS
                    ]);
                    $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                    ], [
                        NEW_MESSAGE_LOG::NEW_MSG => 1,
                        NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                        NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID]
                    ]);
                    if (count($getNewMsg)>0) {
                        if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID])) {
                            $msg_count = count($getNewMsg);
                        }
                    }
                    if (count($getleavedata)>0) {
                        foreach ($getleavedata as $k => $v) {
                            if ((getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y')))) {
                                if ($v[LEAVE::ACTION_TAKEN_STATUS] != APPLIED) {
                                    $new_leave_response++;
                                }
                            }
                            //calculating pending leave requests (within current month)
                            if (getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'m') == (getToday(false, 'm')) && (getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y')))) {
                                $leave_monthly++;
                            }
                            //calculating pending leave requests (within current month)
                            if (getFormattedDateTime($v[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y'))) {
                                $leave_yearly++;
                            }
                        }
                    }
                    if (count($getPayslipData)>0) {
                        foreach ($getPayslipData as $k => $v) {
                            //calculating pending leave requests (within current month)
                            if ((getFormattedDateTime($v[PAY_SLIP::LAST_ACTIVE_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($v[PAY_SLIP::LAST_ACTIVE_DATE],'Y') == (getToday(false, 'Y')))) {
                                $payslip++;
                            }
                        }
                    }
                    if (count($getNotice)>0) {
                        foreach ($getNotice as $k => $v) {
                            //calculating pending leave requests (within current month)
                            if ((getFormattedDateTime($v[NOTICES::LAST_ACTIVE_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($v[NOTICES::LAST_ACTIVE_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($v[NOTICES::LAST_ACTIVE_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($v[NOTICES::LAST_ACTIVE_DATE],'Y') == (getToday(false, 'Y')))) {
                                $notice++;
                                $new = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">New</span>';
                                $notice_sub = (!empty($v[NOTICES::NOTICE_SUBJECT]) || ($v[NOTICES::NOTICE_SUBJECT] != "")) ? altRealEscape($v[NOTICES::NOTICE_SUBJECT]) : EMPTY_VALUE;
                                $notice_file = (!empty($v[NOTICES::NOTICE_FILE])) ? '<a href="'. UPLOADED_NOTICE_URL.$v[NOTICES::NOTICE_FILE] .'" target="_blank" class="download">Click Here</a>' : EMPTY_VALUE;
                                $notice_tr .= '<tr>
                                    <td>'.($k+1).$new.'</td>
                                    <td>'.$notice_sub.'</td>
                                    <td>'.$notice_file.'</td>
                                    <td>'.getFormattedDateTime($v[NOTICES::LAST_ACTIVE_DATE],LONG_DATE_TIME_FORMAT).'</td>
                                </tr>';
                            }
                        }
                    } else {
                        $notice_tr = '<tr class="animated fadeInDown">
                    <td colspan="4">
                        <div class="alert alert-danger" role="alert">
                            No New Notices found !
                        </div>
                    </td>
                </tr>';
                    }
                ?>
                <!-- Message Card  -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center" onclick="window.open('<?=($_SESSION[USER_TYPE] == EMPLOYEE) ? HOST_URL.'employee-chat' : HOST_URL.'manager-chat';?>');">
                                <div class="col mr-2" style="cursor: pointer;">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        New Message</div>
                                    <div class="dashboard_chat_count h5 mb-0 font-weight-bold text-gray-800<?php if($msg_count>0): ?> blinking<?php endif; ?>">&nbsp;<?=$msg_count?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                    <!-- <i class="fas fa-calendar-day fa-2x text-gray-300"></i> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Leave Applications (Monthly) Card  -->
                <div class="col-xl-3 col-md-6 mb-4 d-none">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?>employee-view-leaves');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 14px;">
                                        Leave Applications (Monthly)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"></i>&nbsp;<?=$leave_monthly?></div>
                                </div>
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1" style="font-size: 14px;">
                                        Leave Applications (Yearly)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"></i>&nbsp;<?=$leave_yearly?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Uploaded Payslip -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?><?=($_SESSION[USER_TYPE] == MANAGER) ? 'manager' : 'employee';?>-payslip');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Payslip
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800<?php if($payslip > 0): ?> blinking<?php endif; ?>"><?=$payslip?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Notice Card -->
                <div class="col-xl-4 col-lg-4 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center cursor-pointer" onclick="window.open('<?=HOST_URL?><?=($_SESSION[USER_TYPE] == MANAGER) ? 'manager' : 'employee';?>-notices');">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Notices</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800<?php if($notice > 0): ?> blinking<?php endif; ?>"><?=$notice?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        break;
                }
                ?>
            </div>

            <!-- Cards end -->

            <!-- Content Row -->
            <div class="row">
                <!-- hidden cards start here -->
                <!-- Content Column -->
                <div class="col-lg-4 col-md-4 col-sm-12 mb-4 d-none">

                    <!-- Project Card Example -->
                    <div class="card shadow mb-4 w-40 p-3">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Projects</h6>
                        </div>
                        <div class="card-body">
                            <h4 class="small font-weight-bold">Server Migration <span
                                    class="float-right">20%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
                                    aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Sales Tracking <span
                                    class="float-right">40%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
                                    aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Customer Database <span
                                    class="float-right">60%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar" role="progressbar" style="width: 60%"
                                    aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Payout Details <span
                                    class="float-right">80%</span></h4>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 80%"
                                    aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <h4 class="small font-weight-bold">Account Setup <span
                                    class="float-right">Complete!</span></h4>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"
                                    aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mb-4 d-none">

                    <!-- Color System -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    Primary
                                    <div class="text-white-50 small">#4e73df</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-success text-white shadow">
                                <div class="card-body">
                                    Success
                                    <div class="text-white-50 small">#1cc88a</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-info text-white shadow">
                                <div class="card-body">
                                    Info
                                    <div class="text-white-50 small">#36b9cc</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-warning text-white shadow">
                                <div class="card-body">
                                    Warning
                                    <div class="text-white-50 small">#f6c23e</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-danger text-white shadow">
                                <div class="card-body">
                                    Danger
                                    <div class="text-white-50 small">#e74a3b</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-secondary text-white shadow">
                                <div class="card-body">
                                    Secondary
                                    <div class="text-white-50 small">#858796</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-light text-black shadow">
                                <div class="card-body">
                                    Light
                                    <div class="text-black-50 small">#f8f9fc</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card bg-dark text-white shadow">
                                <div class="card-body">
                                    Dark
                                    <div class="text-white-50 small">#5a5c69</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- hidden cards end here -->
                <div class="col-lg-12 col-md-12 col-sm-12">
                <?php 
                switch ($_SESSION[USER_TYPE]) {
                    case ADMIN:
                ?>
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-danger">New Leave Applications</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area d-none">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                            <a href="<?=HOST_URL?>manage-leaves" style="cursor: pointer;"><span class="badge badge-danger" style="vertical-align: super;">Click Here...</span></a>
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover text-center" style="font-size:12px;">
                                    <thead class="text-center table-warning">
                                        <tr style="text-transform: uppercase; font-size:12px;">
                                            <th>SL.</th>
                                            <th>Subject</th>
                                            <th>Leave Dates</th>
                                            <th>Apply Date</th>
                                            <th>Employee</th>
                                        </tr>
                                    </thead>
                                        <tbody onclick="window.open('<?=HOST_URL?>manage-leaves');">
                                        <?=$recent_leave_tr?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                            break;
                        case EMPLOYEE:
                        case MANAGER:
                    ?>
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #1E1D1D;">New Notices</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area d-none">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                            <!-- <a href="<?=HOST_URL?>employee-notices" style="cursor: pointer;"><span class="badge" style="vertical-align: super; background: #FF5637">Click Here...</span></a> -->
                            <div class="table-responsive">
                                <table class="table table-sm table-striped table-hover text-center" style="font-size:12px;">
                                    <thead class="text-center table-warning">
                                        <tr style="text-transform: uppercase; font-size:12px;">
                                            <th>SL.</th>
                                            <th>Subject</th>
                                            <th>File</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                        <tbody style="cursor: pointer;" onclick="window.open('<?=HOST_URL?>employee-notices');">
                                        <?=$notice_tr?>
                                        </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php
                            break;
                    }
                    ?>
                    
                </div>

            </div>

            <?php if($_SESSION[USER_TYPE] != SADMIN): ?>
            <!-- Attendance Report Start from here -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div
                            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold" style="color: #1E1D1D;">Attendance Report [Self]</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                    aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <?=getSpinner(true, "attendance_report_loader")?>
                            <div class="chart-area d-none">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-5">
                                    <label class="form_label" for="att_year_select">Select Year</label><?=getAsterics();?>
                                    <select id="att_year_select" class="form-control" onchange="getAttRecord(2);">
                                        <option value='0' disabled >--Select Year--</option>
                                        <?php 
                                            $year = getToday(false,'Y');
                                            for ($i=0; $i <= 1; $i++) {
                                                $y = ($year + $i);
                                                $selected = ($y == getToday(false, 'Y')) ? 'selected' : '';
                                                echo "<option ".$selected." value='". $y ."'>".$y."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-4 col-lg-4 col-sm-5">
                                    <label for="att_month_select" class="form_label">Select Month</label>
                                    <select class="form-control" id="att_month_select" onchange="getAttRecord(2);">
                                        <option value='0' selected disabled >--Select Month--</option>
                                    <?php 
                                        $month = getToday(false,'m');
                                        for ($i=0; $i <= count(ALL_MONTHS_NAME); $i++) { 
                                            $m = ($i+1);
                                            $selected = ($m == $month) ? 'selected' : '';
                                            echo "<option value='". $m ."'>".ALL_MONTHS_NAME[$m]."</option>";
                                        }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-2" id="attendance_report_row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped table-hover text-center attendance_report_table" style="font-size:14px;">
                                            <thead class="text-center table-warning">
                                                <tr style="text-transform: uppercase; font-size: 12px;">
                                                    <th class="cursor-pointer">SL.</th>
                                                    <th class="cursor-pointer">Date</th>
                                                    <th class="cursor-pointer">Reporting Time</th>
                                                    <th class="cursor-pointer">Log Off Time</th>
                                                    <th class="cursor-pointer">Working Hours</th>
                                                    <th class="cursor-pointer">Late Mints</th>
                                                    <th class="cursor-pointer">Early Log Off Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 12px;">
                                                <tr class="animated fadeInDown">
                                                    <td colspan="7">
                                                        <div class="alert alert-danger" role="alert">
                                                            No Attendance found ! Please select the particulars to get records.
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Attendance Report ends here -->
            <?php endif; ?>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- End of Main Content -->

    <!-- Footer -->
    <footer class="sticky-footer bg-white" style="position: fixed; bottom:0; padding-bottom:10px; width:100vw;">
        <div class="container my-auto">
            <div class="copyright text-left my-auto">
                <span><?=getToday(false,'Y')?> &copy; Copyright <?=COMPANY_BUSINESS_NAME?> </span>
            </div>
        </div>
    </footer>
    <!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->


<!-- Delete modal -->
<div class="modal animated shake" id="earlyLoggOffReason_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="staticBackdropLabel">Reason Behind the Early Log Off</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('.modal').hide();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
<?php
}
?>