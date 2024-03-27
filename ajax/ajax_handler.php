<?php 

header('Access-Control-Allow-Origin: *');
header('Content-Type: text/html; charset=utf-8');
// Constant defiend in config.php 
// If this one true then send the data as json format
// Or echo with json_encode if false [plain text format]
## IMPORTANT:- [For now do not make this true. Or the code will break. For testing purpose
## The rest of the response are sent as plain text format. The following condition 
## is useless right now. But can be activated in the future.]
## DO NOT ALTER THIS LINE
if (IS_CONTENT_TYPE_JSON) {
    header('Content-Type:application/json');
}
// The main array for sending the response
// Please use this variable to send the response
## By default denoting that error is true
## and the user requesting is logged in
$response = ['error' => true, 'empty' => false, 'tab' => false, 'message' => '', 'login' => 1];
$method = $_SERVER['REQUEST_METHOD'];
$ajax_form_data = $_REQUEST;
switch ($method) {
    case 'GET':
        $ajax_form_data = $_GET;
        break;
    case 'POST':
        $ajax_form_data = $_POST;
        break;
    default:
        $ajax_form_data = $_REQUEST;
        break;
}
if (!isset($ajax_form_data[AJAX_REQUEST])) {
    $response['message'] = 'Invalid Request Supplied';
    $response['login'] = isUserLoggedIn();
    sendRes();
    exit();
}
// $response['login'] = (isUserLoggedIn()) ? 1 : 0;
function sendRes() 
{
    global $response;
    echo json_encode($response);
    exit();
}
$ajax_action = $ajax_form_data[AJAX_REQUEST];
if ((!isset($_SESSION[CLIENT_ID])) && ($ajax_action != 'LOGIN')) {
    $response['login'] = 0;
    sendRes();
}
switch ($ajax_action) {
    case 'LOGIN':
        // rip($ajax_form_data);
        // exit();
        $email = (!empty($ajax_form_data['em'])) ? altRealEscape($ajax_form_data['em']) : "";
        $pass = (!empty($ajax_form_data['ep'])) ? altRealEscape($ajax_form_data['ep']) : "";
        $rtype = (!empty($ajax_form_data['rt'])) ? altRealEscape($ajax_form_data['rt']) : "";
        if (($email == "") || ($pass == "")) {
            $response["message"] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $pass_hash = hash('sha256', $pass);
        if (($pass_hash == "") || (empty($pass_hash))) {
            $response["message"] = ERROR_1;
            sendRes();
        }
        $wh = [];
        switch ($rtype) {
            case 'e':
                $wh = [
                    Users::EMAIL => $email
                ];
                break;
            case 'p':
                $wh = [
                    Users::MOBILE => $email
                ];
                break;
        }
        $wh[Users::ACTIVE] = 1;
        $wh[Users::STATUS] = (string)ACTIVE_STATUS;
        $db_data = getData(Table::USERS, [
            Users::CLIENT_ID,
            Users::EMAIL,
            Users::MOBILE,
            Users::NAME,
            Users::ID,
            Users::PASS_HASH,
            Users::PASSWORD,
            Users::USER_TYPE,
            Users::EMPLOYEE_ID
        ], $wh);
        // rip($db_data);
        // exit();
        if (!count($db_data)>0) {
            $response['message'] = "No Registered User Found";
            sendRes();
        }
        $db_pass_hash = $db_data[0][Users::PASS_HASH];
        $db_pass = $db_data[0][Users::PASSWORD];
        if ($db_pass === $db_pass) {
            if ($pass_hash === $db_pass_hash) {
                $db_cid = $db_data[0][Users::CLIENT_ID];
                $db_email = $db_data[0][Users::EMAIL];
                $db_mobile = $db_data[0][Users::MOBILE];
                $db_name = $db_data[0][Users::NAME];
                $db_id = $db_data[0][Users::ID];
                $db_user_type = $db_data[0][Users::USER_TYPE];
                $user_id = $email;
                $emp_id = $db_data[0][Users::EMPLOYEE_ID];

                if (($emp_id != 0) && ($db_user_type != SADMIN)) {
                    $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
                        EMPLOYEE_DETAILS::ACTIVE
                    ], [
                        EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
                        EMPLOYEE_DETAILS::ID => $emp_id,
                        EMPLOYEE_DETAILS::ACTIVE => 1
                    ]);
                    if (count($getEmpData) <= 0) {
                        $response['message'] = "Currently you are Inactive. Please contact your HR Department";
                        sendRes();
                    }
                }
                
                $_SESSION[LOGGEDIN] = true;
                $_SESSION[USERNAME] = $db_name;
                $_SESSION[USER_ID] = $user_id;
                $_SESSION[RID] = $db_id;
                $_SESSION[CLIENT_ID] = $db_cid;
                $_SESSION[USER_TYPE] = $db_user_type;
                $_SESSION[EMPLOYEE_ID] = $db_data[0][Users::EMPLOYEE_ID];
                $response['error'] = false;
                $response['message'] = 'Login Successful';
                // $_SESSION['success_msg'] = $response['message'];
                // setSessionMsg($response['message']);
            } else {
                $response['message'] = "Incorrect Password";
                sendRes();
            }
        } else {
            $response['message'] = "Incorrect Password";
            sendRes();
        }
        sendRes();
        exit();
        break;
    case 'ADD_DESIGNATION':
        $desig_name = (!empty($ajax_form_data['dname'])) ? altRealEscape($ajax_form_data['dname']) : "";
        $desig_resp = (!empty($ajax_form_data['dres'])) ? altRealEscape($ajax_form_data['dres']) : "";
        $desig_active = (!empty($ajax_form_data['dact'])) ? altRealEscape($ajax_form_data['dact']) : "";
        $desig_exp = (!empty($ajax_form_data['dexp'])) ? altRealEscape($ajax_form_data['dexp']) : "";
        if ($desig_name == "") {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $cols = [
            DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DESIGNATIONS::DESIGNATION_TITLE => $desig_name,
            DESIGNATIONS::ADDED_BY => $_SESSION[RID],
            DESIGNATIONS::CREATION_DATE => getToday(true),
            DESIGNATIONS::LAST_UPDATE_DATE => getToday(true)
        ];
        if (!empty($desig_resp)) {
            $cols[DESIGNATIONS::RESPONSIBILITIES] = $desig_resp;
        }
        if (!empty($desig_exp)) {
            $cols[DESIGNATIONS::EXPERIENCE_REQUIRED] = $desig_exp;
        }
        $getdname = getData(Table::DESIGNATIONS, [DESIGNATIONS::DESIGNATION_TITLE],[
            DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DESIGNATIONS::STATUS => ACTIVE_STATUS
        ]);
        if (count($getdname)>0) {
            foreach ($getdname as $key => $value) {
                if ($value[DESIGNATIONS::DESIGNATION_TITLE] == $desig_name) {
                    $response["message"] = "Designation already exists";
                    sendRes();
                }
            }
        }
        $add_desig = setData(Table::DESIGNATIONS, $cols);
        if (!$add_desig['res']) {
            $response['message'] = ERROR_3.' '.$add_desig['error'];
            logError("Unabled to Save Designation Data", $add_desig['error']);
        } else {
            $response['error'] = false;
            $response['message'] = $desig_name." Designation added successfully";
        }
        sendRes();
        break;
    case 'ADD_EMPLOYEE':
        $emp_name = (!empty($ajax_form_data['enm'])) ? altRealEscape($ajax_form_data['enm']) : '';
        $emp_date_of_birth = (!empty($ajax_form_data['edb'])) ? altRealEscape($ajax_form_data['edb']) : '';
        $emp_mother_name = (!empty($ajax_form_data['emn'])) ? altRealEscape($ajax_form_data['emn']) : '';
        $emp_father_name = (!empty($ajax_form_data['efn'])) ? altRealEscape($ajax_form_data['efn']) : '';
        $emp_mobile = (!empty($ajax_form_data['emb'])) ? altRealEscape($ajax_form_data['emb']) : '';
        $emp_email = (!empty($ajax_form_data['eeml'])) ? altRealEscape($ajax_form_data['eeml']) : '';
        $blood_group = (!empty($ajax_form_data['ebg'])) ? altRealEscape($ajax_form_data['ebg']) : '';
        $emp_payroll = (!empty($ajax_form_data['eprl'])) ? altRealEscape($ajax_form_data['eprl']) : '';
        $emp_date_of_join = (!empty($ajax_form_data['edtj'])) ? altRealEscape($ajax_form_data['edtj']) : '';
        $emp_remarks = (!empty($ajax_form_data['ermk'])) ? altRealEscape($ajax_form_data['ermk']) : '';
        $emp_exp = (!empty($ajax_form_data['exp'])) ? altRealEscape($ajax_form_data['exp']) : '';
        $emp_desig_id = (!empty($ajax_form_data['edgn'])) ? altRealEscape($ajax_form_data['edgn']) : '';

        $emp_id = (!empty($ajax_form_data['emp_id'])) ? altRealEscape($ajax_form_data['emp_id']) : '';
        $emp_department = (!empty($ajax_form_data['emp_department'])) ? $ajax_form_data['emp_department'] : '';
        $emp_salary = (!empty($ajax_form_data['emp_salary'])) ? altRealEscape($ajax_form_data['emp_salary']) : '';
        $emp_webmail = (!empty($ajax_form_data['emp_webmail'])) ? altRealEscape($ajax_form_data['emp_webmail']) : '';
        $emergeny_contact_name = (!empty($ajax_form_data['emergeny_contact_name'])) ? altRealEscape($ajax_form_data['emergeny_contact_name']) : '';
        $emergeny_contact_mobile = (!empty($ajax_form_data['emergeny_contact_mobile'])) ? altRealEscape($ajax_form_data['emergeny_contact_mobile']) : '';
        $current_address = (!empty($ajax_form_data['current_address'])) ? altRealEscape($ajax_form_data['current_address']) : '';
        $permanent_address = (!empty($ajax_form_data['permanent_address'])) ? altRealEscape($ajax_form_data['permanent_address']) : '';
        $emp_aadhaar = (!empty($ajax_form_data['emp_aadhaar'])) ? altRealEscape($ajax_form_data['emp_aadhaar']) : '';
        $emp_pan = (!empty($ajax_form_data['emp_pan'])) ? altRealEscape($ajax_form_data['emp_pan']) : '';
        $emp_salary_ac_number = (!empty($ajax_form_data['emp_salary_ac_number'])) ? altRealEscape($ajax_form_data['emp_salary_ac_number']) : '';
        $emp_salary_ac_ifsc = (!empty($ajax_form_data['emp_salary_ac_ifsc'])) ? altRealEscape($ajax_form_data['emp_salary_ac_ifsc']) : '';
        $emp_uan = (!empty($ajax_form_data['emp_uan'])) ? altRealEscape($ajax_form_data['emp_uan']) : '';
        $emp_esic_ip_number = (!empty($ajax_form_data['emp_esic_ip_number'])) ? altRealEscape($ajax_form_data['emp_esic_ip_number']) : '';

        $employee_user_type = ($ajax_form_data['utype'] != 0) ? altRealEscape($ajax_form_data['utype']) : 0;
        $user_password = (!empty($ajax_form_data['upass'])) ? altRealEscape($ajax_form_data['upass']) : '';

        $employee_report_manager = (($ajax_form_data['rpt_mngr'] != 0) && ($ajax_form_data['rpt_mngr'] != "")) ? $ajax_form_data['rpt_mngr'] : 0;
        $employee_report_time = (($ajax_form_data['employee_report_time'] != 0) && ($ajax_form_data['employee_report_time'] != "")) ? $ajax_form_data['employee_report_time'] : 0;
        

        if (($emp_name == "") || ($emp_desig_id == "") || ($emp_id == "") || ($emp_department == "") || ($user_password == "") || ($employee_user_type == 0) || ($employee_report_manager == 0) || ($employee_report_time == 0)) {
            $response["message"] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $employee_id = 0;
        $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
            EMPLOYEE_DETAILS::EMPLOYEE_NAME,
            EMPLOYEE_DETAILS::EMPLOYEE_ID
        ], [
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID]
        ]);
        if (count($getEmpData)>0) {
            foreach ($getEmpData as $key => $dbEmpData) {
                if ($emp_name == $dbEmpData[EMPLOYEE_DETAILS::EMPLOYEE_NAME]) {
                    $response["message"] = "Employee: ". $emp_name . " already exists";
                    sendRes();
                }
                if ($emp_id == $dbEmpData[EMPLOYEE_DETAILS::EMPLOYEE_ID]) {
                    $response["message"] = "Employee ID: ". EMPLOYEE_ID_PREFIX . $emp_id . " already exists";
                    sendRes();
                }
            }
        }
        $cols = [
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::EMPLOYEE_NAME => $emp_name,
            EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID => $emp_desig_id,
            EMPLOYEE_DETAILS::ACTIVE => 1,
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
            EMPLOYEE_DETAILS::CREATION_DATE => getToday(),
            EMPLOYEE_DETAILS::LAST_UPDATE_DATE => getToday(),
            EMPLOYEE_DETAILS::EMPLOYEE_ADDED_BY => $_SESSION[RID],
            EMPLOYEE_DETAILS::EMPLOYEE_ID => $emp_id,
            EMPLOYEE_DETAILS::REPORTING_TIME => $employee_report_time
        ];
        if (!empty($emp_date_of_birth) || ($emp_date_of_birth != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH] = $emp_date_of_birth;
        }
        if (!empty($emp_mother_name) || ($emp_mother_name != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_MOTHER_NAME] = $emp_mother_name;
        }
        if (!empty($emp_father_name) || ($emp_father_name != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_FATHER_NAME] = $emp_father_name;
        }
        if (!empty($emp_mobile) || ($emp_mobile != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_MOBILE] = $emp_mobile;
        }
        if (!empty($emp_email) || ($emp_email != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_EMAIL] = $emp_email;
        }
        if (!empty($blood_group) || ($blood_group != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_BLOOD_GROUP] = $blood_group;
        }
        if (!empty($emp_payroll) || ($emp_payroll != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_PAYROLL] = $emp_payroll;
        }
        if (!empty($emp_date_of_join) || ($emp_date_of_join != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_JOINNING] = $emp_date_of_join;
        }
        if (!empty($emp_remarks) || ($emp_remarks != "")) {
            $cols[EMPLOYEE_DETAILS::REMARKS] = $emp_remarks;
            $cols[EMPLOYEE_DETAILS::REMARK_BY] = $_SESSION[RID];
        }
        if (!empty($emp_exp) || ($emp_exp != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_EXPERIENCE_DURATION] = $emp_exp;
        }
        if (!empty($emp_department) || ($emp_department != "")) {
            $cols[EMPLOYEE_DETAILS::DEPARTMENT_ID] = $emp_department;
        }
        if (!empty($emp_salary) || ($emp_salary != "")) {
            $cols[EMPLOYEE_DETAILS::SALARY_AMOUNT] = $emp_salary;
        }
        if (!empty($emp_webmail) || ($emp_webmail != "")) {
            $cols[EMPLOYEE_DETAILS::WEBMAIL_ADDRESS] = $emp_webmail;
        }
        if (!empty($emergeny_contact_name) || ($emergeny_contact_name != "")) {
            $cols[EMPLOYEE_DETAILS::EMERGENCY_CONTACT_PERSON_NAME] = $emergeny_contact_name;
        }
        if (!empty($emergeny_contact_mobile) || ($emergeny_contact_mobile != "")) {
            $cols[EMPLOYEE_DETAILS::EMERGENCY_CONTACT_PERSON_MOBILE_NUMBER] = $emergeny_contact_mobile;
        }
        if (!empty($current_address) || ($current_address != "")) {
            $cols[EMPLOYEE_DETAILS::CURRENT_ADDRESS] = $current_address;
        }
        if (!empty($permanent_address) || ($permanent_address != "")) {
            $cols[EMPLOYEE_DETAILS::PERMANENT_ADDRESS] = $permanent_address;
        }
        if (!empty($emp_aadhaar) || ($emp_aadhaar != "")) {
            $cols[EMPLOYEE_DETAILS::AADHAAR_NUMBER] = $emp_aadhaar;
        }
        if (!empty($emp_pan) || ($emp_pan != "")) {
            $cols[EMPLOYEE_DETAILS::PAN_NUMBER] = $emp_pan;
        }
        if (!empty($emp_salary_ac_number) || ($emp_salary_ac_number != "")) {
            $cols[EMPLOYEE_DETAILS::SALARY_ACCOUNT_NUMBER] = $emp_salary_ac_number;
        }
        if (!empty($emp_salary_ac_ifsc) || ($emp_salary_ac_ifsc != "")) {
            $cols[EMPLOYEE_DETAILS::SALARY_ACCOUNT_IFSC_CODE] = $emp_salary_ac_ifsc;
        }
        if (!empty($emp_uan) || ($emp_uan != "")) {
            $cols[EMPLOYEE_DETAILS::UAN_NUMBER] = $emp_uan;
        }
        if (!empty($emp_esic_ip_number) || ($emp_esic_ip_number != "")) {
            $cols[EMPLOYEE_DETAILS::ESIC_IP_NUMBER] = $emp_esic_ip_number;
        }
        $save = setData(Table::EMPLOYEE_DETAILS, $cols);
        if (!$save['res']) {
            logError("Employee Details Save Error, employee name: ". $emp_name, $save['error']);
            $response['message'] = $save['error'];
            sendRes();
        }
        //saving as user
        $employee_id = $save['id'];
        $pass_hash = hash('sha256', $user_password);
        $saveUser = setData(Table::USERS, [
            Users::CLIENT_ID => $_SESSION[CLIENT_ID],
            Users::EMPLOYEE_ID => $employee_id,
            Users::USER_TYPE => $employee_user_type,
            Users::NAME => $emp_name,
            Users::EMAIL => $emp_email,
            Users::MOBILE => $emp_mobile,
            Users::PASSWORD => $user_password,
            Users::PASS_HASH => $pass_hash,
            Users::CREATION_DATE => getToday()
        ]);
        if (!$saveUser['res']) {
            logError("Failed to save User for the Employee: ".$employee_id.", Name: ".$emp_name, $saveUser['error']);
            $response['message'] = "Failed to Assign as User for the Employee: ".$emp_name;
            sendRes();
        }
        //saving reporting manager
        $saveReportManager = setData(Table::EMPLOYEE_REPORTING_MANAGER, [
            EMPLOYEE_REPORTING_MANAGER::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_REPORTING_MANAGER::EMPLOYEE_ID => $employee_id,
            EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID => $employee_report_manager,
            EMPLOYEE_REPORTING_MANAGER::ASSIGNED_BY_USER_ID => $_SESSION[RID],
            EMPLOYEE_REPORTING_MANAGER::ASSIGN_DATE => getToday(false),
            EMPLOYEE_REPORTING_MANAGER::STATUS => ACTIVE_STATUS,
            EMPLOYEE_REPORTING_MANAGER::CREATION_DATE => getToday()
        ]);
        if (!$saveReportManager['res']) {
            logError("Unabled to assign reporting manager for the employee ID: ". $employee_id.".", $saveReportManager['error']);
            $response['message'] = "Unabled to assign Reporting Manager";
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Saved Successfully";
        sendRes();
        break;
    case 'UPDTAE_EMPLOYEE':
        $emp_name = (!empty($ajax_form_data['enm'])) ? altRealEscape($ajax_form_data['enm']) : '';
        $emp_date_of_birth = (!empty($ajax_form_data['edb'])) ? altRealEscape($ajax_form_data['edb']) : '';
        $emp_mother_name = (!empty($ajax_form_data['emn'])) ? altRealEscape($ajax_form_data['emn']) : '';
        $emp_father_name = (!empty($ajax_form_data['efn'])) ? altRealEscape($ajax_form_data['efn']) : '';
        $emp_mobile = (!empty($ajax_form_data['emb'])) ? altRealEscape($ajax_form_data['emb']) : '';
        $emp_email = (!empty($ajax_form_data['eeml'])) ? altRealEscape($ajax_form_data['eeml']) : '';
        $blood_group = (!empty($ajax_form_data['ebg'])) ? altRealEscape($ajax_form_data['ebg']) : '';
        $emp_payroll = (!empty($ajax_form_data['eprl'])) ? altRealEscape($ajax_form_data['eprl']) : '';
        $emp_date_of_join = (!empty($ajax_form_data['edtj'])) ? altRealEscape($ajax_form_data['edtj']) : '';
        $emp_remarks = (!empty($ajax_form_data['ermk'])) ? altRealEscape($ajax_form_data['ermk']) : '';
        $emp_exp = (!empty($ajax_form_data['exp'])) ? altRealEscape($ajax_form_data['exp']) : '';
        $emp_desig_id = (!empty($ajax_form_data['edgn'])) ? altRealEscape($ajax_form_data['edgn']) : '';
        $emp_row_id = altRealEscape($ajax_form_data['emp_row_id']);

        $emp_id = (!empty($ajax_form_data['emp_id'])) ? altRealEscape($ajax_form_data['emp_id']) : '';
        $emp_department = (!empty($ajax_form_data['emp_department'])) ? $ajax_form_data['emp_department'] : '';
        $emp_salary = (!empty($ajax_form_data['emp_salary'])) ? altRealEscape($ajax_form_data['emp_salary']) : '';
        $emp_webmail = (!empty($ajax_form_data['emp_webmail'])) ? altRealEscape($ajax_form_data['emp_webmail']) : '';
        $emergeny_contact_name = (!empty($ajax_form_data['emergeny_contact_name'])) ? altRealEscape($ajax_form_data['emergeny_contact_name']) : '';
        $emergeny_contact_mobile = (!empty($ajax_form_data['emergeny_contact_mobile'])) ? altRealEscape($ajax_form_data['emergeny_contact_mobile']) : '';
        $current_address = (!empty($ajax_form_data['current_address'])) ? altRealEscape($ajax_form_data['current_address']) : '';
        $permanent_address = (!empty($ajax_form_data['permanent_address'])) ? altRealEscape($ajax_form_data['permanent_address']) : '';
        $emp_aadhaar = (!empty($ajax_form_data['emp_aadhaar'])) ? altRealEscape($ajax_form_data['emp_aadhaar']) : '';
        $emp_pan = (!empty($ajax_form_data['emp_pan'])) ? altRealEscape($ajax_form_data['emp_pan']) : '';
        $emp_salary_ac_number = (!empty($ajax_form_data['emp_salary_ac_number'])) ? altRealEscape($ajax_form_data['emp_salary_ac_number']) : '';
        $emp_salary_ac_ifsc = (!empty($ajax_form_data['emp_salary_ac_ifsc'])) ? altRealEscape($ajax_form_data['emp_salary_ac_ifsc']) : '';
        $emp_uan = (!empty($ajax_form_data['emp_uan'])) ? altRealEscape($ajax_form_data['emp_uan']) : '';
        $emp_esic_ip_number = (!empty($ajax_form_data['emp_esic_ip_number'])) ? altRealEscape($ajax_form_data['emp_esic_ip_number']) : '';
        $employee_report_time = (!empty($ajax_form_data['emp_rprt_tm'])) ? altRealEscape($ajax_form_data['emp_rprt_tm']) : '';
        

        if (($emp_name == "") || ($emp_desig_id == "") || ($emp_id == "") || ($emp_department == "") || ($employee_report_time == "")) {
            $response["message"] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $getEmpData = getData(Table::EMPLOYEE_DETAILS, [
            EMPLOYEE_DETAILS::EMPLOYEE_NAME,
            EMPLOYEE_DETAILS::EMPLOYEE_ID
        ], [
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::ID => $emp_row_id
        ]);
        if (count($getEmpData) <= 0) {
            $response["message"] = "No Employee was found against the Employee ID";
            sendRes();
        }
        $cols = [
            EMPLOYEE_DETAILS::EMPLOYEE_NAME => $emp_name,
            EMPLOYEE_DETAILS::EMPLOYEE_DESIGNATION_ID => $emp_desig_id,
            EMPLOYEE_DETAILS::LAST_UPDATE_DATE => getToday(),
            EMPLOYEE_DETAILS::EMPLOYEE_ID => $emp_id,
            EMPLOYEE_DETAILS::REPORTING_TIME => $employee_report_time
        ];
        if (!empty($emp_date_of_birth) || ($emp_date_of_birth != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_BIRTH] = $emp_date_of_birth;
        }
        
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_MOTHER_NAME] = $emp_mother_name;
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_FATHER_NAME] = $emp_father_name;
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_MOBILE] = $emp_mobile;
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_EMAIL] = $emp_email;
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_BLOOD_GROUP] = $blood_group;
        if (!empty($emp_payroll) || ($emp_payroll != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_PAYROLL] = $emp_payroll;
        }
        if (!empty($emp_date_of_join) || ($emp_date_of_join != "")) {
            $cols[EMPLOYEE_DETAILS::EMPLOYEE_DATE_OF_JOINNING] = $emp_date_of_join;
        }
        $cols[EMPLOYEE_DETAILS::REMARKS] = $emp_remarks;
        $cols[EMPLOYEE_DETAILS::REMARK_BY] = $_SESSION[RID];
        $cols[EMPLOYEE_DETAILS::EMPLOYEE_EXPERIENCE_DURATION] = $emp_exp;
        if (!empty($emp_department) || ($emp_department != "")) {
            $cols[EMPLOYEE_DETAILS::DEPARTMENT_ID] = $emp_department;
        }
        $cols[EMPLOYEE_DETAILS::SALARY_AMOUNT] = $emp_salary;
        $cols[EMPLOYEE_DETAILS::WEBMAIL_ADDRESS] = $emp_webmail;
        $cols[EMPLOYEE_DETAILS::EMERGENCY_CONTACT_PERSON_NAME] = $emergeny_contact_name;
        $cols[EMPLOYEE_DETAILS::EMERGENCY_CONTACT_PERSON_MOBILE_NUMBER] = $emergeny_contact_mobile;
        $cols[EMPLOYEE_DETAILS::CURRENT_ADDRESS] = $current_address;
        $cols[EMPLOYEE_DETAILS::PERMANENT_ADDRESS] = $permanent_address;
        $cols[EMPLOYEE_DETAILS::AADHAAR_NUMBER] = $emp_aadhaar;
        $cols[EMPLOYEE_DETAILS::PAN_NUMBER] = $emp_pan;
        $cols[EMPLOYEE_DETAILS::SALARY_ACCOUNT_NUMBER] = $emp_salary_ac_number;
        $cols[EMPLOYEE_DETAILS::SALARY_ACCOUNT_IFSC_CODE] = $emp_salary_ac_ifsc;
        $cols[EMPLOYEE_DETAILS::UAN_NUMBER] = $emp_uan;
        $cols[EMPLOYEE_DETAILS::ESIC_IP_NUMBER] = $emp_esic_ip_number;

        $update = updateData(Table::EMPLOYEE_DETAILS, $cols, [
            EMPLOYEE_DETAILS::ID => $emp_row_id,
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
        ]);
        if (!$update['res']) {
            logError("Failed to update employee details. employee row id: " + $emp_row_id, $update['error']);
            $response['message'] = ERROR_2;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Updated Successfully";
        sendRes();
        break;
    case 'UPLOAD_PAYSLIP':
        $file = $_FILES['media_file'];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $tmp_name = $file['tmp_name'];

        $payslip_employee       = (isset($ajax_form_data['emp_nm']))       ? altRealEscape(trim($ajax_form_data['emp_nm']))      : '';
        $payslip_month          = (isset($ajax_form_data['pay_mnth']))     ? altRealEscape(trim($ajax_form_data['pay_mnth']))    : '';

        if(!in_array(strtolower($file_extension), ALLOWED_PAYSLIP_FILE_TYPE)) {
            $response['message'] = 'File type not suported.';
            sendRes();
        } 
        ## Check size
        if($file_size > ALLOWED_MAX_FILESIZE) {
            $response['message'] = 'File size exceeded.';
            sendRes();
        }
        if (($payslip_employee == "") || ($payslip_employee == null)) {
            $response['message'] = "You Must have to select an employee";
            sendRes();
        }
        $getEmployeeName = getData(Table::EMPLOYEE_DETAILS, [
            EMPLOYEE_DETAILS::EMPLOYEE_NAME
        ], [
            EMPLOYEE_DETAILS::ID => $payslip_employee,
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
        ]);
        if (count($getEmployeeName) <= 0) {
            $response['message'] = "Looks like selected Employee is no more working for the company";
            sendRes();
        }
        $employee_name = $getEmployeeName[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME];
        //check for duplicate payslip entry start

        $getPayslipData = getData(Table::PAY_SLIP, [
            PAY_SLIP::ID,
            PAY_SLIP::PAYSLIP_FILE
        ], [
            PAY_SLIP::EMPLOYEE_ID => $payslip_employee,
            PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
            PAY_SLIP::PAYSLIP_MONTH => $payslip_month,
            PAY_SLIP::STATUS => ACTIVE_STATUS
        ]);
        if (count($getPayslipData)>0) {
            $response['message'] = "Payslip has already been uploaded for the selected month";
            sendRes();
        }
        // Create the target path
        $hasUploaded = false;
        $target_path = '';
        $monthNum  = $payslip_month;
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');
        $file_name = $employee_name . '_' . $monthName . '_' . getToday(false,'Y');
        $file_name = removeSpace($file_name) . '.' . $file_extension;
        ## Image
        $target_path = UPLOAD_PAYSLIP_PATH . $file_name;
        $hasUploaded = move_uploaded_file($tmp_name, $target_path);
        if(!$hasUploaded) {
            $response['message'] = 'File upload failed.';
            logError("Payslip file failed to upload for the employee: ". $employee_name . ', for the month ' . ALL_MONTHS_NAME[$payslip_month] . '_' . getToday(false,'Y'), $file['error']);
            sendRes();
        }
        ## Save / update the database
        $cols = [
            PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
            PAY_SLIP::EMPLOYEE_ID => $payslip_employee,
            PAY_SLIP::PAYSLIP_MONTH => $payslip_month,
            PAY_SLIP::PAYSLIP_FILE => $file_name,
            PAY_SLIP::UPLOADED_BY => $_SESSION[RID],
            PAY_SLIP::ACTIVE => 1,
            PAY_SLIP::STATUS => ACTIVE_STATUS,
            PAY_SLIP::CREATION_DATE => getToday()
        ];
        $res = setData(Table::PAY_SLIP, $cols);
        if(!$res['res']) {
            unlink($target_path);
            $response['message'] = ERROR_2;
            logError("Payslip data failed to save in the database for the employee: " . $employee_name . ', for the month ' . ALL_MONTHS_NAME[$payslip_month] . '_' . getToday(false,'Y'), $res['error']);
            sendRes();
        }
        $response['message'] = "Payslip Uploaded Successfully";
        $response['error'] = false;
        sendRes();
        //check for duplicate payslip entry end
        break;
    case 'ADD_DEPARTMENT':
        $department_name = (!empty($ajax_form_data['dnm'])) ? altRealEscape($ajax_form_data['dnm']) : "";

        if (($department_name == "") || ($department_name == null) || (empty($department_name))) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }

        $getDepartmentData = getData(Table::DEPARTMENTS, [
            DEPARTMENTS::DEPARTMENT_NAME
        ], [
            DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DEPARTMENTS::STATUS => ACTIVE_STATUS
        ]);
        if (count($getDepartmentData)>0) {
            foreach ($getDepartmentData as $key => $v) {
                if ($v[DEPARTMENTS::DEPARTMENT_NAME] == $department_name) {
                    $response['message'] = $department_name." Already Exists";
                    sendRes();
                }
            }
        }
        $save = setData(Table::DEPARTMENTS, [
            DEPARTMENTS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DEPARTMENTS::DEPARTMENT_NAME => $department_name,
            DEPARTMENTS::ADDED_BY => $_SESSION[RID],
            DEPARTMENTS::STATUS => ACTIVE_STATUS,
            DEPARTMENTS::CREATION_DATE => getToday(true)
        ]);
        if (!$save['res']) {
            logError("Department addition failed. Name: ". $department_name, $save['error']);
            $response['message'] = ERROR_2;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Saved Successfully";
        sendRes();
        break;
    case 'EMPLOYEE_ACTIVE':
        $row_id = $ajax_form_data['row_id'];
        $act_status = $ajax_form_data['act_status'];
        $emp_last_working_day = (isset($ajax_form_data['lwd'])) ? altRealEscape($ajax_form_data['lwd']) : "";

        $response['error'] = false;
        $getdata = getData(Table::EMPLOYEE_DETAILS, [EMPLOYEE_DETAILS::ACTIVE], [
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::ID => $row_id
        ]);

        if (count($getdata)>0) {
            if ($act_status == $getdata[0][EMPLOYEE_DETAILS::ACTIVE]) {
                $response['message'] = "Requested status is already matching with the database";
                sendRes();
            }
            $change = updateData(Table::EMPLOYEE_DETAILS, [
                EMPLOYEE_DETAILS::ACTIVE => $act_status,
                EMPLOYEE_DETAILS::LAST_WORKING_DAY => $emp_last_working_day
            ], [
                EMPLOYEE_DETAILS::ID => $row_id,
                EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS,
                EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID]
            ]);
            if (!$change['res']) {
                logError("Failed to change Employee active status for employee id: " + $row_id + ", Active Status: " + $act_status, $change['error']);
                $response['error'] = true;
                $response['message'] = ERROR_2;
                sendRes();
            }
            $response['error'] = false;
            $response['message'] = "Status Updated";
            sendRes();
        } else {
            $response['error'] = true;
            $response['message'] = "No Employee Found for the selected row";
            sendRes();
        }
        break;
    case 'NOTICE_ACTIVE':
        $row_id = $ajax_form_data['row_id'];
        $act_status = $ajax_form_data['act_status'];

        $response['error'] = false;
        $getdata = getData(Table::NOTICES, [NOTICES::ACTIVE], [
            NOTICES::STATUS => ACTIVE_STATUS,
            NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID],
            NOTICES::ID => $row_id
        ]);

        if (count($getdata)>0) {
            if ($act_status == $getdata[0][NOTICES::ACTIVE]) {
                $response['message'] = "Requested status is already matching with the database";
                sendRes();
            }
            $cols = [
                NOTICES::ACTIVE => $act_status
            ];
            if ($act_status == 1) {
                $cols[NOTICES::LAST_ACTIVE_DATE] = getToday();
            }
            $change = updateData(Table::NOTICES, $cols, [
                NOTICES::ID => $row_id,
                NOTICES::STATUS => ACTIVE_STATUS,
                NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID]
            ]);
            if (!$change['res']) {
                logError("Failed to change Notice active status for Notice id: " + $row_id + ", Active Status: " + $act_status, $change['error']);
                $response['error'] = true;
                $response['message'] = ERROR_2;
                sendRes();
            }
            $response['error'] = false;
            $response['message'] = "Status Updated";
            sendRes();
        } else {
            $response['error'] = true;
            $response['message'] = "No Notice Found for the selected row.";
            sendRes();
        }
        break;
    case 'PAYSLIP_ACTIVE':
        $row_id = $ajax_form_data['row_id'];
        $act_status = $ajax_form_data['act_status'];

        $response['error'] = false;
        $getdata = getData(Table::PAY_SLIP, [PAY_SLIP::ACTIVE], [
            PAY_SLIP::STATUS => ACTIVE_STATUS,
            PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
            PAY_SLIP::ID => $row_id
        ]);

        if (count($getdata)>0) {
            if ($act_status == $getdata[0][PAY_SLIP::ACTIVE]) {
                $response['message'] = "Requested status is already matching with the database";
                sendRes();
            }
            $cols = [
                PAY_SLIP::ACTIVE => $act_status
            ];
            if ($act_status == 1) {
                $cols[PAY_SLIP::LAST_ACTIVE_DATE] = getToday();
            }
            $change = updateData(Table::PAY_SLIP, $cols, [
                PAY_SLIP::ID => $row_id,
                PAY_SLIP::STATUS => ACTIVE_STATUS,
                PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID]
            ]);
            if (!$change['res']) {
                logError("Failed to change Payslip active status for Payslip id: " + $row_id + ", Active Status: " + $act_status, $change['error']);
                $response['error'] = true;
                $response['message'] = ERROR_2;
                sendRes();
            }
            $response['error'] = false;
            $response['message'] = "Status Updated";
            sendRes();
        } else {
            $response['error'] = true;
            $response['message'] = "No Payslip Found for the selected row.";
            sendRes();
        }
        break;
    case 'UPLOAD_NOTICE':
        $file = $_FILES['media_file'];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $tmp_name = $file['tmp_name'];

        $notice_subject       = (isset($ajax_form_data['notice_subject']))    ? altRealEscape(trim($ajax_form_data['notice_subject']))   : '';
        $notice_status        = (isset($ajax_form_data['notice_status']))     ? altRealEscape(trim($ajax_form_data['notice_status']))    : '';

        if(!in_array(strtolower($file_extension), ALLOWED_PAYSLIP_FILE_TYPE)) {
            $response['message'] = 'File type not suported.';
            sendRes();
        } 
        ## Check size
        if($file_size > ALLOWED_MAX_FILESIZE) {
            $response['message'] = 'File size exceeded.';
            sendRes();
        }
        if (($notice_status == "") || ($notice_status == 0)) {
            $response['message'] = "You Must have to select Publish Status";
            sendRes();
        }
        $getNoticeData = getData(Table::NOTICES, [
            NOTICES::ID
        ], [
            NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID]
        ], [],[],[NOTICES::ID],"DESC");
        $notice_index = 1;
        if (count($getNoticeData)>0) {
            $notice_index = ((count($getNoticeData)) + 1);
        }
        // Create the target path
        $hasUploaded = false;
        $monthNum  = date('m');
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F');
        $file_name = $notice_index . '_' . COMPANY_NAME . '_NOTICE_' . $monthName . '_' . getToday(false,'Y');
        $file_name = removeSpace($file_name) . '.' . $file_extension;
        ## Image
        $target_path = UPLOAD_NOTICE_PATH . $file_name;
        $hasUploaded = move_uploaded_file($tmp_name, $target_path);
        if(!$hasUploaded) {
            logError("Notice File Upload Failed.", $file["error"]);
            $response['message'] = 'File upload failed.';
            sendRes();
        }
        ## Save / update the database
        $cols = [
            NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID],
            NOTICES::ACTIVE => $notice_status,
            NOTICES::NOTICE_FILE => $file_name,
            NOTICES::NOTICE_ADDED_BY => $_SESSION[RID],
            NOTICES::CREATION_DATE => getToday()
        ];
        if (!empty($notice_subject) || ($notice_subject != "")) {
            $cols[NOTICES::NOTICE_SUBJECT] = $notice_subject;
        }
        $res = setData(Table::NOTICES, $cols);
        if(!$res['res']) {
            unlink($target_path);
            $response['message'] = ERROR_2;
            logError("Notice Data failed to save in the database.", $res['error']);
            sendRes();
        }
        $response['message'] = "Notice Uploaded Successfully";
        $response['error'] = false;
        sendRes();
        break;
    case 'APPLY_LEAVE':
        $leave_month = (!empty($ajax_form_data['lmonth'])) ? altRealEscape($ajax_form_data['lmonth']) : 0;
        $leave_year = (!empty($ajax_form_data['lyear'])) ? altRealEscape($ajax_form_data['lyear']) : 0;
        $leave_dates = (!empty($ajax_form_data['ldates'])) ? altRealEscape($ajax_form_data['ldates']) : '';
        $leave_subject = (!empty($ajax_form_data['lsubject'])) ? altRealEscape($ajax_form_data['lsubject']) : '';
        $leave_matter = (!empty($ajax_form_data['lmatter'])) ? altRealEscape($ajax_form_data['lmatter']) : '';
        $hasFile = $hasUploaded = false;

        if (($leave_month == 0) || ($leave_year == 0) || ($leave_dates == '') || ($leave_subject == '') || ($leave_matter == '')) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }

        // $htmlcode = htmlentities(htmlspecialchars($leave_matter));
        // echo $htmlcode,'<br>';

        // echo html_entity_decode(htmlspecialchars_decode($htmlcode));
        // exit;
        // echo gettype((bool)$ajax_form_data['hasFile']).'<br>';
        // echo $ajax_form_data['hasFile'];
        // exit;

        if (isset($ajax_form_data['hasFile']) && ($ajax_form_data['hasFile']) == 1) {
            $hasFile = $ajax_form_data['hasFile'];
            $file = $_FILES['media_file'];
            $file_type = $file['type'];
            $file_size = $file['size'];
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $tmp_name = $file['tmp_name'];

            if(!in_array(strtolower($file_extension), ALLOWED_LEAVE_DOCS_FILE_TYPE)) {
                $response['message'] = 'File type not suported.';
                sendRes();
            } 
            ## Check size
            if($file_size > ALLOWED_MAX_FILESIZE) {
                $response['message'] = 'File size exceeded.';
                sendRes();
            }
            $getEmployeeName = getData(Table::EMPLOYEE_DETAILS, [
                EMPLOYEE_DETAILS::EMPLOYEE_NAME
            ], [
                EMPLOYEE_DETAILS::ID => $_SESSION[EMPLOYEE_ID],
                EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
            ]);
            $employee_name = "new_employee";
            if (count($getEmployeeName)>0) {
                $employee_name = altRealEscape($getEmployeeName[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME]);
            }
            // Create the target path
            $monthNum  = date('m');
            $dateObj   = DateTime::createFromFormat('!m', $monthNum);
            $monthName = $dateObj->format('F');
            $file_name = $employee_name . '_LEAVE_REF_DOC_' . getToday(false,'d') . $monthName . '_' . getToday(false,'Y');
            $file_name = removeSpace($file_name) . '.' . $file_extension;
            ## Image
            $target_path = UPLOAD_LEAVE_DOC_PATH . $file_name;
            $hasUploaded = move_uploaded_file($tmp_name, $target_path);
            if(!$hasUploaded) {
                logError("Leave Reference File Upload Failed.", $file["error"]);
                $response['message'] = 'File upload failed.';
                sendRes();
            }
        }

        $cols = [
            LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
            LEAVE::LEAVE_DATES => $leave_dates,
            // LEAVE::LEAVE_MATTER => $leave_matter,
            LEAVE::LEAVE_MATTER => htmlentities($leave_matter),
            LEAVE::LEAVE_MONTH => $leave_month,
            LEAVE::LEAVE_SUBJECT => $leave_subject,
            LEAVE::LEAVE_YEAR => $leave_year,
            LEAVE::USER_ID => $_SESSION[RID],
            LEAVE::LEAVE_APPLY_DATE => getToday(),
            LEAVE::STATUS => ACTIVE_STATUS,
            LEAVE::CREATION_DATE => getToday(),
            LEAVE::ACTION_TAKEN_STATUS => APPLIED
        ];
        if (($hasFile) && ($hasUploaded)) {
            $cols[LEAVE::REFERENCE_DOC] = $file_name;
        }
        $getEmpData = getData(Table::USERS, [
            Users::EMPLOYEE_ID
        ], [
            Users::ID => $_SESSION[RID],
            Users::CLIENT_ID => $_SESSION[CLIENT_ID],
            Users::ACTIVE => 1,
            Users::STATUS => ACTIVE_STATUS
        ]);
        if (count($getEmpData)>0) {
            $cols[LEAVE::EMPLOYEE_ID] = $getEmpData[0][Users::EMPLOYEE_ID];
        } else {
            logError("No Employee ID found for the Current User: ". $_SESSION[USERNAME].", ID: ". $_SESSION[RID], "");
            $response["message"] = ERROR_1;
            sendRes();
        }
        $save = setData(Table::LEAVE, $cols);
        if (!$save['res']) {
            if (($hasFile) && ($hasUploaded)) {
                unlink($target_path);
            }
            logError('Failed to save leave application data for the user: '. $_SESSION[RID], $save['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Applied Successfully. Please wait for the approval";
        sendRes();
        break;
    case 'UPDATE_LEAVE_RESPONSE_BY_MANAGER':
        $leave_id = $ajax_form_data['lid'];
        $admin_leave_response = (!empty($ajax_form_data['resp'])) ? altRealEscape($ajax_form_data['resp']) : "";
        $admin_leave_status = ((empty($ajax_form_data['resp_status'])) || (($ajax_form_data['resp_status']) == 0) || (($ajax_form_data['resp_status']) == null)) ? 0 : $ajax_form_data['resp_status'];
        if ((($admin_leave_response) == "") || (($admin_leave_status) == 0)) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $cols = [
            LEAVE::RESPONSE => $admin_leave_response,
            LEAVE::ACTION_TAKEN_STATUS => $admin_leave_status,
            LEAVE::RESPONSE_BY_USER_ID => $_SESSION[RID],
            LEAVE::RESPONSE_DATE => getToday()
        ];
        $update = updateData(Table::LEAVE, $cols, [
            LEAVE::ID => $leave_id,
            LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
            LEAVE::STATUS => ACTIVE_STATUS
        ]);
        if (!$update['res']) {
            logError("Failed to update leave response by admin, for leave id: ".$leave_id, $update['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Response Recorded Successfully";
        sendRes();
        break;
    case 'UPDATE_LEAVE_RESPONSE_BY_ADMIN':
        $leave_id = $ajax_form_data['lid'];
        $admin_leave_response = (!empty($ajax_form_data['resp'])) ? altRealEscape($ajax_form_data['resp']) : "";
        $admin_leave_status = ((empty($ajax_form_data['resp_status'])) || (($ajax_form_data['resp_status']) == 0) || (($ajax_form_data['resp_status']) == null)) ? 0 : $ajax_form_data['resp_status'];
        if ((($admin_leave_response) == "") || (($admin_leave_status) == 0)) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $cols = [
            LEAVE::ADMIN_RESPONSE => $admin_leave_response,
            LEAVE::ADMIN_ACTION_TAKEN_STATUS => $admin_leave_status,
            LEAVE::ADMIN_USER_ID => $_SESSION[RID],
            LEAVE::ADMIN_RESPONSE_DATE => getToday()
        ];
        $update = updateData(Table::LEAVE, $cols, [
            LEAVE::ID => $leave_id,
            LEAVE::CLIENT_ID => $_SESSION[CLIENT_ID],
            LEAVE::STATUS => ACTIVE_STATUS
        ]);
        if (!$update['res']) {
            logError("Failed to update leave response by admin, for leave id: ".$leave_id, $update['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Response Recorded Successfully";
        sendRes();
        break;
    case 'UPDATE_PROFILE':
        $name = (!empty($ajax_form_data['nm'])) ? altRealEscape($ajax_form_data['nm']) : "";
        $email = (!empty($ajax_form_data['em'])) ? altRealEscape($ajax_form_data['em']) : "";
        $mobile = (!empty($ajax_form_data['mb'])) ? altRealEscape($ajax_form_data['mb']) : "";
        if (($name == "") || ($email == "") || ($mobile == "")) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        // $getdata = getData(Table::USERS, [
        //     Users::NAME,
        //     Users::EMAIL,
        //     Users::MOBILE
        // ], [
        //     Users::STATUS => ACTIVE_STATUS,
        //     Users::CLIENT_ID => $_SESSION[CLIENT_ID]
        // ]);
        $sql = 'SELECT '. Users::NAME.','.
                Users::EMAIL.','.
                Users::MOBILE.' FROM '. 
                Table::USERS.' WHERE '. 
                Users::STATUS.' = "'.
                ACTIVE_STATUS.'" AND '. 
                Users::CLIENT_ID.' = '.
                $_SESSION[CLIENT_ID].' AND '.
                Users::ID.' NOT IN ("'. 
                $_SESSION[RID].'")';
        $getdata = getCustomData($sql);
        // echo $sql.'<br>';
        // rip($getdata);
        // exit();
        if (count($getdata)>0) {
            foreach ($getdata as $k => $v) {
                if ($v[Users::NAME] == $name) {
                    $response['message'] = "Please choose a different Name";
                    sendRes();
                }
                if ($v[Users::EMAIL] == $email) {
                    $response['message'] = "Please choose a different Email address";
                    sendRes();
                }
                if ($v[Users::MOBILE] == $mobile) {
                    $response['message'] = "Please insert a different Mobile Number";
                    sendRes();
                }
            }
        } else {
            logError("Failed to find any user on the database to update profile.", "");
            $response['message'] = ERROR_1;
            sendRes();
        }
        $update = updateData(Table::USERS, [
            Users::NAME => $name,
            Users::EMAIL => $email,
            Users::MOBILE => $mobile
        ], [
            Users::ID => $_SESSION[RID],
            Users::CLIENT_ID => $_SESSION[CLIENT_ID],
            Users::STATUS => ACTIVE_STATUS
        ]);
        if (!$update['res']) {
            logError("Failed to update user profile for the user ID: ". $_SESSION[RID], $update['error']);
            $response['message'] = ERROR_2;
            sendRes();
        }
        $_SESSION[USERNAME] = $name;
        $_SESSION[USER_ID] = $email;
        $response['error'] = false;
        $response['message'] = "Updated Successfully";
        sendRes();
        break;
    case 'DELETE_ITEM':
        $action = altRealEscape($ajax_form_data['action']);
        $row_id = $ajax_form_data['id'];
        if ((empty($action)) || ($row_id == 0) || (empty($row_id))) {
            $response['message'] = ERROR_1;
            sendRes();
        }
        $table = $tb = "";
        switch ($action) {
            case 'notice':
                $table = Table::NOTICES;
                $tb = 'NOTICES';
                break;
            case 'payslip':
                $table = Table::PAY_SLIP;
                $tb = 'PAY_SLIP';
                break;
            case 'employee_list':
                $table = Table::EMPLOYEE_DETAILS;
                $tb = 'EMPLOYEE_DETAILS';
                break;
            case 'designation':
                $table = Table::DESIGNATIONS;
                $tb = 'DESIGNATIONS';
                break;
            case 'department':
                $table = Table::DEPARTMENTS;
                $tb = 'DEPARTMENTS';
                break;
        }

        $delete = updateData($table, [
            $tb::STATUS => 'D'
        ], [
            $tb::ID => $row_id,
            $tb::CLIENT_ID => $_SESSION[CLIENT_ID]
        ]);

        if (!$delete['res']) {
            logError("Unable to delete item from ".$action.", for row id: ".$row_id.".", $delete['error']);
            $response['message'] = ERROR_3;
            sendRes();
        }

        $response['error'] = false;
        $response['message'] = "Deleted Successfully";
        sendRes();
        break;
    case 'ACCEPT_PAYSLIP':
        $pid = (isset($ajax_form_data['pid'])) ? altRealEscape($ajax_form_data['pid']) : 0;
        $status = (isset($ajax_form_data['st'])) ? altRealEscape($ajax_form_data['st']) : 0;
        if (($pid == 0) || ($status == 0)) {
            $response["message"] = ERROR_1;
            sendRes();
        }
        $getDBdata = getData(Table::PAY_SLIP,[
            PAY_SLIP::ACCEPT_STATUS
        ],[
            PAY_SLIP::ID => $pid,
            PAY_SLIP::STATUS => ACTIVE_STATUS,
            PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID]
        ]);
        if (count($getDBdata)<=0) {
            $response['message'] = ERROR_2;
            sendRes();
        }
        if ($status == $getDBdata[0][PAY_SLIP::ACCEPT_STATUS]) {
            $response["message"] = "Requested Status is already matching with the database.";
            sendRes();
        }
        $update = updateData(Table::PAY_SLIP,[
            PAY_SLIP::ACCEPT_STATUS => $status
        ], [
            PAY_SLIP::ID => $pid,
            PAY_SLIP::CLIENT_ID => $_SESSION[CLIENT_ID],
            PAY_SLIP::STATUS => ACTIVE_STATUS
        ]);
        if (!$update['res']) {
            logError("Failed to Update Payslip Accept Status. Payslip ID: ".$pid.", Employee ID: ".$_SESSION[EMPLOYEE_ID], $update['error']);
            $response['message'] = ERROR_3;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = "Status Updated Successfully";
        sendRes();
        break;
    case 'SAVE_INVOICE':
        // checkSession();
        $bill_info_data = (isset($ajax_form_data['info_dt'])) ? $ajax_form_data['info_dt'] : [];
        $bill_item_data = (isset($ajax_form_data['item_dt'])) ? $ajax_form_data['item_dt'] : [];
        $invoice_id = 0;
        // echo "info data: <br>";
        // rip($bill_info_data);
        // echo "<br>item data: <br>";
        // rip($bill_item_data);
        // exit;
        if ((count($bill_info_data) > 0) && (count($bill_item_data) > 0)) {
            //check invoice no for duplicate entry
            $getDBinvoiceData = getData(Table::INVOICE, [
                INVOICE::ID,
                INVOICE::INVOICE_COUNT_NUMBER,
                INVOICE::INVOICE_NUMBER
            ], [
                INVOICE::CLIENT_ID => $_SESSION[CLIENT_ID],
                INVOICE::STATUS => ACTIVE_STATUS,
                INVOICE::INVOICE_COUNT_NUMBER => $bill_info_data[INVOICE::INVOICE_COUNT_NUMBER],
                INVOICE::INVOICE_MONTH => $bill_info_data[INVOICE::INVOICE_MONTH],
                INVOICE::INVOICE_YEAR => $bill_info_data[INVOICE::INVOICE_YEAR]
            ]);
            if (count($getDBinvoiceData)>0) {
                $response['error'] = true;
                $response['message'] = "INVOICE No already exists";
                sendRes();
            } else {
                $save_invoice = setData(Table::INVOICE, $bill_info_data);
                if (!$save_invoice['res']) {
                    logError("Unabled to save invoice data, Invoice Number: ".$bill_info_data[INVOICE::INVOICE_NUMBER].", Billing Name: ".$bill_info_data[INVOICE::BILLING_NAME].".", $save_invoice['error']);
                    $response['error'] = true;
                    $response['message'] = "Failed to save Invoice";
                    sendRes();
                } else {
                    $invoice_id = $save_invoice['id'];
                    $response['error'] = false;
                    $response['message'] = "Invoice Saved Successfully";
                }
            }
            if ((!$response['error']) && ($invoice_id != 0) && (count($bill_item_data)>0)) {
                for ($i=0; $i < count($bill_item_data); $i++) {
                    $bill_item_data[$i][INVOICE_DETAILS::INVOICE_ID] = $invoice_id;
                }
                // echo "item data: <br>";
                // echo "item count: ". count($bill_item_data) ."<br>";
                // rip($bill_item_data);
                // exit();
                $save_bill_items = setMultipleData(Table::INVOICE_DETAILS, $bill_item_data);
                if (!$save_bill_items['res']) {
                    logError("Unabled to save billing items data. Invoice ID: ".$invoice_id.", Invoice No: ".$bill_info_data[INVOICE::INVOICE_NUMBER].".", $save_bill_items['error']);
                    $response['error'] = true;
                    $response['message'] = "Failed to save Billing Items data";
                    sendRes();
                    //Now delete the main invoice table row containing the current invoice id
                } else {
                    $response['error'] = false;
                    $response['message'] .= " With all Items";
                    sendRes();
                }
            }
        } else {
            $response["message"] = ERROR_4;
            sendRes();
        }
        break;
    case 'ATTENDANCE_ACTION':
        $emp_id = $_SESSION[EMPLOYEE_ID];
        $report_time = $working_hours = 0;
        $attCycle = $ajax_form_data['attCycle'];
        $att_id = (isset($ajax_form_data['att_id'])) ? $ajax_form_data['att_id'] : 0;
        $reporting_time = $logOffTime = getToday(true, '', 'H:i');
        $late_mints = '';
        $row = '';

        $getReportTime = getData(Table::EMPLOYEE_DETAILS,[
            EMPLOYEE_DETAILS::REPORTING_TIME
        ], [
            EMPLOYEE_DETAILS::ID => $_SESSION[EMPLOYEE_ID],
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
        ]);
        if (count($getReportTime)>0) {
            $report_time = (int)$getReportTime[0][EMPLOYEE_DETAILS::REPORTING_TIME];
        } else {
            $report_time = (int)DEFAULT_REPORT_TIME;
        }
        switch ($attCycle) {
            case CYCLE_NOT_STARTED:
                if ($report_time != 0) {
                    $hour = $report_time;
                    $mint = 00;
                    $reporting_hour = (explode(":", $reporting_time))[0];
                    $reporting_mint = (explode(":", $reporting_time))[1];
                    if ((int)$reporting_hour > (int)$hour) {
                        $late_mints = ((int)$reporting_hour - (int)$hour).' Hrs.';
                        // $late_mints .= ((int)$reporting_mint > GRACE_TIME) ? ' And '.$reporting_mint.' Mints': '';
                        $late_mints .= ' And '.$reporting_mint.' Mints';
                    } else {
                        if ((int)$reporting_hour < (int)$hour) {
                            $late_mints = '';
                        } else {
                            $late_mints = ((int)$reporting_mint > GRACE_TIME) ? $reporting_mint.' Mints': '';
                        }
                    }
                }
                // echo "late_mints: ". $late_mints;
                // exit;
                $cols = [
                    ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
                    ATTENDANCE::EMPLOYEE_ID => $emp_id,
                    ATTENDANCE::ATTENDANCE_DATE => getToday(false),
                    ATTENDANCE::ATTENDANCE_MONTH => getToday(false, 'm'),
                    ATTENDANCE::ATTENDANCE_YEAR => getToday(false, 'Y'),
                    ATTENDANCE::REPORTING_TIME => $reporting_time,
                    ATTENDANCE::ACTIVE => CYCLE_ACTIVE,
                    ATTENDANCE::CREATION_DATE => getToday()
                ];
                if ($late_mints != '') {
                    $cols[ATTENDANCE::LATE_MINTS] = $late_mints;
                    $cols[ATTENDANCE::IS_LATE_ENTRY] = 1;
                }
                $save = setData(Table::ATTENDANCE, $cols);
                if (!$save['res']) {
                    logError("Unabled to save Attendance details for employee ID: ". $emp_id, $save['error']);
                    $response['message'] = ERROR_1;
                    sendRes();
                }
                $current_cycle = CYCLE_ACTIVE;
                $live_time = getFormattedDateTime(getToday(), LONG_DATE_TIME_FORMAT);
                $reportingTime = getToday(true, '', 'H:i');
                $workingHours = getWorkingHrs($reportingTime, getToday(true, '', 'H:i'));
                $reportTime = getFormattedDateTime(getToday(true, '', 'H:i'), LONG_TIME_FORMAT);
                $late_row = '';
                if ($late_mints != '') {
                    $late_row = '
                    <small class="text-muted" id="late_mints_row">
                        <b class="text-danger">You are Late of:</b>&nbsp;<i class="text-danger">'.$late_mints.'</i>
                    </small><br>
                    ';
                }
$row =<<<HTML
<div class="col-md-3 col-lg-3 col-sm-12">
    <button class="btn btn-danger" type="button" id="attendance_btn" onclick="recordAttendance();" data-att="$current_cycle" style="
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
    <span id="live_date_time">$live_time</span><br>
    <small class="text-muted" id="attendance_msg">
        <b>*</b>&nbsp;<i class="text-danger">Click on the button to Log Off.</i>
    </small><br>
    <small class="text-muted" id="reporting_time">
        <b>Reporting Time:</b>&nbsp;<i class="text-success">$reportTime</i>
    </small><br>
    $late_row
    <small class="text-muted" id="working_hours">
        <b>Working Hours:</b>&nbsp;<i class="text-success">$workingHours Hrs.</i>
    </small>
</div>
HTML;
                $response['error'] = false;
                $response['row'] = $row;
                $response['message'] = "Attendance recorded successfully";
                sendRes();
                break;
            case CYCLE_ACTIVE:
                // $att_id = (isset($ajax_form_data['att_id'])) ? $ajax_form_data['att_id'] : 0;
                $early_logOff_mints = (isset($ajax_form_data['early_mints'])) ? $ajax_form_data['early_mints'] : '';
                $earlyLogOffReason = (isset($ajax_form_data['early_reason'])) ? $ajax_form_data['early_reason'] : '';

                if ($att_id == 0) {
                    $response['message'] = ERROR_3;
                    sendRes();
                }
                $getReportingTime = getData(Table::ATTENDANCE, [
                    ATTENDANCE::REPORTING_TIME
                ], [
                    ATTENDANCE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID],
                    ATTENDANCE::ID => $att_id,
                    ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
                    ATTENDANCE::STATUS => ACTIVE_STATUS,
                    ATTENDANCE::ACTIVE => CYCLE_ACTIVE
                ]);
                if (count($getReportingTime)>0) {
                    $reporting_time = $getReportingTime[0][ATTENDANCE::REPORTING_TIME];
                }
                $working_hours = getWorkingHrs($reporting_time, $logOffTime);
                // echo 'working_hours: '. $working_hours;
                $cols = [
                    ATTENDANCE::LOG_OFF_TIME => $logOffTime,
                    ATTENDANCE::WORKING_HOURS => $working_hours,
                    ATTENDANCE::ACTIVE => CYCLE_CLOSED
                ];
                if (($early_logOff_mints != '') && ($earlyLogOffReason != '')) {
                    $cols[ATTENDANCE::EARLY_LOG_OFF_MINTS] = $early_logOff_mints;
                    $cols[ATTENDANCE::EARLY_LOG_OFF_REASON] = $earlyLogOffReason;
                }
                $saveLogOff = updateData(Table::ATTENDANCE, $cols, [
                    ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
                    ATTENDANCE::ID => $att_id,
                    ATTENDANCE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID]
                ]);
                if (!$saveLogOff['res']) {
                    logError('unabled to record log off for the employee id: '. $_SESSION[EMPLOYEE_ID].', Attendance ID: '. $att_id, $saveLogOff['error']);
                    $response['message'] = 'Failed to Log Off';
                    sendRes();
                }
                $current_cycle = CYCLE_CLOSED;
                $live_time = getFormattedDateTime(getToday(), LONG_DATE_TIME_FORMAT);
                $reportingTime = getToday(true, '', 'H:i');
                $workingHours = $working_hours.' Hrs.';
                $reportTime = getFormattedDateTime(getToday(true, '', 'H:i'), LONG_TIME_FORMAT);
                $row =<<<HTML
<div class="col-md-2 col-lg-2 col-sm-12" style="padding: 30px;">
</div>
<div class="col-md-3 col-lg-3 col-sm-12">
    <button class="btn btn-success" type="button" onclick="javascript:void(0);" data-att="$current_cycle" style="
        border-radius: 50%;
        padding: 30px;
        padding-left: 30px;
        padding-right: 30px;
    "><i class="fas fa-2x fa-check"></i></button>
</div>
<div class="col-md-7 col-lg-7 col-sm-12 text-left" style="margin-top: 10px;">
    <span id="live_date_time">$live_time</span><br>
    <small class="text-muted" id="attendance_msg">
        <b>*</b>&nbsp;<i class="text-success">Your Attendance has been recorded for today.</i>
    </small><br>
    <small class="text-muted" id="working_hours">
        <b>Working Hours:</b>&nbsp;<i class="text-success">$workingHours</i>
    </small>
</div>
HTML;
                $response['error'] = false;
                $response['message'] = "Thank You !";
                $response['row'] = $row;
                $response['att_id'] = $att_id;
                sendRes();
                exit;
                break;
        }
        
        break;
    case 'CALCULATE_EARLY_LOG_OFF_TIME':
        $reporting_time = $report_time = $att_id = 0;
        $logOff_time = getToday(true, '', 'H:i');
        $early_logOff_mints = '';
        $is_late = false;

        $getReportTime = getData(Table::EMPLOYEE_DETAILS,[
            EMPLOYEE_DETAILS::REPORTING_TIME
        ], [
            EMPLOYEE_DETAILS::ID => $_SESSION[EMPLOYEE_ID],
            EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
            EMPLOYEE_DETAILS::STATUS => ACTIVE_STATUS
        ]);
        $getReportingTime = getData(Table::ATTENDANCE, [
            ATTENDANCE::ID,
            ATTENDANCE::REPORTING_TIME
        ], [
            ATTENDANCE::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID],
            ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
            ATTENDANCE::STATUS => ACTIVE_STATUS,
            ATTENDANCE::ACTIVE => CYCLE_ACTIVE,
            ATTENDANCE::ATTENDANCE_DATE => getToday(false),
            ATTENDANCE::ATTENDANCE_MONTH => getToday(false, 'm'),
            ATTENDANCE::ATTENDANCE_YEAR => getToday(false, 'Y')
        ]);
        if (count($getReportTime)>0) {
            $report_time = (int)$getReportTime[0][EMPLOYEE_DETAILS::REPORTING_TIME];
        } else {
            $report_time = (int)DEFAULT_REPORT_TIME;
        }
        if (count($getReportingTime)>0) {
            $reporting_time = $getReportingTime[0][ATTENDANCE::REPORTING_TIME];
            $att_id = $getReportingTime[0][ATTENDANCE::ID];
        }

        if (($reporting_time == 0) || ($report_time == 0)) {
            $response['message'] = ERROR_2;
            logError("Unable to get Report time & Reporting time to Log off the employee ID: ". $_SESSION[EMPLOYEE_ID],'');
            sendRes();
        }

        
        $reporting_hour = (explode(":", $report_time))[0];
        $reporting_mint = (explode(":", $reporting_time))[1];
        $logOff_hour = (explode(":", $logOff_time))[0];
        $logOff_mint = (explode(":", $logOff_time))[1];

        $right_hour = ((int)$report_time + RIGHT_WORK_HOUR);

        if (((int)$logOff_hour < (int)$right_hour)) {
            if (((int)$right_hour - (int)$logOff_hour) == 1) {
                $early_logOff_mints = ((int)$right_hour - (int)$logOff_hour). ' Hrs';
                if ((60 - (int)$logOff_mint) > GRACE_TIME) {
                    $early_logOff_mints = (60 - (int)$logOff_mint). ' Mints.';
                } else {
                    $is_late = false;
                    $response['error'] = false;
                    $response['is_late'] = $is_late;
                    $response['early_logOff_mints'] = 'NO LATE';
                    $response['att_id'] = $att_id;
                    sendRes();
                }
            } else {
                if (((int)$right_hour - (int)$logOff_hour) > 1) {
                    $early_logOff_mints = ((int)$right_hour - (int)$logOff_hour). ' Hrs';
                    if (((int)$logOff_mint != 00)) {
                        $early_logOff_mints = (((int)$right_hour - (int)$logOff_hour) - 1). ' Hrs. And '.(60 - (int)$logOff_mint). ' Mints.';
                    }
                } else {
                    $is_late = false;
                    $response['error'] = false;
                    $response['is_late'] = $is_late;
                    $response['early_logOff_mints'] = 'NO LATE';
                    $response['att_id'] = $att_id;
                    sendRes();
                }
            }
            $is_late = true;
            $response['error'] = false;
            $response['is_late'] = $is_late;
            $response['early_logOff_mints'] = $early_logOff_mints;
            $response['att_id'] = $att_id;
            sendRes();
        }        
        $is_late = false;
        $response['error'] = false;
        $response['is_late'] = $is_late;
        $response['early_logOff_mints'] = $early_logOff_mints;
        $response['att_id'] = $att_id;
        sendRes();
        break;
    case 'GET_ATTENDANCE_RECORD':
        $emp_id = (isset($ajax_form_data['emp']))   ? $ajax_form_data['emp']   : 0;
        $month  = (isset($ajax_form_data['month'])) ? $ajax_form_data['month'] : 0;
        $year   = (isset($ajax_form_data['year']))  ? $ajax_form_data['year']  : 0;
        $mode   = (isset($ajax_form_data['mode']))  ? $ajax_form_data['mode']  : 1;

        if ($mode == 2) {
            $emp_id = $_SESSION[EMPLOYEE_ID];
        }

        if (($emp_id == 0) || ($month == 0) || ($year == 0)) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }


        $cur_year = date("Y");
        $cur_month = date("m");
        $dateObj            = DateTime::createFromFormat('!m', $cur_month);
        $bill_monthName     = $dateObj->format('F');
        if ($year > $cur_year) {
            $response['error'] = true;
            $response['message'] = 'You Cannot Select Year Greater Than ' . $cur_year;
            sendRes();
        } elseif (($month > $cur_month)) {
            if ($year >= $cur_year) {
                $response['error'] = true;
                $response['message'] = 'You Cannot Select Month Greater Than ' . $bill_monthName . ' In The Year ' . $cur_year . '';
                sendRes();
            }
        }
        $month = ($month < 10) ? '0' . $month : $month;
        $firstday = date($year . '-' . $month . '-01');
        // $response['firstday'] = $firstday;
        $lastday = "";
        if ($month == date("m") && $year == date('Y')) {
            $lastday = date($year . '-' . $month . "-d");
        } else {
            // $lastday = date_format(date_create(date(date('t', strtotime($firstday)) . '-' . $month . '-' . $year)), "Y-m-d");
            $dt = DateTime::createFromFormat("Y-m-d", $firstday);
            $lastday = date($year . '-' . $month . '-t', $dt->getTimestamp());
        }
        // echo $firstday.'<br>'.$lastday;
        // exit;
        $arr = [];
        $att_tr = $emp_details = '';
        $sl = 0;
        $att_dt = [];
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
        EMPLOYEE_DETAILS::ID." = ". $emp_id;
        $getEmpData = getCustomData($getEmpDataSql);
        $employee_id = $getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_ID];
        $emp_details = '<h6><b>Name: </b>'.$getEmpData[0][EMPLOYEE_DETAILS::EMPLOYEE_NAME].'</h6>';
        $emp_details .= '<h6><b>EMP ID: </b>'.$employee_id.'</h6>';
        $emp_details .= '<h6><b>Department: </b>'.$getEmpData[0][DEPARTMENTS::DEPARTMENT_NAME].'</h6>';
        $emp_details .= '<h6><b>Designation: </b>'.$getEmpData[0][DESIGNATIONS::DESIGNATION_TITLE].'</h6>';
        while (strtotime($firstday) <= strtotime($lastday)) {
            $day_num = date('d/m/Y', strtotime($firstday));
            $id_date = date('Ymd', strtotime($firstday));
            // $table_tr_id['id_date'] = $id_date;
            $curr_date = date("Y-m-d", strtotime($firstday));
            $day_name = date('l', strtotime($firstday));
            $firstday = date("Y-m-d", strtotime("+1 day", strtotime($firstday)));
            $arr[] = [
                'day_num: ' => $day_num,
                'id_date: ' => $id_date,
                'day_name: ' => $day_name,
                'firstday: ' => $firstday
            ];

            $show_date = $day_name.' - '.$day_num;
            $sl++;

            // start from here
            $getAttDataSql = "SELECT * FROM 
            ". Table::ATTENDANCE ." WHERE 
            ". ATTENDANCE::EMPLOYEE_ID ." = ". $emp_id ." AND 
            ". ATTENDANCE::ATTENDANCE_DATE ." = '". $curr_date ."' AND 
            ". ATTENDANCE::ATTENDANCE_MONTH ." = ".$month." AND 
            ". ATTENDANCE::ATTENDANCE_YEAR ." = ".$year." AND 
            ". ATTENDANCE::CLIENT_ID ." = ".$_SESSION[CLIENT_ID]." AND 
            ". ATTENDANCE::STATUS ." = '".ACTIVE_STATUS."'";
            
            // $getAttDetails = getData(Table::ATTENDANCE, [], [
            //     ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
            //     ATTENDANCE::STATUS => ACTIVE_STATUS,
            //     ATTENDANCE::ATTENDANCE_MONTH => $month,
            //     ATTENDANCE::ATTENDANCE_YEAR => $year,
            //     ATTENDANCE::EMPLOYEE_ID => $emp_id,
            //     ATTENDANCE::ATTENDANCE_DATE => $curr_date
            // ]);
            $getAttDetails = getCustomData($getAttDataSql);
            // rip($getAttDetails);
            // exit;
            // echo $getAttDataSql;s
            $att_dt[$curr_date] = $getAttDetails;
            if (count($getAttDetails)>0) {
                // rip($getAttDetails);
                // exit;
                $v = $getAttDetails[0];
                // foreach ($getAttDetails as $k => $v) {
                // $userType = 0;
                $employee_id = $late = '';
                $late = '';
                if ($v[ATTENDANCE::IS_LATE_ENTRY] == 1) {
                    $late = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">Late</span>';
                }
                
                $getUserData = getData(Table::USERS, [
                    Users::USER_TYPE
                ], [
                    Users::ACTIVE => 1,
                    Users::STATUS => ACTIVE_STATUS,
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    Users::EMPLOYEE_ID => $v[ATTENDANCE::EMPLOYEE_ID]
                ]);
                $userType = 0;
                if (count($getUserData)>0) {
                    $userType = $getUserData[0][Users::USER_TYPE];
                }
                // rip($getUserData);
                // exit;
                // echo $userType;
                // exit;
                

                $date = getFormattedDateTime($v[ATTENDANCE::ATTENDANCE_DATE], LONG_DATE_FORMAT);
                $report_time = getFormattedDateTime($v[ATTENDANCE::REPORTING_TIME], LONG_TIME_FORMAT);
                $logOff_time = ($v[ATTENDANCE::LOG_OFF_TIME] != '') ? getFormattedDateTime($v[ATTENDANCE::LOG_OFF_TIME], LONG_TIME_FORMAT) : 'NOT YET';
                $workingHours = ($v[ATTENDANCE::WORKING_HOURS] != '') ? $v[ATTENDANCE::WORKING_HOURS] : ((($v[ATTENDANCE::REPORTING_TIME] != '') && (getToday(false, 'Y-m-d') == $v[ATTENDANCE::ATTENDANCE_DATE])) ? getWorkingHrs(getFormattedDateTime($v[ATTENDANCE::REPORTING_TIME], 'H:i'), getToday(true, '', 'H:i')) : EMPTY_VALUE);
                $lateReason = ($v[ATTENDANCE::LATE_ENTRY_REASON] != '') ? $v[ATTENDANCE::LATE_ENTRY_REASON] : EMPTY_VALUE;
                $earlyReason = ($v[ATTENDANCE::EARLY_LOG_OFF_REASON] != '') ? $v[ATTENDANCE::EARLY_LOG_OFF_REASON] : EMPTY_VALUE;
                $lateMints = ($v[ATTENDANCE::LATE_MINTS] != '') ? $v[ATTENDANCE::LATE_MINTS] : EMPTY_VALUE;
                $att_tr .= '<tr id="att_list_'.$v[ATTENDANCE::ID].'">
                                <td>'.$sl.$late.'</td>
                                <td>'.$show_date.'</td>';
                if ($mode != 2) {
                    // $att_tr .= '<td class="text-left">'.$emp_details.'</td>';
                }
                $att_tr .=     '<td>'.$report_time.'</td>
                                <td>'.$logOff_time.'</td>
                                <td>'.$workingHours.'</td>
                                <td>'.$lateMints.'</td>
                                <td>'.$earlyReason.'</td>
                            </tr>';
                // }
            } else {
                if ($day_name == "Sunday") {
                    $att_tr .= '
                    <tr id="att_list_'.$id_date.'">
                        <td>'.$sl.'</td>
                        <td>'.$show_date.'</td>
                        <td colspan="5">
                            <div class="alert alert-danger" role="alert">
                                <strong>Sunday - OFF Day</strong>
                            </div>
                        </td>
                    </tr>';
                } else {
                    $att_tr .= '
                    <tr id="att_list_'.$id_date.'">
                        <td>'.$sl.'</td>
                        <td>'.$show_date.'</td>
                        <td colspan="5">
                            <div class="alert alert-danger" role="alert">
                                ABSENT !
                            </div>
                        </td>
                    </tr>';
                }
            }

        }        
        // rip($arr);
        // exit;
        $response['error'] = false;
        $response['att_tr'] = $att_tr;
        $response['att_dt'] = $att_dt;
        $response['emp_details'] = $emp_details;
        sendRes();
        break;
    case 'GET_DATEWISE_ATTENDANCE_RECORD':
        $att_date = (isset($ajax_form_data['date'])) ? $ajax_form_data['date'] : '';
        if ($att_date == '') {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }

        $month = getFormattedDateTime($att_date, 'm');
        $year = getFormattedDateTime($att_date, 'Y');
        $date = getFormattedDateTime($att_date, 'd');
        $day_name = date('l', strtotime($att_date));
        $att_tr = '';
        // echo "date: ".$date."<br>";
        // echo "month: ".$month."<br>";;
        // echo "year: ".$year;
        // exit;
        $cur_year           = date("Y");
        $cur_month          = date("m");
        $dateObj            = DateTime::createFromFormat('!m', $cur_month);
        $bill_monthName     = $dateObj->format('F');
        if ($year > $cur_year) {
            $response['error'] = true;
            $response['message'] = 'You Cannot Select Year Greater Than ' . $cur_year;
            sendRes();
        } elseif (($month > $cur_month)) {
            if ($year >= $cur_year) {
                $response['error'] = true;
                $response['message'] = 'You Cannot Select Month Greater Than ' . $bill_monthName . ' In The Year ' . $cur_year . '';
                sendRes();
            }
        }
        $month = ($month < 10) ? '0' . $month : $month;
        $getEmpDataSql = "SELECT emp.". 
                EMPLOYEE_DETAILS::ID.", emp.". 
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
                EMPLOYEE_DETAILS::STATUS." = '". ACTIVE_STATUS. "'";
        $getEmpData = getCustomData($getEmpDataSql);
        // rip($getEmpData);
        // exit;
        if (count($getEmpData)>0) {
            foreach ($getEmpData as $k => $v) {
                $sl = ($k+1);
                $getUserData = getData(Table::USERS, [
                    Users::USER_TYPE
                ], [
                    Users::ACTIVE => 1,
                    Users::STATUS => ACTIVE_STATUS,
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    Users::EMPLOYEE_ID => $v[EMPLOYEE_DETAILS::ID]
                ]);
                $userType = 0;
                if (count($getUserData)>0) {
                    $userType = $getUserData[0][Users::USER_TYPE];
                }
                $getAttDetails = getData(Table::ATTENDANCE, ['*'], [
                    ATTENDANCE::CLIENT_ID => $_SESSION[CLIENT_ID],
                    ATTENDANCE::STATUS => ACTIVE_STATUS,
                    ATTENDANCE::ATTENDANCE_MONTH => $month,
                    ATTENDANCE::ATTENDANCE_YEAR => $year,
                    ATTENDANCE::EMPLOYEE_ID => $v[EMPLOYEE_DETAILS::ID],
                    ATTENDANCE::ATTENDANCE_DATE => $att_date
                ], [], [], [ATTENDANCE::ID], "DESC");

                $emp_id = $v[EMPLOYEE_DETAILS::EMPLOYEE_ID];
                $emp_details = '<b>Name: </b>'.$v[EMPLOYEE_DETAILS::EMPLOYEE_NAME];
                $emp_details .= '<br><b>EMP ID: </b>'.$emp_id;
                $emp_details .= '<br><b>Department: </b>'.$v[DEPARTMENTS::DEPARTMENT_NAME];
                $emp_details .= '<br><b>Designation: </b>'.$v[DESIGNATIONS::DESIGNATION_TITLE];

                if (count($getAttDetails)>0) {
                    $attData = $getAttDetails[0];
                    $date = getFormattedDateTime($attData[ATTENDANCE::ATTENDANCE_DATE], LONG_DATE_FORMAT);
                    $report_time = getFormattedDateTime($attData[ATTENDANCE::REPORTING_TIME], LONG_TIME_FORMAT);
                    $logOff_time = ($attData[ATTENDANCE::LOG_OFF_TIME] != '') ? getFormattedDateTime($attData[ATTENDANCE::LOG_OFF_TIME], LONG_TIME_FORMAT) : 'NOT YET';
                    $workingHours = ($attData[ATTENDANCE::WORKING_HOURS] != '') ? $attData[ATTENDANCE::WORKING_HOURS] : ((($attData[ATTENDANCE::REPORTING_TIME] != '') && ($attData[ATTENDANCE::ATTENDANCE_DATE] == getToday(false))) ? getWorkingHrs(getFormattedDateTime($attData[ATTENDANCE::REPORTING_TIME], 'H:i'), getToday(true, '', 'H:i')) : EMPTY_VALUE);
                    $lateReason = ($attData[ATTENDANCE::LATE_ENTRY_REASON] != '') ? $attData[ATTENDANCE::LATE_ENTRY_REASON] : EMPTY_VALUE;
                    $earlyReason = ($attData[ATTENDANCE::EARLY_LOG_OFF_REASON] != '') ? $attData[ATTENDANCE::EARLY_LOG_OFF_REASON] : EMPTY_VALUE;
                    $lateMints = ($attData[ATTENDANCE::LATE_MINTS] != '') ? $attData[ATTENDANCE::LATE_MINTS] : EMPTY_VALUE;
                    $late = '';
                    if ($attData[ATTENDANCE::IS_LATE_ENTRY] == 1) {
                        $late = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">Late</span>';
                    }
                    $att_tr .= '<tr id="att_list_'.$v[EMPLOYEE_DETAILS::ID].'">
                                    <td>'.$sl.$late.'</td>
                                    <td class="text-left">'.$emp_details.'</td>
                                    <td>'.$report_time.'</td>
                                    <td>'.$logOff_time.'</td>
                                    <td>'.$workingHours.'</td>
                                    <td>'.$lateMints.'</td>
                                    <td>'.$earlyReason.'</td>
                                </tr>';
                } else {
                    if ($day_name == 'Sunday') {
                        $att_tr .= '
                        <tr id="att_list_'.$v[EMPLOYEE_DETAILS::ID].'">
                            <td>'.$sl.'</td>
                            <td class="text-left">'.$emp_details.'</td>
                            <td colspan="5">
                                <div class="alert alert-danger" role="alert">
                                    <strong>Sunday - OFF Day !</strong>
                                </div>
                            </td>
                        </tr>';
                    } else {
                        $att_tr .= '
                        <tr id="att_list_'.$v[EMPLOYEE_DETAILS::ID].'">
                            <td>'.$sl.'</td>
                            <td class="text-left">'.$emp_details.'</td>
                            <td colspan="5">
                                <div class="alert alert-danger" role="alert">
                                    ABSENT !
                                </div>
                            </td>
                        </tr>';
                    }
                }
            }
        }  else {
            $att_tr = '<tr class="animated fadeInDown">
            <td colspan="7">
                <div class="alert alert-danger" role="alert">
                    No Attendance found ! Please select the particulars to get records.
                </div>
            </td>
            </tr>';
        }
        $response['error'] = false;
        $response['att_tr'] = $att_tr;
        sendRes();
        break;
    case 'SEND_MESSAGE':
        $uid = (isset($ajax_form_data['uid'])) ? $ajax_form_data['uid'] : 0;
        $msg = (isset($ajax_form_data['msg'])) ? altRealEscape($ajax_form_data['msg']) : "";
        $freshConversetion = false;
        $msg_id = 0;
        if (($uid == 0) && ($msg == "")) {
            $response['message'] = ERROR_2;
            sendRes();
        }
        $getMsgData = getData(Table::MESSAGES, [
            MESSAGES::RECEIVER_USER_ID,
            MESSAGES::SENDER_USER_ID,
            MESSAGES::CREATION_DATE,
            MESSAGES::ID
        ], [
            MESSAGES::CLIENT_ID => $_SESSION[CLIENT_ID],
            MESSAGES::STATUS => ACTIVE_STATUS
        ]);
        if (count($getMsgData)>0) {
            foreach ($getMsgData as $k => $v) {
                // if ($v[MESSAGES::SENDER_USER_ID] == $uid) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::SENDER_USER_ID] == $_SESSION[RID]) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::RECEIVER_USER_ID] == $uid) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::RECEIVER_USER_ID] == $_SESSION[RID]) {
                //     $msg_id = $v[MESSAGES::ID];
                // } else {
                //     $freshConversetion = true;
                // }
                if (($v[MESSAGES::SENDER_USER_ID] == $uid) && ($v[MESSAGES::RECEIVER_USER_ID] == $_SESSION[RID])) {
                    $msg_id = $v[MESSAGES::ID];
                } elseif (($v[MESSAGES::SENDER_USER_ID] == $_SESSION[RID]) && ($v[MESSAGES::RECEIVER_USER_ID] == $uid)) {
                    $msg_id = $v[MESSAGES::ID];
                } else {
                    $freshConversetion = true;
                }
            }
        } else {
            $freshConversetion = true;
        }
        if ($msg_id == 0) {
            $saveNewConv = setData(Table::MESSAGES, [
                MESSAGES::CLIENT_ID => $_SESSION[CLIENT_ID],
                MESSAGES::SENDER_USER_ID => $_SESSION[RID],
                MESSAGES::RECEIVER_USER_ID => $uid,
                MESSAGES::STATUS => ACTIVE_STATUS,
                MESSAGES::CREATION_DATE => getToday()
            ]);
            if (!$saveNewConv['res']) {
                logError("Unabled to save new message conversation. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid, $saveNewConv['error']);
                $response['message'] = ERROR_2;
                sendRes();
            }
            $msg_id = $saveNewConv['id'];
        }
        if ($msg_id != 0) {
            $saveConvDetails = setData(Table::MESSAGE_DETAILS, [
                MESSAGE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                MESSAGE_DETAILS::MESSAGE_ID => $msg_id,
                MESSAGE_DETAILS::MESSAGE_TXT => $msg,
                MESSAGE_DETAILS::USER_ID => $_SESSION[RID],
                MESSAGE_DETAILS::STATUS => ACTIVE_STATUS,
                MESSAGE_DETAILS::CREATION_DATE => getToday()
            ]);
            if (!$saveConvDetails['res']) {
                logError("Unabled to save message details. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid . ", Message ID: ". $msg_id, $saveConvDetails['error']);
                $response['message'] = ERROR_2;
                sendRes();
            }
            $newMsgSet = setData(Table::NEW_MESSAGE_LOG, [
                NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $uid,
                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $_SESSION[RID],
                NEW_MESSAGE_LOG::NEW_MSG => 1,
                NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                NEW_MESSAGE_LOG::CREATION_DATE => getToday()
            ]);
            if (!$newMsgSet['res']) {
                logError("Unabled to save New message alert. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid . ", Message ID: ". $msg_id, $newMsgSet['error']);
                // $response['message'] = ERROR_2;
                // sendRes();
            }
            $time = getToday(false, LONG_DATE_TIME_FORMAT);
            $mm = makeUrltoLink($msg);
            $html = <<<HTML
<div class="row single_msg_row">
    <div class="col-6 single_msg_first_col">&nbsp;</div>
    <div class="col-6 single_msg_second_col">
        <div class="jumbotron jumbotron-fluid single_chat">
            <div class="container">
                <p class="lead display-8">$mm</p>
                <br />
                <span class="chat_date_time"><small><em>$time</em></small></span>
            </div>
        </div>
    </div>
</div>
HTML;
            $response['error'] = false;
            $response['message'] = "Sent !";
            $response['html'] = $html;
            sendRes();
        }
        break;
    case 'GET_CHAT_HISTOSRY':
        $uid = (isset($ajax_form_data['uid'])) ? $ajax_form_data['uid'] : 0;
        $msg_id = $new_chat_count = 0;
        $freshConversetion = false;
        $html = '';
        if ($uid == 0) {
            $response['message'] = ERROR_1;
            sendRes();
        }

        $getMsgData = getData(Table::MESSAGES, [
            MESSAGES::RECEIVER_USER_ID,
            MESSAGES::SENDER_USER_ID,
            MESSAGES::CREATION_DATE,
            MESSAGES::ID
        ], [
            MESSAGES::CLIENT_ID => $_SESSION[CLIENT_ID],
            MESSAGES::STATUS => ACTIVE_STATUS
        ]);
        if (count($getMsgData)>0) {
            foreach ($getMsgData as $k => $v) {
                if (($v[MESSAGES::SENDER_USER_ID] == $uid) && ($v[MESSAGES::RECEIVER_USER_ID] == $_SESSION[RID])) {
                    $msg_id = $v[MESSAGES::ID];
                } elseif (($v[MESSAGES::SENDER_USER_ID] == $_SESSION[RID]) && ($v[MESSAGES::RECEIVER_USER_ID] == $uid)) {
                    $msg_id = $v[MESSAGES::ID];
                } else {
                    $freshConversetion = true;
                }
            }
        } else {
            $freshConversetion = true;
        }
        // echo "msg_id: ". $msg_id;
        // echo "<br>: ". $freshConversetion;
        // exit;
        if ($msg_id != 0) {
            $chatArr = [];
            $getMsgDetails = getData(Table::MESSAGE_DETAILS, ['*'], [
                MESSAGE_DETAILS::MESSAGE_ID => $msg_id,
                MESSAGE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                MESSAGE_DETAILS::STATUS => ACTIVE_STATUS
            ], [], [], [MESSAGE_DETAILS::ID], "ASC");
            // rip($getMsgDetails);
            if (count($getMsgDetails)>0) {
                // $html = getSpinner(true, "chat_card_body_loader");
                foreach ($getMsgDetails as $k => $v) {
                    $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID,
                        NEW_MESSAGE_LOG::ID
                    ], [
                        NEW_MESSAGE_LOG::NEW_MSG => 1,
                        NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                        NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $v[MESSAGE_DETAILS::USER_ID]
                    ]);
                    if (count($getNewMsg)>0) {
                        if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $v[MESSAGE_DETAILS::USER_ID])) {
                            $setMsgRead = updateData(Table::NEW_MESSAGE_LOG, [
                                NEW_MESSAGE_LOG::NEW_MSG => 0,
                                NEW_MESSAGE_LOG::STATUS => DEACTIVE_STATUS
                            ],  [
                                NEW_MESSAGE_LOG::ID => $getNewMsg[0][NEW_MESSAGE_LOG::ID]
                            ]);
                            if (!$setMsgRead['res']) {
                                logError("Unabled to set Message read status. New Message ID: ". $getNewMsg[0][NEW_MESSAGE_LOG::ID]. ", Message Details ID: ". $v[MESSAGE_DETAILS::ID], $setMsgRead['error']);
                            }
                        }
                    }

                    $chatArr[getFormattedDateTime($v[MESSAGE_DETAILS::CREATION_DATE], 'Y-m-d')][getFormattedDateTime($v[MESSAGE_DETAILS::CREATION_DATE], 'H:i:s')] = $v;
                    $msg = $v[MESSAGE_DETAILS::MESSAGE_TXT];
                    $mm = makeUrltoLink($msg);
                    $date = getFormattedDateTime($v[MESSAGE_DETAILS::CREATION_DATE], LONG_DATE_TIME_FORMAT);
                    $attachment_name = $v[MESSAGE_DETAILS::ATTACHMENT_NAME];
                    $attachment_link = ($v[MESSAGE_DETAILS::ATTACHMENT_NAME] != "") ? UPLOAD_CHAT_FILE_URL.$v[MESSAGE_DETAILS::ATTACHMENT_NAME] : "";
                    
                    $image_preview = "";
                    if ($attachment_name != "") {
                        if (in_array(pathinfo($attachment_name, PATHINFO_EXTENSION), ALL_IMAGE_TYPE)) {
                            // $image_preview = '<img src="'.$msg_link.'" class="img-thumbnail">
                            $image_preview = '
                            <a href="'.$attachment_link.'" data-lightbox="image-1" data-title="'.$attachment_name.'"><img src="'.$attachment_link.'" class="img-thumbnail"></a>
                            ';
                        }
                    }
                    if ($attachment_name != "") {
                        if ($v[MESSAGE_DETAILS::USER_ID] == $_SESSION[RID]) {
                        $html .= 
'<div class="row single_msg_row">
    <div class="col-6 single_msg_second_col"></div>
    <div class="col-6 single_msg_first_col">
        <div class="jumbotron jumbotron-fluid single_chat">
            <div class="container">
                <a class="lead text-primary" target="_blank" href="'.$attachment_link.'" style="text-decoration: underline;" download="'.$attachment_link.'">Click to Download'.$image_preview.'<span class="badge badge-info" style="font-size: 60% !important; margin-left: 10px;">Attachment</span>'.$attachment_name.'</a>
                <br />
                <span class="chat_date_time"><small><em>'.$date.'</em></small></span>
            </div>
        </div>
    </div>
</div>';
                    } else {
                    $html .= 
'<div class="row single_msg_row">
    <div class="col-6 single_msg_first_col">
        <div class="jumbotron jumbotron-fluid single_chat">
            <div class="container">
                <a class="lead text-primary" target="_blank" href="'.$attachment_link.'" style="text-decoration: underline;" download="'.$attachment_link.'">Click to Download'.$image_preview.'<span class="badge badge-info" style="font-size: 60% !important; margin-left: 10px;">Attachment</span>'.$attachment_name.'</a>
                <br />
                <span class="chat_date_time"><small><em>'.$date.'</em></small></span>
            </div>
        </div>
    </div>
    <div class="col-6 single_msg_second_col"></div>
</div>';
                    }
                    } else {
                        if ($v[MESSAGE_DETAILS::USER_ID] == $_SESSION[RID]) {
                            $html .= 
    '<div class="row single_msg_row">
        <div class="col-6 single_msg_second_col"></div>
        <div class="col-6 single_msg_first_col">
            <div class="jumbotron jumbotron-fluid single_chat">
                <div class="container">
                    <p class="lead display-8">'.$mm.'</p>
                    <br />
                    <span class="chat_date_time"><small><em>'.$date.'</em></small></span>
                </div>
            </div>
        </div>
    </div>';
                        } else {
                        $html .= 
    '<div class="row single_msg_row">
        <div class="col-6 single_msg_first_col">
            <div class="jumbotron jumbotron-fluid single_chat">
                <div class="container">
                    <p class="lead display-8">'.$mm.'</p>
                    <br />
                    <span class="chat_date_time"><small><em>'.$date.'</em></small></span>
                </div>
            </div>
        </div>
        <div class="col-6 single_msg_second_col"></div>
    </div>';
                        }
                    }
                    
                    
                }

            }
        }
        //Getting Total Chat count
        if (isUserLoggedIn()) {
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
                    $new_chat_count = count($getNewMsg);
                }
            }
        }
        $response['error'] = false;
        $response['html'] = $html;
        $response['chat'] = $new_chat_count;
        sendRes();
        break;
    case 'SEND_ATTACHMENT':
        $uid = (isset($ajax_form_data['uid'])) ? $ajax_form_data['uid'] : 0;
        $freshConversetion = $hasUploaded = false;
        $msg_id = 0;
        $file_name = '';

        if ($uid == 0) {
            $response['message'] = ERROR_1;
            sendRes();
        }

        if (isset($_FILES['media_file'])) {
            $file = $_FILES['media_file'];
            $file_type = $file['type'];
            $file_size = $file['size'];
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $tmp_name = $file['tmp_name'];
            $narr = explode('.'.$file_extension,$file['name']);
            // rip($narr);
            // exit;
            $file_real_name = $narr[0];

            if(!in_array(strtolower($file_extension), ALLOWED_CHAT_FILE_TYPE)) {
                $response['message'] = 'File type not suported.';
                sendRes();
            } 
            ## Check size
            if($file_size > ALLOWED_MAX_FILESIZE) {
                $response['message'] = 'File size exceeded.';
                sendRes();
            }
            // Create the target path
            $file_name = $file_real_name . '_' . getToday(false,'Ymd') . '_' . date('His');
            $file_name = removeSpace($file_name) . '.' . $file_extension;
            ## Image
            $target_path = UPLOAD_CHAT_FILE_PATH . $file_name;
            $hasUploaded = move_uploaded_file($tmp_name, $target_path);
            if(!$hasUploaded) {
                logError("Chat File Upload Failed.", $file["error"]);
                $response['message'] = 'File upload failed.';
                sendRes();
            }
        }

        
        if (($uid == 0) && ($msg == "")) {
            $response['message'] = ERROR_2;
            sendRes();
        }
        $getMsgData = getData(Table::MESSAGES, [
            MESSAGES::RECEIVER_USER_ID,
            MESSAGES::SENDER_USER_ID,
            MESSAGES::CREATION_DATE,
            MESSAGES::ID
        ], [
            MESSAGES::CLIENT_ID => $_SESSION[CLIENT_ID],
            MESSAGES::STATUS => ACTIVE_STATUS
        ]);
        if (count($getMsgData)>0) {
            foreach ($getMsgData as $k => $v) {
                // if ($v[MESSAGES::SENDER_USER_ID] == $uid) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::SENDER_USER_ID] == $_SESSION[RID]) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::RECEIVER_USER_ID] == $uid) {
                //     $msg_id = $v[MESSAGES::ID];
                // } elseif ($v[MESSAGES::RECEIVER_USER_ID] == $_SESSION[RID]) {
                //     $msg_id = $v[MESSAGES::ID];
                // } else {
                //     $freshConversetion = true;
                // }
                if (($v[MESSAGES::SENDER_USER_ID] == $uid) && ($v[MESSAGES::RECEIVER_USER_ID] == $_SESSION[RID])) {
                    $msg_id = $v[MESSAGES::ID];
                } elseif (($v[MESSAGES::SENDER_USER_ID] == $_SESSION[RID]) && ($v[MESSAGES::RECEIVER_USER_ID] == $uid)) {
                    $msg_id = $v[MESSAGES::ID];
                } else {
                    $freshConversetion = true;
                }
            }
        } else {
            $freshConversetion = true;
        }
        if ($msg_id == 0) {
            $saveNewConv = setData(Table::MESSAGES, [
                MESSAGES::CLIENT_ID => $_SESSION[CLIENT_ID],
                MESSAGES::SENDER_USER_ID => $_SESSION[RID],
                MESSAGES::RECEIVER_USER_ID => $uid,
                MESSAGES::STATUS => ACTIVE_STATUS,
                MESSAGES::CREATION_DATE => getToday()
            ]);
            if (!$saveNewConv['res']) {
                logError("Unabled to save new message conversation. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid, $saveNewConv['error']);
                $response['message'] = ERROR_2;
                sendRes();
            }
            $msg_id = $saveNewConv['id'];
        }
        if ($msg_id != 0) {
            $saveConvDetails = setData(Table::MESSAGE_DETAILS, [
                MESSAGE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                MESSAGE_DETAILS::MESSAGE_ID => $msg_id,
                MESSAGE_DETAILS::ATTACHMENT_NAME => $file_name,
                MESSAGE_DETAILS::USER_ID => $_SESSION[RID],
                MESSAGE_DETAILS::STATUS => ACTIVE_STATUS,
                MESSAGE_DETAILS::CREATION_DATE => getToday()
            ]);
            if (!$saveConvDetails['res']) {
                logError("Unabled to save message details. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid . ", Message ID: ". $msg_id, $saveConvDetails['error']);
                $response['message'] = ERROR_2;
                sendRes();
            }
            $newMsgSet = setData(Table::NEW_MESSAGE_LOG, [
                NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $uid,
                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $_SESSION[RID],
                NEW_MESSAGE_LOG::NEW_MSG => 1,
                NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                NEW_MESSAGE_LOG::CREATION_DATE => getToday()
            ]);
            if (!$newMsgSet['res']) {
                logError("Unabled to save New message alert. Sender User ID: ". $_SESSION[RID] . ", Receiver User ID: ". $uid . ", Message ID: ". $msg_id, $newMsgSet['error']);
                // $response['message'] = ERROR_2;
                // sendRes();
            }
            $time = getToday(false, LONG_DATE_TIME_FORMAT);
            $msg_link = UPLOAD_CHAT_FILE_URL.$file_name;
            $image_preview = "";
            if ($file_name != "") {
                if (in_array(pathinfo($file_name, PATHINFO_EXTENSION), ALL_IMAGE_TYPE)) {
                    // $image_preview = '<img src="'.$msg_link.'" class="img-thumbnail">
                    $image_preview = '
                    <a href="'.$msg_link.'" data-lightbox="image-1" data-title="'.$file_name.'"><img src="'.$msg_link.'" class="img-thumbnail"></a>
                    ';
                }
            }
            $html = <<<HTML
<div class="row single_msg_row">
    <div class="col-6 single_msg_first_col">&nbsp;</div>
    <div class="col-6 single_msg_second_col">
        <div class="jumbotron jumbotron-fluid single_chat">
            <div class="container">
            <a class="lead text-primary" target="_blank" href="$msg_link" download="'.$msg_link.'" style="text-decoration: underline;">Click to Download $image_preview<span class="badge badge-info" style="font-size: 60% !important; margin-left: 10px;">Attachment</span>$file_name</a>
                <br />
                <span class="chat_date_time"><small><em>$time</em></small></span>
            </div>
        </div>
    </div>
</div>
HTML;
            $response['error'] = false;
            $response['message'] = "Sent !";
            $response['html'] = $html;
            sendRes();
        }
        break;
    case 'GET_TOTAL_CHAT_COUNT':
        $new_chat_count = 0;
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
                $new_chat_count = count($getNewMsg);
            }
        }
        $response['new_chat_count'] = $new_chat_count;
        $response['error'] = false;
        sendRes();
        break;
    case 'GET_USER_LIST_FOR_CHAT':
        $new = '';
        $user_list = '<ul class="list-group list-group-flush" id="chat_usertype_list">';
    foreach (USERS as $k => $v) {
        switch ($_SESSION[USER_TYPE]) {
            case SADMIN:
                if ($k != SADMIN) {
                    $user_list .= '<li class="list-group-item" id="chat_usertype_items_'.$k.'"><strong>'.$v.'</strong>';
                    $getUsers = getData(Table::USERS, [
                        Users::ID,
                        Users::NAME,
                        Users::EMPLOYEE_ID
                    ], [
                        Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                        USERS::USER_TYPE => $k,
                        Users::ACTIVE => 1,
                        Users::STATUS => ACTIVE_STATUS
                    ]);
                    if (count($getUsers)>0) {
                        foreach ($getUsers as $uk => $uv) {
                            if (($uv[Users::EMPLOYEE_ID] != 0) && ($uv[Users::EMPLOYEE_ID] != 1001)) {
                                $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                                    NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                                    NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                                ], [
                                    NEW_MESSAGE_LOG::NEW_MSG => 1,
                                    NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                                    NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                                    NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                                    NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $uv[Users::ID]
                                ]);
                                $new = '';
                                if (count($getNewMsg)>0) {
                                    if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $uv[Users::ID])) {
                                        $new = '&nbsp;<span id="new_msg_alert_'.$uv[Users::ID].'" class="blinking badge badge-primary" style="position: absolute;">'.count($getNewMsg).'</span>';
                                    }
                                }
                                $user_list .= '<ul class="list-group list-group-flush" id="chat_user_list_'. $uv[Users::ID] .'">';
                                $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">';
                                $user_list .= '<img src="'.CDN_URL.'images/User-Profile.png" class="rounded float-left" alt="user_logo">';
                                $user_list .= $uv[Users::NAME].$new.'</li>';
                                $user_list .= '</ul>';
                            }
                        }
                    }
                    $user_list .= '</li>';
                }
                break;
            case ADMIN:
            case EMPLOYEE:
            case MANAGER:
                $user_list .= '<li class="list-group-item" id="chat_usertype_items_'.$k.'"><strong>'.$v.'</strong>';
                $getUsers = getData(Table::USERS, [
                    Users::ID,
                    Users::NAME,
                    Users::EMPLOYEE_ID
                ], [
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    USERS::USER_TYPE => $k,
                    Users::ACTIVE => 1,
                    Users::STATUS => ACTIVE_STATUS
                ]);
                if (count($getUsers)>0) {
                    foreach ($getUsers as $uk => $uv) {
                        if (($uv[Users::EMPLOYEE_ID] != 0) && ($uv[Users::EMPLOYEE_ID] != $_SESSION[EMPLOYEE_ID])) {
                            $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                            ], [
                                NEW_MESSAGE_LOG::NEW_MSG => 1,
                                NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                                NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $uv[Users::ID]
                            ]);
                            $new = '';
                            if (count($getNewMsg)>0) {
                                if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $uv[Users::ID])) {
                                    $new = '&nbsp;<span id="new_msg_alert_'.$uv[Users::ID].'" class="blinking badge badge-primary" style="position: absolute;">'.count($getNewMsg).'</span>';
                                }
                            }
                            $user_list .= '<ul class="list-group list-group-flush" id="chat_user_list_'. $uv[Users::ID] .'">';
                            $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">';
                            $user_list .= '<img src="'.CDN_URL.'images/User-Profile.png" class="rounded float-left" alt="user_logo">';
                            $user_list .= $uv[Users::NAME].$new.'</li>';
                            $user_list .= '</ul>';
                        }
                    }
                }
                $user_list .= '</li>';
                break;
            case 12:
                $user_list .= '<li class="list-group-item" id="chat_usertype_items_'.$k.'"><strong>'.$v.'</strong>';
                $getUsers = getData(Table::USERS, [
                    Users::ID,
                    Users::NAME,
                    Users::EMPLOYEE_ID
                ], [
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    USERS::USER_TYPE => $k,
                    Users::ACTIVE => 1,
                    Users::STATUS => ACTIVE_STATUS
                ]);
                if (count($getUsers)>0) {
                    foreach ($getUsers as $uk => $uv) {
                        $getEmpUnderManeger = getData(Table::EMPLOYEE_REPORTING_MANAGER, [
                            EMPLOYEE_REPORTING_MANAGER::ID
                        ], [
                            EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID => $_SESSION[RID],
                            EMPLOYEE_REPORTING_MANAGER::EMPLOYEE_ID => $uv[Users::EMPLOYEE_ID],
                            EMPLOYEE_REPORTING_MANAGER::STATUS => ACTIVE_STATUS,
                            EMPLOYEE_REPORTING_MANAGER::CLIENT_ID => $_SESSION[CLIENT_ID]
                        ]);
                        if (($k == EMPLOYEE) && (count($getEmpUnderManeger) == 0)) {
                            
                        } elseif (($uv[Users::EMPLOYEE_ID] != 0) && ($uv[Users::EMPLOYEE_ID] != $_SESSION[EMPLOYEE_ID])) {
                            $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                            ], [
                                NEW_MESSAGE_LOG::NEW_MSG => 1,
                                NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                                NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                                NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                                NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $uv[Users::ID]
                            ]);
                            $new = '';
                            if (count($getNewMsg)>0) {
                                if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $uv[Users::ID])) {
                                    $new = '&nbsp;<span id="new_msg_alert_'.$uv[Users::ID].'" class="blinking badge badge-primary" style="position: absolute;">'.count($getNewMsg).'</span>';
                                }
                            }
                            $user_list .= '<ul class="list-group list-group-flush" id="chat_user_list_'. $uv[Users::ID] .'">';
                            $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">'.$uv[Users::NAME].$new.'</li>';
                            $user_list .= '</ul>';
                        }
                    }
                }
                $user_list .= '</li>';
                break;
            case 13:
                $user_list .= '<li class="list-group-item" id="chat_usertype_items_'.$k.'"><strong>'.$v.'</strong>';
                $getUsers = getData(Table::USERS, [
                    Users::ID,
                    Users::NAME,
                    Users::EMPLOYEE_ID
                ], [
                    Users::CLIENT_ID => $_SESSION[CLIENT_ID],
                    USERS::USER_TYPE => $k,
                    Users::ACTIVE => 1,
                    Users::STATUS => ACTIVE_STATUS
                ]);
                if (count($getUsers)>0) {
                    if ($k != EMPLOYEE) {
                        foreach ($getUsers as $uk => $uv) {
                            if (($uv[Users::EMPLOYEE_ID] != 0)) {
                                if ($k == MANAGER) {
                                    $getEmpUnderManeger = getData(Table::EMPLOYEE_REPORTING_MANAGER, [
                                        EMPLOYEE_REPORTING_MANAGER::ID
                                    ], [
                                        EMPLOYEE_REPORTING_MANAGER::REPORTING_MANAGER_USER_ID => $uv[Users::ID],
                                        EMPLOYEE_REPORTING_MANAGER::EMPLOYEE_ID => $_SESSION[EMPLOYEE_ID],
                                        EMPLOYEE_REPORTING_MANAGER::STATUS => ACTIVE_STATUS,
                                        EMPLOYEE_REPORTING_MANAGER::CLIENT_ID => $_SESSION[CLIENT_ID]
                                    ]);
                                    if (count($getEmpUnderManeger)>0) {
                                        $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                                            NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                                            NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                                        ], [
                                            NEW_MESSAGE_LOG::NEW_MSG => 1,
                                            NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                                            NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                                            NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                                            NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $uv[Users::ID]
                                        ]);
                                        $new = '';
                                        if (count($getNewMsg)>0) {
                                            if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $uv[Users::ID])) {
                                                $new = '&nbsp;<span id="new_msg_alert_'.$uv[Users::ID].'" class="blinking badge badge-primary" style="position: absolute;">'.count($getNewMsg).'</span>';
                                            }
                                        }
                                        $user_list .= '<ul class="list-group list-group-flush" id="chat_user_list_'. $uv[Users::ID] .'">';
                                        $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">'.$uv[Users::NAME].$new.'</li>';
                                        $user_list .= '</ul>';
                                    }
                                } else {
                                    $getNewMsg = getData(Table::NEW_MESSAGE_LOG, [
                                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID,
                                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID
                                    ], [
                                        NEW_MESSAGE_LOG::NEW_MSG => 1,
                                        NEW_MESSAGE_LOG::CLIENT_ID => $_SESSION[CLIENT_ID],
                                        NEW_MESSAGE_LOG::STATUS => ACTIVE_STATUS,
                                        NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID => $_SESSION[RID],
                                        NEW_MESSAGE_LOG::MSG_SENDER_USER_ID => $uv[Users::ID]
                                    ]);
                                    $new = '';
                                    if (count($getNewMsg)>0) {
                                        if (($getNewMsg[0][NEW_MESSAGE_LOG::MSG_RECEIVER_USER_ID] == $_SESSION[RID]) && ($getNewMsg[0][NEW_MESSAGE_LOG::MSG_SENDER_USER_ID] == $uv[Users::ID])) {
                                            $new = '&nbsp;<span id="new_msg_alert_'.$uv[Users::ID].'" class="blinking badge badge-primary" style="position: absolute;">'.count($getNewMsg).'</span>';
                                        }
                                    }
                                    $user_list .= '<ul class="list-group list-group-flush" id="chat_user_list_'. $uv[Users::ID] .'">';
                                    $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">'.$uv[Users::NAME].$new.'</li>';
                                    $user_list .= '</ul>';
                                }
                            }
                        }
                    }
                }
                $user_list .= '</li>';
                break;
        }
        
    }
    $user_list .= '</ul>';
        $response['error'] = false;
        $response['user_list'] = $user_list;
        sendRes();
        break;
    case 'ADD_DOMESTIC_CLIENT':
        $business_phone   = (isset($ajax_form_data['ph'])) ? altRealEscape($ajax_form_data['ph']) : "";
        $current_status   = (isset($ajax_form_data['st'])) ? $ajax_form_data['st'] : "";
        $business_details = (isset($ajax_form_data['dt'])) ? altRealEscape($ajax_form_data['dt']) : "";

        if (($business_phone == "") || ($current_status == "") || ($business_details == "")) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }

        $getDCdetails = getData(Table::DOMESTIC_CLIENTS_DATA, [
            DOMESTIC_CLIENTS_DATA::ID,
            DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS,
            DOMESTIC_CLIENTS_DATA::STATUS
        ], [
            DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO => $business_phone,
            DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID],
            DOMESTIC_CLIENTS_DATA::ACTIVE => 1
        ]);

        $response['exists'] = false;
        if (count($getDCdetails)>0) {
            $getClientHistory = getData(Table::DOMESTIC_CLIENTS_ACTIONS, [
                DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID,
                DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE,
                DOMESTIC_CLIENTS_ACTIONS::INFOTXT,
                DOMESTIC_CLIENTS_ACTIONS::ID,
            ], [
                DOMESTIC_CLIENTS_ACTIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                DOMESTIC_CLIENTS_ACTIONS::DC_ID => $getDCdetails[0][DOMESTIC_CLIENTS_DATA::ID]
            ], [], [], [DOMESTIC_CLIENTS_ACTIONS::ID], "DESC");
            $client_actions = $client_action_txt = '';
            if (count($getClientHistory)>0) {
                $client_tr = '';
                foreach ($getClientHistory as $k => $v) {
                    $pr_st = ($v[DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS] != 0) ? DOMESTIC_CLIENTS_STATUSES[$v[DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS]] : EMPTY_VALUE;
                    $cr_st = ($v[DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS] != 0) ? DOMESTIC_CLIENTS_STATUSES[$v[DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS]] : EMPTY_VALUE;
                    $date = getFormattedDateTime($v[DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE], LONG_DATE_FORMAT);
                    $getusername = getData(Table::USERS, [Users::NAME], [Users::ID => $v[DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID], Users::CLIENT_ID => $_SESSION[CLIENT_ID]]);
                    $ac_username = $getusername[0][Users::NAME];
                    if ($k == 0) {
                        $client_action_txt = 'Last Status Updated By: '.$ac_username;
                    }
                    
                    $client_tr .= '<tr id="client_status_'.$v[DOMESTIC_CLIENTS_ACTIONS::ID].'">
                        <td>'.$pr_st.'</td>
                        <td>'.$cr_st.'</td>
                        <td>'.$ac_username.'</td>
                        <td>'.$date.'</td>
                    </tr>';
                }
                $client_actions = '<div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center client_status_history_table" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase;">
                                <th>Previous</th>
                                <th>Changed</th>
                                <th>User</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">'.$client_tr.'</tbody>
                    </table>
                </div>';
            }
            $response['client_action_txt'] = ($client_action_txt != "") ? '<span class="text-primary client_action_txt" onclick="ViewClientStatusHistory(\''.$client_actions.'\')">'.$client_action_txt.'</span>' : '';
            $response['error'] = false;
            $response['exists'] = true;
            $response['message'] = "Business Already Exists !";
            sendRes();
        }

        $setDCdata = setData(Table::DOMESTIC_CLIENTS_DATA, [
            DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO => $business_phone,
            DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID],
            DOMESTIC_CLIENTS_DATA::STATUS => $current_status,
            DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS => htmlentities($business_details),
            DOMESTIC_CLIENTS_DATA::CREATION_DATE => getToday(true),
            DOMESTIC_CLIENTS_DATA::ACTIVE => 1
        ]);

        if (!$setDCdata['res']) {
            logError("Unabled to save domestic clients data", $setDCdata['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }

        $DC_id = $setDCdata['id'];

        $setStatusRecord = setData(Table::DOMESTIC_CLIENTS_ACTIONS, [
            DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID => $_SESSION[RID],
            DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS => $current_status,
            DOMESTIC_CLIENTS_ACTIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE => getToday(true),
            DOMESTIC_CLIENTS_ACTIONS::DC_ID => $DC_id,
            DOMESTIC_CLIENTS_ACTIONS::INFOTXT => getInfoText(true)
        ]);
        if (!$setStatusRecord['res']) {
            logError("Unabled to save domestic clients status data for DC id: ".$DC_id, $setStatusRecord['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = 'Client Added Successfully';
        sendRes();
        break;
    case 'CHECK_DC_PHONE_NO':
        $business_phone   = (isset($ajax_form_data['ph'])) ? altRealEscape($ajax_form_data['ph']) : "";
        if (($business_phone == "")) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }

        $getDCdetails = getData(Table::DOMESTIC_CLIENTS_DATA, [
            DOMESTIC_CLIENTS_DATA::ID,
            DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS,
            DOMESTIC_CLIENTS_DATA::STATUS
        ], [
            DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO => $business_phone,
            DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID],
            DOMESTIC_CLIENTS_DATA::ACTIVE => 1
        ]);

        $response['exists'] = false;
        if (count($getDCdetails)>0) {
            $getClientHistory = getData(Table::DOMESTIC_CLIENTS_ACTIONS, [
                DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID,
                DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE,
                DOMESTIC_CLIENTS_ACTIONS::INFOTXT,
                DOMESTIC_CLIENTS_ACTIONS::ID,
            ], [
                DOMESTIC_CLIENTS_ACTIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                DOMESTIC_CLIENTS_ACTIONS::DC_ID => $getDCdetails[0][DOMESTIC_CLIENTS_DATA::ID]
            ], [], [], [DOMESTIC_CLIENTS_ACTIONS::ID], "DESC");
            $client_actions = $client_action_txt = '';
            $bdetails = cleanText(html_entity_decode($getDCdetails[0][DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS]));
            $bstatus = $getDCdetails[0][DOMESTIC_CLIENTS_DATA::STATUS];
            $b_id = $getDCdetails[0][DOMESTIC_CLIENTS_DATA::ID];
            if (count($getClientHistory)>0) {
                $client_tr = '';
                foreach ($getClientHistory as $k => $v) {
                    $pr_st = ($v[DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS] != 0) ? DOMESTIC_CLIENTS_STATUSES[$v[DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS]] : EMPTY_VALUE;
                    $cr_st = ($v[DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS] != 0) ? DOMESTIC_CLIENTS_STATUSES[$v[DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS]] : EMPTY_VALUE;
                    $date = getFormattedDateTime($v[DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE], LONG_DATE_FORMAT);
                    $getusername = getData(Table::USERS, [Users::NAME], [Users::ID => $v[DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID], Users::CLIENT_ID => $_SESSION[CLIENT_ID]]);
                    $ac_username = $getusername[0][Users::NAME];
                    if ($k == 0) {
                        $client_action_txt = '<b class="text-secondary">Last Status Updated By: </b>'.$ac_username;
                    }
                    
                    $client_tr .= '<tr id="client_status_'.$v[DOMESTIC_CLIENTS_ACTIONS::ID].'">
                        <td>'.$pr_st.'</td>
                        <td>'.$cr_st.'</td>
                        <td>'.$ac_username.'</td>
                        <td>'.$date.'</td>
                    </tr>';
                }
                $client_actions =<<<HTML
<div class="table-responsive">
    <table class="table table-sm table-striped table-hover text-center client_status_history_table" style="font-size:14px;">
        <thead class="text-center table-warning">
            <tr style="text-transform: uppercase;">
                <th>Previous</th>
                <th>Changed</th>
                <th>User</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody class="text-center">$client_tr</tbody>
    </table>
</div>

HTML;
            }
            $response['client_action_txt'] = ($client_action_txt != "") ? '<span class="text-primary client_action_txt cursor-pointer font-weight-bold" style="text-decoration:underline;" onclick="ViewClientStatusHistory()">'.$client_action_txt.'</span>' : '';
            $response['error'] = false;
            $response['exists'] = true;
            $response['bdetails'] = $bdetails;
            $response['bstatus'] = $bstatus;
            $response['b_id'] = $b_id;
            $response['message'] = "Business Already Exists !";
            $response['client_actions'] = $client_actions;
            sendRes();
        }
        $response['error'] = false;
        sendRes();
        break;
    case 'UPDATE_DOMESTIC_CLIENT':
        // rip($ajax_form_data);
        $business_phone   = (isset($ajax_form_data['ph'])) ? altRealEscape($ajax_form_data['ph']) : "";
        $current_status   = (isset($ajax_form_data['st'])) ? $ajax_form_data['st'] : "";
        $business_details = (isset($ajax_form_data['dt'])) ? altRealEscape($ajax_form_data['dt']) : "";
        $dc_id            = (isset($ajax_form_data['id'])) ? altRealEscape($ajax_form_data['id']) : "";

        if (($business_phone == "") || ($current_status == "") || ($business_details == "") || ($dc_id == "")) {
            $response['message'] = EMPTY_FIELD_ALERT;
            sendRes();
        }
        $getPrStatus = getData(Table::DOMESTIC_CLIENTS_DATA, [
            DOMESTIC_CLIENTS_DATA::STATUS
        ], [
            DOMESTIC_CLIENTS_DATA::ID => $dc_id,
            DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID]
        ]);
        $update = updateData(Table::DOMESTIC_CLIENTS_DATA, [
            DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS => htmlentities($business_details),
            DOMESTIC_CLIENTS_DATA::STATUS => $current_status
        ], [
            DOMESTIC_CLIENTS_DATA::ID => $dc_id,
            DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID]
        ]);
        if (!$update['res']) {
            logError("Unabled to update DC Data, DC id: ".$dc_id, $update['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $setStatusRecord = setData(Table::DOMESTIC_CLIENTS_ACTIONS, [
            DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID => $_SESSION[RID],
            DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS => $current_status,
            DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS => $getPrStatus[0][DOMESTIC_CLIENTS_DATA::STATUS],
            DOMESTIC_CLIENTS_ACTIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
            DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE => getToday(true),
            DOMESTIC_CLIENTS_ACTIONS::DC_ID => $dc_id,
            DOMESTIC_CLIENTS_ACTIONS::INFOTXT => getInfoText(true)
        ]);
        if (!$setStatusRecord['res']) {
            logError("Unabled to save domestic clients status data for DC id: ".$dc_id, $setStatusRecord['error']);
            $response['message'] = ERROR_1;
            sendRes();
        }
        $response['error'] = false;
        $response['message'] = 'Client Updated Successfully';
        sendRes();
        break;
    default:
        $response['message'] = "Invalid Request";
        sendRes();
        break;
}
?>