<?php 
function printContent () {
    $colspan = 7;
    $getEmployee = getData(Table::EMPLOYEE_DETAILS, [
        EMPLOYEE_DETAILS::ID,
        EMPLOYEE_DETAILS::EMPLOYEE_NAME,
        EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID
    ], [
        EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
        EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
        EMPLOYEE_DETAILS::ACTIVE => 1
    ]);
    $employees = $payslip_tr = '';
    if (count($getEmployee)>0) {
        $employees = '<option value="0" disabled selected>Select Employees</option>';
        foreach ($getEmployee as $key => $empl_val) {
            $getDesig = getData(Table::DESIGNATIONS,[
                DESIGNATIONS::DESIGNATION_TITLE
            ], [
                DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                DESIGNATIONS::STATUS => ACTIVE_STATUS,
                DESIGNATIONS::ID => $empl_val[EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID]
            ]);
            $emp_desig = ((count($getDesig)>0) && ($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE] != "")) ? "  ---  ".altRealEscape(ucwords($getDesig[0][DESIGNATIONS::DESIGNATION_TITLE])) : "";
            $employees .= '<option value="'.$empl_val[EMPLOYEE_DETAILS::ID].'" >'.altRealEscape($empl_val[EMPLOYEE_DETAILS::EMPLOYEE_NAME]).$emp_desig.'</option>';
        }
    } else {
        $employees = '<option value="0" disabled selected>No Employees Found</option>';
    }
    $payslipcols = [
        PAY_SLIP::ID,
        PAY_SLIP::EMPLOYEE_ID,
        PAY_SLIP::PAYSLIP_MONTH,
        PAY_SLIP::PAYSLIP_FILE,
        PAY_SLIP::CREATION_DATE,
        PAY_SLIP::ACTIVE,
        PAY_SLIP::ACCEPT_STATUS
    ];
    $payslipwh = [
        PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
        PAY_SLIP::STATUS => ACTIVE_STATUS
    ];
    switch ($_SESSION[USER_TYPE]) {
        case EMPLOYEE:
        case MANAGER:
            $colspan = 7;
            $payslipwh[PAY_SLIP::EMPLOYEE_ID] = $_SESSION[EMPLOYEE_ID];
            $payslipwh[PAY_SLIP::ACTIVE] = 1;
            $payslipcols[] = PAY_SLIP::LAST_ACTIVE_DATE;
            break;
    }

    $getPayslipData = getData(Table::PAY_SLIP, $payslipcols, $payslipwh);
    // rip($getPayslipData);
    // exit();
    
    if (count($getPayslipData)>0) {
        foreach ($getPayslipData as $key => $payslip_data) {
            $slno = ($key + 1);
            $getEmpName = getData(Table::EMPLOYEE_DETAILS, [
                EMPLOYEE_DETAILS::EMPLOYEE_NAME, 
                EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID
            ], [
                EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
                EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                EMPLOYEE_DETAILS::ID => $payslip_data[PAY_SLIP::EMPLOYEE_ID]
            ]);
            $employee_name = $employee_designation = EMPTY_VALUE;
            if (count($getEmpName)>0) {
                $employee_name = altRealEscape(ucwords($getEmpName[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME]));
                $getEmployeeDesignation = getData(Table::DESIGNATIONS,[
                    DESIGNATIONS::DESIGNATION_TITLE
                ], [
                    DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                    DESIGNATIONS::STATUS => ACTIVE_STATUS,
                    DESIGNATIONS::ID => $getEmpName[0][EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID]
                ]);
                $employee_designation = ((count($getEmployeeDesignation)>0) && ($getEmployeeDesignation[0][DESIGNATIONS::DESIGNATION_TITLE] != "")) ? altRealEscape(ucwords($getEmployeeDesignation[0][DESIGNATIONS::DESIGNATION_TITLE])) : EMPTY_VALUE;
            } 
            $payslip_month = (!empty($payslip_data[PAY_SLIP::PAYSLIP_MONTH])) ? ALL_MONTHS_NAME[altRealEscape($payslip_data[PAY_SLIP::PAYSLIP_MONTH])].' '.getToday(false, 'Y') : EMPTY_VALUE;
            $payslip_file = (!empty($payslip_data[PAY_SLIP::PAYSLIP_FILE])) ? '<a href="'. UPLOADED_PAYSLIPS_URL . $payslip_data[PAY_SLIP::PAYSLIP_FILE] .'" target="_blank" class="download">Click Here</a>' : EMPTY_VALUE;
            $dact = (($payslip_data[PAY_SLIP::ACTIVE]) == 1) ? "checked" : "";
            $action = '
            <div class="row" style="display:flex; justify-content: center;">
                <div class="col-6 text-danger cursor-pointer" onclick="initiateDelete('. $payslip_data[PAY_SLIP::ID] .', \'payslip\')"><i class="fas fa-trash-alt"></i></div>
            </div>
            ';
            $accept_status = '';
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                case SADMIN:
                    $color = $accept_status ='';
                    $ac_stts = $payslip_data[PAY_SLIP::ACCEPT_STATUS];
                    if ($ac_stts == PAYSLIP_PENDING) {
                        $color = 'warning';
                    }
                    if ($ac_stts == PAYSLIP_DISPUTE_RAISED) {
                        $color = 'danger';
                    }
                    if ($ac_stts == PAYSLIP_ACCEPTED) {
                        $color = 'success';
                    }
                    $accept_status = '<span class="text-'.$color.'">'. (($ac_stts == PAYSLIP_DISPUTE_RAISED) ? 'QUIRY RAISED' : PAYSLIP_ACCEPT_STATUSES[$ac_stts]) .'</span>';
                    break;
                case EMPLOYEE:
                case MANAGER:
                    $color = '';
                    if ($payslip_data[PAY_SLIP::ACCEPT_STATUS] == PAYSLIP_PENDING) {
                        $color = 'warning';
                    }
                    if ($payslip_data[PAY_SLIP::ACCEPT_STATUS] == PAYSLIP_DISPUTE_RAISED) {
                        $color = 'danger';
                    }
                    if ($payslip_data[PAY_SLIP::ACCEPT_STATUS] == PAYSLIP_ACCEPTED) {
                        $color = 'success';
                    }
                    $accept_status = '<select id="emp_payslip_accept_status_'.$payslip_data[PAY_SLIP::ID].'" class="form-control-sm text-'.$color.'" onchange="AcceptPayslip('.$payslip_data[PAY_SLIP::ID].')">';
                    if (count(PAYSLIP_ACCEPT_STATUSES)>0) {
                        foreach (PAYSLIP_ACCEPT_STATUSES as $k => $v) {
                            $selected = ($k == $payslip_data[PAY_SLIP::ACCEPT_STATUS]) ? 'selected' : '';
                            // $color = '';
                            if ($k == PAYSLIP_PENDING) {
                                $color = 'warning';
                            }
                            if ($k == PAYSLIP_DISPUTE_RAISED) {
                                $color = 'danger';
                            }
                            if ($k == PAYSLIP_ACCEPTED) {
                                $color = 'success';
                            }
                            $accept_status .= '<option class="text-'.$color.'" value="'.$k.'" '.$selected.'>'. $v .'</option>';
                        }
                    }
                    $accept_status .= '</select>';
                    break;
            }

            $active_text = (($payslip_data[PAY_SLIP::ACTIVE]) == 1) ? '<label class="custom-control-label text-success form_label" for="payslip_active_'.$payslip_data[PAY_SLIP::ID].'">A</label>' : '<label class="custom-control-label text-danger form_label" for="payslip_active_'.$payslip_data[PAY_SLIP::ID].'">D</label>';
            $active = '<div class="custom-control custom-switch noselect">
            <input type="checkbox" '.$dact.' class="custom-control-input" id="payslip_active_'.$payslip_data[PAY_SLIP::ID].'" onclick="changeActiveStatus(\'payslip\','.$payslip_data[PAY_SLIP::ID].', \'payroll_loader\');">
            '.$active_text.'
            </div>';
            switch ($_SESSION[USER_TYPE]) {
                case EMPLOYEE:
                case MANAGER:
                    $payslip_tr .= '<tr id="payslip_'.$payslip_data[PAY_SLIP::ID].'">
                        <td>'.$slno.'</td>
                        <td>'.$employee_name.'</td>
                        <td>'.$employee_designation.'</td>
                        <td>'.$payslip_month.'</td>
                        <td>'.$payslip_file.'</td>
                        <td>'.getFormattedDateTime($payslip_data[PAY_SLIP::LAST_ACTIVE_DATE], LONG_DATE_TIME_FORMAT).'</td>
                        <td>'.$accept_status.'</td>
                    </tr>';
                    break;
                case ADMIN:
                case SADMIN:
                    $payslip_tr .= '<tr id="payslip_'.$payslip_data[PAY_SLIP::ID].'">
                        <td>'.$slno.'</td>
                        <td>'.$employee_name.'</td>
                        <td>'.$employee_designation.'</td>
                        <td>'.$payslip_month.'</td>
                        <td>'.$payslip_file.'</td>
                        <td>'.$active.'</td>
                        <td>'.$accept_status.'</td>
                        <td>'.$action.'</td>
                    </tr>';
                    break;
            }
        }
    } else {
        $payslip_tr = '<tr class="animated fadeInDown">
        <td colspan="'.$colspan.'">
            <div class="alert alert-danger" role="alert">
                No Payslip found ! ';
        if ($_SESSION[USER_TYPE] == ADMIN) {
            $payslip_tr .= '<a style="text-decoration: underline; font-weight: bold;" href="'.HOST_URL.'payroll">Click Here</a> to Upload Payslips First.';
        }
        $payslip_tr .= '
        </div>
    </td>
    </tr>';
    }

