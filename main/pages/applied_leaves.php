<?php 
function printContent() {
    $page_heading_text = $emp_leave_tr = $admin_leave_tr = $colspan = 7;
    $employee_name = $employee_department = $employee_designation = "";
    $reporting_manager_user_id = 0;
    $where = $employee_id_in['id'] = [0];
    switch ($_SESSION[USER_TYPE]) {
        case SADMIN:
            $where = [
                LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                LEAVE::STATUS => ACTIVE_STATUS
            ];
            $getEmpIdIn = getData(Table::EMPLOYEE_DETAILS, [
                EMPLOYEE_DETAILS::ID
            ], [
                EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                EMPLOYEE_DETAILS::ACTIVE => 1,
                EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
            ]);
            if (count($getEmpIdIn)>0) {
                foreach ($getEmpIdIn as $k => $v) {
                    $employee_id_in['id'][] = $v[EMPLOYEE_DETAILS::ID];
                }
            } else {
                $employee_id_in['id'] = [0];
            }
            break;
        case ADMIN:
            $where = [
                LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                LEAVE::STATUS => ACTIVE_STATUS
            ];
            $getEmpIdIn = getData(Table::USERS, [
                Users::EMPLOYEE_ID
            ], [
                Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                Users::ACTIVE => 1,
                Users::STATUS => ACTIVE_STATUS
            ], [
                Users::USER_TYPE => [EMPLOYEE, MANAGER]
            ]);
            if (count($getEmpIdIn)>0) {
                foreach ($getEmpIdIn as $k => $v) {
                    $employee_id_in['id'][] = $v[Users::EMPLOYEE_ID];
                }
            } else {
                $employee_id_in['id'] = [0];
            }
            // rip($employee_id_in['id']);
            // exit;
            break;
        case EMPLOYEE:
            $where = [
                LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                LEAVE::STATUS => ACTIVE_STATUS,
                LEAVE::USER_ID => $_SESSION[RID],
                LEAVE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID]
            ];
            break;
        case MANAGER:
            $where = [
                LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
                LEAVE::STATUS => ACTIVE_STATUS
            ];
            $getEmpIdIn = getData(Table::EMPLOYEE_REPORTING_MANAGER, [
                EMPLOYEE_REPORTING_MANAGER::EMPLOYEE_ID
            ], [
                EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID => $_SESSION[RID],
                EMPLOYEE_REPORTING_MANAGER::CLIENT_ID => $_SESSION[CLIENT_ID],
                EMPLOYEE_REPORTING_MANAGER::STATUS => ACTIVE_STATUS
            ]);
            if (count($getEmpIdIn)>0) {
                foreach ($getEmpIdIn as $k => $v) {
                    $employee_id_in['id'][] = $v[Users::EMPLOYEE_ID];
                }
            } else {
                $employee_id_in['id'] = [0];
            }
            // rip($getEmpIdIn);
            // exit;
            break;
    }
    // echo $employee_id_in['id'];
    $getLeaveData = getData(Table::LEAVE, [
        LEAVE::ID,
        LEAVE::ACTION_TAKEN_STATUS,
        LEAVE::LEAVE_APPLY_DATE,
        LEAVE::LEAVE_DATES,
        LEAVE::LEAVE_MATTER,
        LEAVE::LEAVE_SUBJECT,
        LEAVE::LEAVE_MONTH,
        LEAVE::LEAVE_YEAR,
        LEAVE::RESPONSE,
        LEAVE::RESPONSE_BY_USER_ID,
        LEAVE::RESPONSE_DATE,
        LEAVE::EMPLOYEE_ID,
        LEAVE::CREATION_DATE,
        LEAVE::USER_ID,
        LEAVE::REFERENCE_DOC,
        LEAVE::ADMIN_RESPONSE,
        LEAVE::ADMIN_USER_ID,
        LEAVE::ADMIN_RESPONSE_DATE,
        LEAVE::ADMIN_ACTION_TAKEN_STATUS
    ], $where,($_SESSION[USER_TYPE] != EMPLOYEE) ? [
        LEAVE::EMPLOYEE_ID => $employee_id_in['id']
    ] : [],[],[LEAVE::ID],"DESC");
    if (count($getLeaveData)>0) {
        foreach ($getLeaveData as $k => $emp_ldata) {
            $slno = ($k + 1);
            $new = '';
            // if ((getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'m') == getToday(false, 'm')) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'Y') == getToday(false, 'Y')) && ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == APPLIED)) {
            if ((getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'Y') == (getToday(false, 'Y')))) {
                if (($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == APPLIED) && ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == 0)) {
                    $new = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">New</span>';
                }
            }
            $subject = (!empty($emp_ldata[LEAVE::LEAVE_SUBJECT])) ? altRealEscape($emp_ldata[LEAVE::LEAVE_SUBJECT]) : EMPTY_VALUE;
            $leave_dates = (!empty($emp_ldata[LEAVE::LEAVE_DATES])) ? altRealEscape($emp_ldata[LEAVE::LEAVE_DATES]).' of '.ALL_MONTHS_NAME[$emp_ldata[LEAVE::LEAVE_MONTH]].', '.$emp_ldata[LEAVE::LEAVE_YEAR] : EMPTY_VALUE;
            $leave_apply_date = getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE], LONG_DATE_TIME_FORMAT);
            $status_text = LEAVE_ACTION_STATUS[$emp_ldata[LEAVE::ACTION_TAKEN_STATUS]];
            $status = $response_by = $response_by_user_type = $final_status = "";
            $ref_doc = EMPTY_VALUE;
            if ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == APPLIED) {
                $status = '<span class="badge badge-light">'.$status_text.'</span>';
            }
            if ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == PROCESSING) {
                $status = '<span class="badge badge-info">'.$status_text.'</span>';
            }
            if ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == ON_HOLD) {
                $status = '<span class="badge badge-warning">'.$status_text.'</span>';
            }
            if ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == ACCEPTED) {
                $status = '<span class="badge badge-success">'.$status_text.'</span>';
            }
            if ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == REJECTED) {
                $status = '<span class="badge badge-danger">'.$status_text.'</span>';
            }
            if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] != 0) {
                $admin_status_text = LEAVE_ACTION_STATUS[$emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS]];
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == APPLIED) {
                    $final_status = '<span class="badge badge-light">'.$admin_status_text.'</span>';
                }
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == PROCESSING) {
                    $final_status = '<span class="badge badge-info">'.$admin_status_text.'</span>';
                }
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == ON_HOLD) {
                    $final_status = '<span class="badge badge-warning">'.$admin_status_text.'</span>';
                }
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == ACCEPTED) {
                    $final_status = '<span class="badge badge-success">'.$admin_status_text.'</span>';
                }
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] == REJECTED) {
                    $final_status = '<span class="badge badge-danger">'.$admin_status_text.'</span>';
                }
            }
            if (($emp_ldata[LEAVE::RESPONSE] != "") || ($emp_ldata[LEAVE::RESPONSE] != null) || ($emp_ldata[LEAVE::RESPONSE_BY_USER_ID] != 0)) {
                $getUserData = getData(Table::USERS, [Users::USER_TYPE, Users::NAME], [
                    Users::ID => $emp_ldata[LEAVE::RESPONSE_BY_USER_ID],
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    Users::STATUS => ACTIVE_STATUS
                ]);
                // $response_by = EMPTY_VALUE;
                // rip($getUserData);
                // exit;
                if (count($getUserData)>0) {
                 $response_by = $getUserData[0][Users::NAME].' ( <b>'. USERS[$getUserData[0][Users::USER_TYPE]].'</b> )';
                 $response_by_user_type = $getUserData[0][Users::USER_TYPE];
                }
                if ($emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS] != 0) {
                    $getUserData = getData(Table::USERS, [Users::USER_TYPE, Users::NAME], [
                        Users::ID => $emp_ldata[LEAVE::ADMIN_USER_ID],
                        Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                        Users::STATUS => ACTIVE_STATUS
                    ]);
                    if (count($getUserData)>0) {
                     $response_by = $getUserData[0][Users::NAME].' ( <b>'. USERS[$getUserData[0][Users::USER_TYPE]].'</b> )';
                     $response_by_user_type = $getUserData[0][Users::USER_TYPE];
                    }
                }
                
            } else {
                $getUserData = getData(Table::USERS, [Users::USER_TYPE, Users::NAME], [
                    Users::ID => $emp_ldata[LEAVE::ADMIN_USER_ID],
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    Users::STATUS => ACTIVE_STATUS
                ]);
                // rip($getUserData);
                // exit;
                if (count($getUserData)>0) {
                    $response_by = $getUserData[0][Users::NAME].' ( <b>'. USERS[$getUserData[0][Users::USER_TYPE]].'</b> )';
                    $response_by_user_type = $getUserData[0][Users::USER_TYPE];
                }
            }
            if (($emp_ldata[LEAVE::REFERENCE_DOC] != "") || (!empty($emp_ldata[LEAVE::REFERENCE_DOC]))) {
                $ref_doc = '<a href="'. UPLOADED_LEAVE_DOC_URL.altRealEscape($emp_ldata[LEAVE::REFERENCE_DOC]) .'" target="_blank" class="download">View Document</a>';
            }
            $getReportManager = getData(Table::EMPLOYEE_REPORTING_MANAGER, [
                EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID
            ], [
                EMPLOYEE_REPORTING_MANAGER::CLIENT_ID => $_SESSION[CLIENT_ID],
                EMPLOYEE_REPORTING_MANAGER::STATUS => ACTIVE_STATUS,
                EMPLOYEE_REPORTING_MANAGER::EMPLOYEE_ID => $emp_ldata[LEAVE::EMPLOYEE_ID]
            ]);
            if (count($getReportManager)>0) {
                $reporting_manager_user_id = $getReportManager[0][EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID];
            }
            //Fetching data for admin interface start
            if (($_SESSION[USER_TYPE] == ADMIN) || ($_SESSION[USER_TYPE] == MANAGER) || ($_SESSION[USER_TYPE] == SADMIN)) {
                $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
                    EMPLOYEE_DETAILS::ID,
                    EMPLOYEE_DETAILS::DEPARTMENT_ID,
                    EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID,
                    EMPLOYEE_DETAILS::EMPLOYEE_NAME
                ], [
                    EMPLOYEE_DETAILS::ID => $emp_ldata[LEAVE::EMPLOYEE_ID],
                    EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                    EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
                ]);
                if (count($getEmpData)>0) {
                    $employee_name = $getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME];
                    $getDepartment = getData(Table::DEPARTMENTS, [
                        DEPARTMENTS::DEPARTMENT_NAME
                    ], [
                        DEPARTMENTS::ID => $getEmpData[0][EMPLOYEE_DETAILS::DEPARTMENT_ID],
                        DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
                        DEPARTMENTS::STATUS => ACTIVE_STATUS
                    ]);
                    if (count($getDepartment)>0) {
                        $employee_department = $getDepartment[0][DEPARTMENTS::DEPARTMENT_NAME];
                    }
                    $getDesignation = getData(Table::DESIGNATIONS, [
                        DESIGNATIONS::DESIGNATION_TITLE
                    ], [
                        DESIGNATIONS::ID => $getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID],
                        DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                        DESIGNATIONS::STATUS => ACTIVE_STATUS
                    ]);
                    if (count($getDesignation)>0) {
                        $employee_designation = $getDesignation[0][DESIGNATIONS::DESIGNATION_TITLE];
                    }
                }
                    
            }
            //Fetching data for admin interface end
            // echo preg_replace("/\r\n\r\n/", "", html_entity_decode(htmlspecialchars_decode($emp_ldata[LEAVE::LEAVE_MATTER])));
            // exit;
            $decodedData = html_entity_decode($emp_ldata[LEAVE::LEAVE_MATTER]);
            // echo str_replace('\'', '\\\'', $decodedData);
            // echo stripslashes($decodedData);
            $text_cleaned = cleanText($decodedData);
            // $text_for_js = htmlspecialchars(json_encode($text_cleaned), ENT_QUOTES, 'UTF-8');
            // echo $text_cleaned;
            // exit;
            $data = [
                LEAVE::ID                   =>   $emp_ldata[LEAVE::ID],
                LEAVE::ACTION_TAKEN_STATUS  =>   $status,
                'action_taken_status_id'    =>   $emp_ldata[LEAVE::ACTION_TAKEN_STATUS],
                LEAVE::LEAVE_APPLY_DATE     =>   $leave_apply_date,
                LEAVE::LEAVE_DATES          =>   $leave_dates,
                // LEAVE::LEAVE_MATTER         =>   $emp_ldata[LEAVE::LEAVE_MATTER],
                LEAVE::LEAVE_MATTER         =>   $text_cleaned,
                LEAVE::LEAVE_SUBJECT        =>   $subject,
                LEAVE::LEAVE_MONTH          =>   $emp_ldata[LEAVE::LEAVE_MONTH],
                LEAVE::LEAVE_YEAR           =>   $emp_ldata[LEAVE::LEAVE_YEAR],
                LEAVE::RESPONSE             =>   $emp_ldata[LEAVE::RESPONSE],
                LEAVE::RESPONSE_BY_USER_ID  =>   $response_by,
                'response_by_user_type'     =>   $response_by_user_type,
                LEAVE::RESPONSE_DATE        =>   getFormattedDateTime($emp_ldata[LEAVE::RESPONSE_DATE],LONG_DATE_TIME_FORMAT),
                LEAVE::CREATION_DATE        =>   $emp_ldata[LEAVE::CREATION_DATE],
                LEAVE::REFERENCE_DOC        =>   $ref_doc,
                LEAVE::ADMIN_ACTION_TAKEN_STATUS => $emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS],
                LEAVE::ADMIN_RESPONSE       => $emp_ldata[LEAVE::ADMIN_RESPONSE],
                LEAVE::ADMIN_RESPONSE_DATE  => getFormattedDateTime($emp_ldata[LEAVE::ADMIN_RESPONSE_DATE], LONG_DATE_TIME_FORMAT),
                'final_status'              => $final_status
            ];
            $data['reporting_manager_user_id'] = $reporting_manager_user_id;
            $data['response_by_admin_id'] = $emp_ldata[LEAVE::ADMIN_USER_ID];
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                case SADMIN:
                    $data['employee_name'] = $employee_name;
                    $data['employee_department'] = $employee_department;
                    $data['employee_designation'] = $employee_designation;
                    // $data['final_status'] = $final_status;
                    // $data[LEAVE::ADMIN_ACTION_TAKEN_STATUS] = $emp_ldata[LEAVE::ADMIN_ACTION_TAKEN_STATUS];
                    // $data[LEAVE::ADMIN_RESPONSE] = $emp_ldata[LEAVE::ADMIN_RESPONSE];
                    // $data[LEAVE::ADMIN_RESPONSE_DATE] = $emp_ldata[LEAVE::ADMIN_RESPONSE_DATE];

                    $status_options = '<option value="0" selected disabled>--- Select Status ---</option>';
                    foreach (LEAVE_ACTION_STATUS as $key => $las) {
                        $status_options .= '<option value="'.$key.'">'.$las.'</option>';
                    }
                    // $status_options = LEAVE_ACTION_STATUS;
                    $data['status_options'] = $status_options;
                    $employee_details = '<span>Employee Name: <b>'.$employee_name.'</b></span><br>';
                    $employee_details .= '<span>Department: <b>'.$employee_department.'</b></span><br>';
                    $employee_details .= '<span>Designation: <b>'.$employee_designation.'</b></span>';
                    $action = '
                    <div class="row" style="display:flex; justify-content: center;">
                        <div class="col-6 text-info view_leave_details" style="font-size:18px; cursor: pointer;" onclick=\'viewLeaveDetails("admin",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'><i class="fas fa-info-circle"></i></div>
                    </div>
                    ';
                    $colspan = 7;
                    $emp_leave_tr .= '<tr id="leave_'.$emp_ldata[LEAVE::ID].'" style="font-size: 12px; cursor: pointer;" onclick=\'viewLeaveDetails("admin",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'>
                    <td>'.$slno.$new.'</td>
                    <td>'.$subject.'</td>
                    <td>'.$leave_dates.'</td>
                    <td style="font-size: 11px; font-weight: bold;">'.$leave_apply_date.'</td>
                    <td class="text-left">'.$employee_details.'</td>
                    <td>'.(($final_status != '') ? $final_status : $status).'</td>
                    <td>'.$action.'</td>
                    </tr>';
                    break;
                case MANAGER:
                    $data['employee_name'] = $employee_name;
                    $data['employee_department'] = $employee_department;
                    $data['employee_designation'] = $employee_designation;
                    $status_options = '<option value="0" selected disabled>--- Select Status ---</option>';
                    foreach (LEAVE_ACTION_STATUS as $key => $las) {
                        $status_options .= '<option value="'.$key.'">'.$las.'</option>';
                    }
                    // $status_options = LEAVE_ACTION_STATUS;
                    $data['status_options'] = $status_options;
                    $employee_details = '<span>Employee Name: <b>'.$employee_name.'</b></span><br>';
                    $employee_details .= '<span>Department: <b>'.$employee_department.'</b></span><br>';
                    $employee_details .= '<span>Designation: <b>'.$employee_designation.'</b></span>';
                    $action = '
                    <div class="row" style="display:flex; justify-content: center;">
                        <div class="col-6 text-info view_leave_details" style="font-size:18px; cursor: pointer;" onclick=\'viewLeaveDetails("manager",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'><i class="fas fa-info-circle"></i></div>
                    </div>
                    ';
                    $colspan = 7;
                    $emp_leave_tr .= '<tr id="leave_'.$emp_ldata[LEAVE::ID].'" style="font-size: 12px; cursor: pointer;" onclick=\'viewLeaveDetails("manager",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'>
                    <td>'.$slno.$new.'</td>
                    <td>'.$subject.'</td>
                    <td>'.$leave_dates.'</td>
                    <td style="font-size: 11px; font-weight: bold;">'.$leave_apply_date.'</td>
                    <td class="text-left">'.$employee_details.'</td>
                    <td>'.(($final_status != '') ? $final_status : $status).'</td>
                    <td>'.$action.'</td>
                    </tr>';
                    break;
                case EMPLOYEE:
                    $action = '
                    <div class="row" style="display:flex; justify-content: center;">
                        <div class="col-6 text-info view_leave_details" style="font-size:18px; cursor: pointer;" onclick=\'viewLeaveDetails("employee",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'><i class="fas fa-info-circle"></i></div>
                    </div>
                    ';
                    $colspan = 6;
                    $emp_leave_tr .= '<tr id="leave_'.$emp_ldata[LEAVE::ID].'" style="font-size: 12px; cursor: pointer;" onclick=\'viewLeaveDetails("employee",'. htmlspecialchars(json_encode($data), ENT_QUOTES, 'UTF-8') .');\'>
                    <td>'.$slno.$new.'</td>
                    <td>'.$subject.'</td>
                    <td>'.$leave_dates.'</td>
                    <td>'.$leave_apply_date.'</td>
                    <td>'.(($final_status != '') ? $final_status : $status).'</td>
                    <td>'.$action.'</td>
                    </tr>';
                    break;
            }
        }
    } else {
        $emp_leave_tr = '<tr class="animated fadeInDown">
        <td colspan="'.$colspan.'">
            <div class="alert alert-danger" role="alert">
                No Leave applied yet !
            </div>
        </td>
        </tr>';
    }
