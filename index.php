<?php 
include 'config.php';
// include ('main/pages/pause.html');
// printContent();
// hakai();
// exit();
switch ($action) {
    case '':
    case 'home':
    case 'dashboard':
        if (isUserLoggedin()) {
            include('main/pages/dashboard.php');
        } else {
            // include ('main/pages/index.php');
            header('Location: '.HOST_URL.'login');
            exit();
        }
        break;
    case 'login':
        if (isUserLoggedin()) {
            header('location:' . HOST_URL);
        } else {
            include ('main/auth/login.php');
        }
        // printContent();
        // exit();
        break;
    case 'cx':
        include('ajax/ajax_handler.php');
        exit();
        break;
    case 'logout':
        include('main/auth/logout.php');
        exit();
        break;
    case 'setting':
    case 'employee-setting':
    case 'sadmin-setting':
        checkSession();
        include('main/pages/settings.php');
        break;
    case 'employees':
    case 'sadmin-employees':
        checkSession();
    include('main/pages/employees.php');
        break;
    case 'add-employee':
    case 'sadmin-add-employee':
        checkSession();
    include('main/pages/add_employee.php');
        break;
    case 'designations':
    case 'sadmin-designations':
        checkSession();
    include('main/pages/designations.php');
        break;
    case 'departments':
    case 'sadmin-departments':
        checkSession();
    include('main/pages/departments.php');
        break;
    case 'manage-leaves':
    case 'sadmin-manage-leaves':
    case 'manager-manage-leaves':
        checkSession();
        include('main/pages/applied_leaves.php');
        break;
    case 'manager-view-leaves':
    case 'admin-view-leaves':
        checkSession();
        include('main/pages/admin_manager_view_leave.php');
        break;
    case 'employee-leaves':
    case 'manager-leaves':
    case 'admin-apply-leave':
        checkSession();
    include('main/pages/leaves.php');
        break;
    case 'employee-view-leaves':
        checkSession();
    include('main/pages/applied_leaves.php');
        break;
    case 'company-details':
    case 'employee-company-details':
        checkSession();
        include('main/pages/company_details.php');
        break;
    case 'notices':
    case 'sadmin-notices':
    case 'employee-notices':
    case 'manager-notices':
        checkSession();
        include('main/pages/notices.php');
        break;
    case 'holidays':
    case 'sadmin-holidays':
        checkSession();
        include('main/pages/holidays.php');
        break;
    case 'payroll':
    case 'sadmin-payroll':
    case 'employee-payslip':
    case 'manager-payslip':
        checkSession();
        include('main/pages/payroll.php');
        break;
    case 'policies':
    case 'sadmin-policies':
        checkSession();
        include('main/pages/policies.php');
        break;
    case 'profile':
    case 'employee-profile':
    case 'sadmin-profile':
    case 'manager-profile':
        checkSession();
        include('main/pages/profile.php');
        break;
    case 'generate-invoice':
    case 'sadmin-generate-invoice':
        checkSession();
        include('main/pages/generate_invoice.php');
        break;
    case 'old-invoice':
    case 'sadmin-old-invoice':
        checkSession();
        include('main/pages/old_invoice.php');
        break;
    case 'generated-invoice':
        checkSession();
        include ('main/pages/invoice/index.php');
        printContent();
        hakai();
        exit();
        break;
    case 'admin-manage-attendance':
    case 'sadmin-manage-attendance':
        checkSession();
        include ('main/pages/manage_attendance.php');
        break;
    case 'admin-datewise-attendance':
    case 'sadmin-datewise-attendance':
        checkSession();
        include ('main/pages/datewise_attendance.php');
        break;
    case 'admin-chat':
    case 'sadmin-chat':
    case 'employee-chat':
    case 'manager-chat':
    case 'chat':
        checkSession();
        include ('main/pages/chat.php');
        break;
    case 'mod-domestic-clients':
        checkSession();
        include ('main/pages/domestic_clients_mod.php');
        break;
    case 'domestic-client-list':
        checkSession();
        include ('main/pages/dc_list.php');
        break;
    case '404':
    default:
        include ('main/pages/not_found.php');
        printContent();
        hakai();
        exit();
        break;
}
$exclude_header = ['404','admin-area', 'login', 'pre_login'];
$exclude_footers = ['404','admin-area', 'login', 'pre_login'];
if (!in_array($action, $exclude_header)) {
    if (isUserLoggedIn()) {
        include('layouts/admin_header.php');
    } else {
        include('layouts/header.php');
    }
}
printContent();
if (!in_array($action, $exclude_footers)) {
    if (isUserLoggedIn()) {
        include('layouts/admin_footer.php');
    } else {
        include('layouts/footer.php');
    }
}
hakai();

?>