switch ($_SESSION[USER_TYPE]) {
    case ADMIN:
?>
    <div class="row">
        <div class="col-sm-10 col-md-10 col-lg-10 text-right">
            <h4 class="text-center font-weight-bold">Payroll Management</h4>
        </div>
        <div class="col-sm-2 col-md-2 col-lg-2 text-right" id="list_nav_btn">
            <button class="btn btn-primary" name="list" onclick="changeNavigateBtn('payslip');">View List</button>
        </div>
    </div>
<?php
        break;
    case EMPLOYEE:
    case MANAGER:
    case SADMIN:
?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 text-right">
            <h4 class="text-center font-weight-bold">View Payslips</h4>
        </div>
    </div>
<?php
        break;
}
?>
<div class="card mt-5">
    <?=getSpinner(true, "payroll_loader")?>
    <div class="card_body" style="padding: 15px;">
        <?php if(($_SESSION[USER_TYPE] != EMPLOYEE) && ($_SESSION[USER_TYPE] != SADMIN) && ($_SESSION[USER_TYPE] != MANAGER)): ?>
        <div id="upload_payslip_row">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="form-outline">
                    <label class="form_label" for="payslip_month">Select Month</label>
                    <select id="payslip_month" class="form-control">
                        <option value='0' selected disabled>--Select Month--</option>
                        <?=ALL_MONTHS?>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label class="form_label" for="payslip_employee">Select Employee</label>
                <select id="payslip_employee" class="form-control"><?=$employees;?></select>
            </div>
        </div>
            <h6 class="text-left mt-4 font-weight-bold" style="padding: 15px;">Upload Payslip here</h6>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12 text-left">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="payslip_file" name="file_upload" onchange="fileNamePreview($(this));" />
                    <label class="custom-file-label" for="payslip_file">Choose file</label>
                </div>
                <div class="progress mt-4 media_progress" style="display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"> 75% </div>
                </div>
                <div class="pt-3">
                    <button class="btn btn-outline-secondary text-success" type="button" id="upload_payslip">Upload</button>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 text-center" style="padding: 10px;">
                <span style="font-weight: bold; display:none;" id="file_name_preview">Uploaded File : <span class="text-success"></span></span>
                <button type="button" class="close" aria-label="Close" id="file_clear_btn" style="display: none;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        </div>
        <?php endif; ?>
        <div id="view_payslip_row" <?php if(($_SESSION[USER_TYPE] != EMPLOYEE) && ($_SESSION[USER_TYPE] != SADMIN) && ($_SESSION[USER_TYPE] != MANAGER)): ?>style="display: none;"<?php endif; ?>>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover text-center payslip_table" id="example" style="font-size:14px;">
                            <thead class="text-center table-warning">
                                <?php 
                                switch ($_SESSION[USER_TYPE]) {
                                    case EMPLOYEE:
                                    case MANAGER:
                                    ?>
                                    <tr style="text-transform: uppercase;">
                                        <th>SL.</th>
                                        <th>Employee</th>
                                        <th>Designation</th>
                                        <th>Pay Month</th>
                                        <th>Payslip File</th>
                                        <th>Uploaded On</th>
                                        <th>Accepted</th>
                                    </tr>
                                    <?php
                                        break;
                                    case ADMIN:
                                    case SADMIN:
                                    ?>
                                    <tr style="text-transform: uppercase;">
                                        <th>SL.</th>
                                        <th>Employee</th>
                                        <th>Designation</th>
                                        <th>Pay Month</th>
                                        <th>Payslip File</th>
                                        <th>Active / Inactive</th>
                                        <th>Accept / Dispute</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php
                                        break;
                                }
                                ?>
                            </thead>
                            <tbody>
                                <?=$payslip_tr;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>