?>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 text-right">
        <h4 class="text-center font-weight-bold">
            <?php 
            $heading = "";
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                case SADMIN:
                    $heading = "Leave Management";
                    break;
                case EMPLOYEE:
                    $heading = "View Applied Leaves here";
                    break;
            }
            echo $heading;
            ?>
        </h4>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "leave_loader");?>
    <div class="card_body" style="padding: 15px;">
        <div id="view_leave">
        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover text-center" id="example" style="font-size:14px;">
                            <thead class="text-center table-warning">
                                <?php 
                                switch ($_SESSION[USER_TYPE]) {
                                    case ADMIN:
                                    case SADMIN:
                                    case MANAGER:
                                ?>
                                <tr style="text-transform: uppercase;">
                                    <th>SL.</th>
                                    <th>Subject</th>
                                    <th>Leave Dates</th>
                                    <th>Applied on (Date)</th>
                                    <th>Employee Details</th>
                                    <th>Status</th>
                                    <th>View Details</th>
                                </tr>
                                <?php
                                        break;
                                    case EMPLOYEE:
                                ?>
                                <tr style="text-transform: uppercase;">
                                    <th>SL.</th>
                                    <th>Subject</th>
                                    <th>Leave Dates</th>
                                    <th>Applied on (Date)</th>
                                    <th>Status</th>
                                    <th>View Details</th>
                                </tr>
                                <?php
                                        break;
                                }
                                ?>
                            </thead>
                            <tbody>
                                <?=$emp_leave_tr;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scrollable modal -->
<div class="modal fade" id="leave_details_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content" <?php if((!is_mobile()) && (!is_tablet())): ?>style="width: 150%;" <?php endif; ?>>
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Leave Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-primary">Understood</button> -->
            </div>
        </div>
    </div>
</div>

<?php } ?>