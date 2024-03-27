<?php 
function printContent() {
    $page_heading_text = $emp_leave_tr = $admin_leave_tr = $colspan = 6;
    $employee_name = $employee_department = $employee_designation = "";
    $reporting_manager_user_id = 0;
    $where = $employee_id_in['id'] = [0];
    $where = [
        LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
        LEAVE::STATUS => ACTIVE_STATUS,
        LEAVE::USER_ID => $_SESSION[RID],
        LEAVE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID]
    ];
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
    ], $where,[],[],[LEAVE::ID],"DESC");
    // rip($getLeaveData);
    if (count($getLeaveData)>0) {
        foreach ($getLeaveData as $k => $emp_ldata) {
            $slno = ($k + 1);
            $new = '';
            if ((getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') >= (getToday(false, 'd') - 3)) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'d') <= getToday(false, 'd')) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'m') == getToday(false, 'm')) && (getFormattedDateTime($emp_ldata[LEAVE::LEAVE_APPLY_DATE],'Y') == getToday(false, 'Y')) && ($emp_ldata[LEAVE::ACTION_TAKEN_STATUS] == APPLIED)) {
                $new = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">New</span>';
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
            // $text = str_replace(["\r\n\r\n", "\r\n", "\n"], "", html_entity_decode(htmlspecialchars_decode($emp_ldata[LEAVE::LEAVE_MATTER])));
            // echo $text;
            // $thedata = "Dear Sir / Madam,\r\n\r\nI\&amp;#039;m writing to test the matter.\r\n\r\nThank you.";
            // $t = htmlspecialchars_decode($emp_ldata[LEAVE::LEAVE_MATTER]);
            // $thedata = htmlentities(htmlspecialchars($t));
            // Decode HTML entities
            $decodedData = html_entity_decode($emp_ldata[LEAVE::LEAVE_MATTER]);

            // Remove line breaks and newline characters
            $cleanedData = str_replace(["\r\n\r\n", "\r\n", "\n"], "<br>", $decodedData);
            // $cleanedData = preg_replace("/\r\n\r\n/", "", $decodedData);
            // $cleanedData = altRealEscape($decodedData);

            // echo $decodedData;
            // exit;
            $data = [
                LEAVE::ID                   =>   $emp_ldata[LEAVE::ID],
                LEAVE::ACTION_TAKEN_STATUS  =>   $status,
                'action_taken_status_id'    =>   $emp_ldata[LEAVE::ACTION_TAKEN_STATUS],
                LEAVE::LEAVE_APPLY_DATE     =>   $leave_apply_date,
                LEAVE::LEAVE_DATES          =>   $leave_dates,
                // LEAVE::LEAVE_MATTER         =>   $emp_ldata[LEAVE::LEAVE_MATTER],
                LEAVE::LEAVE_MATTER         =>   $decodedData,
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
            $action = '
            <div class="row" style="display:flex; justify-content: center;">
                <div class="col-6 text-info view_leave_details" style="font-size:18px; cursor: pointer;" onclick=\'viewLeaveDetails("employee",'. json_encode($data) .');\'><i class="fas fa-info-circle"></i></div>
            </div>
            ';
            $colspan = 6;
            $emp_leave_tr .= '<tr id="leave_'.$emp_ldata[LEAVE::ID].'" style="font-size: 12px; cursor: pointer;" onclick=\'viewLeaveDetails("employee",'. json_encode($data) .');\'>
            <td>'.$slno.$new.'</td>
            <td>'.$subject.'</td>
            <td>'.$leave_dates.'</td>
            <td>'.$leave_apply_date.'</td>
            <td>'.(($final_status != '') ? $final_status : $status).'</td>
            <td>'.$action.'</td>
            </tr>';
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
            $heading = "View Applied Leaves here";
            echo $heading;
            ?>
        </h4>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "leave_loader");?>
    <div class="card-body" style="padding: 15px;">
        <div id="view_leave">
        <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover text-center" id="example" style="font-size:14px;">
                            <thead class="text-center table-warning">
                                <?php 
                                switch ($_SESSION[USER_TYPE]) {
                                    case ADMIN:
                                    case MANAGER:
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
                                <?=$emp_leave_tr?>
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
            </div>
        </div>
    </div>
</div>
<?php 
}
?>