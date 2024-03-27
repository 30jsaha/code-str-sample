<?php
$exclude_sidebar = ['login'];
//Admin sidebar arrays
$admin_dashboard = ['home', '', 'dashboard'];
$admin_account = ['profile','setting', 'company-details'];
$employee_management = ['employees', 'add-employee', 'designations', 'departments'];
$admin_dept = ['notices', 'holidays', 'payroll', 'policies', 'manage-leaves', 'admin-apply-leave', 'admin-view-leaves', 'admin-manage-attendance', 'admin-datewise-attendance'];
$admin_invoice = ['generate-invoice', 'old-invoice'];
$admin_chat = ['admin-chat'];
//Sadmin sidebar arrays
$sadmin_dashboard = ['home', '', 'dashboard'];
$sadmin_account = ['sadmin-profile','sadmin-setting', 'sadmin-company-details'];
$sadmin_employee_management = ['sadmin-employees', 'sadmin-add-employee', 'sadmin-designations', 'sadmin-departments'];
$sadmin_dept = ['sadmin-notices', 'sadmin-holidays', 'sadmin-payroll', 'sadmin-policies', 'sadmin-manage-leaves', 'sadmin-manage-attendance', 'sadmin-datewise-attendance'];
$sadmin_invoice = ['sadmin-generate-invoice', 'sadmin-old-invoice'];
$sadmin_chat = ['sadmin-chat'];
//Manager sidebar arrays
$manager_dashboard = ['home', '', 'dashboard'];
$manager_account = ['manager-profile','manager-setting', 'manager-company-details'];
$manager_administrative = ['manager-notices', 'manager-leaves', 'manager-view-leaves', 'manager-payslip'];
$manager_manage_leaves = ['manager-manage-leaves'];
$manager_chat = ['manager-chat'];
//Employees sidebar arrays
$employee_dashboard = ['home', '', 'dashboard'];
$employee_account = ['employee-profile','employee-setting', 'employee-company-details'];
$employee_administrative = ['employee-notices', 'employee-leaves', 'employee-view-leaves', 'employee-payslip'];
$employee_chat = ['employee-chat'];


$domestic_clients = [];
$getDeptId = getData(Table::EMPLOYEE_DETAILS, [
    EMPLOYEE_DETAILS::DEPARTMENT_ID
], [
    EMPLOYEE_DETAILS::ID => $_SESSION[EMPLOYEE_ID],
    EMPLOYEE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID]
]);
$dept_id = (count($getDeptId)>0) ? $getDeptId[0][EMPLOYEE_DETAILS::DEPARTMENT_ID] : 0;

if ((($dept_id == 0) || ($dept_id != 5)) && ($_SESSION[USER_TYPE] != SADMIN)) {
    $domestic_clients = [];
} else {
    $domestic_clients = ['mod-domestic-clients', 'domestic-client-list'];
}
$domestic_clients = [];
// rip($domestic_clients);
// echo '<br> dept_id: '.$dept_id;
// exit;
$admin_sidebar_menus = array_merge($admin_dashboard,$employee_management,$admin_account,$admin_dept,$admin_invoice, $admin_chat);
$employee_sidebar_menus = array_merge($employee_dashboard, $employee_account, $employee_administrative, $domestic_clients);
$manager_sidebar_menus = array_merge($manager_dashboard, $manager_account, $manager_administrative,$manager_manage_leaves, $domestic_clients);
$sadmin_sidebar_menus = array_merge($sadmin_dashboard,$sadmin_account,$sadmin_employee_management,$sadmin_dept,$sadmin_invoice, $sadmin_chat, $domestic_clients);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Jyotirmoy Saha">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=WEBSITE_TITLE?></title>
    <?php include(BASE_DIR . 'includes/admin_url_to_css.php'); ?>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar  -->
        <?php if (!in_array($action, $exclude_sidebar)) : ?>
            <nav id="sidebar">
            <?php if((is_mobile()) || (is_tablet())): ?>
                <div id="dismiss">
                    <i class="fas fa-arrow-left"></i>
                </div>
            <?php endif; ?>
                <div class="sidebar-header text-center">
                    <img src="<?=COMPANY_LOGO_PATH;?>" class="img-fluid" style="width: 50%;"/>
                    <h3 style="font-size: 1.3rem !important; font-weight: 600  !important; margin-top: 10px;"><?=COMPANY_NAME?> CRM</h3>
                </div>

                <ul class="list-unstyled components">
                    <p class="text-center font-weight-bold"><?=USERS[$_SESSION[USER_TYPE]]?> Account</p>
                    <!-- <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Home</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="#">Home 1</a>
                        </li>
                        <li>
                            <a href="#">Home 2</a>
                        </li>
                        <li>
                            <a href="#">Home 3</a>
                        </li>
                    </ul>
                </li> -->
                    <!-- <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Pages</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#">Page 1</a>
                        </li>
                        <li>
                            <a href="#">Page 2</a>
                        </li>
                        <li>
                            <a href="#">Page 3</a>
                        </li>
                    </ul>
                </li> -->
                <?php 
                // Admin Sidebar Menu Start
                if(in_array($action, $admin_sidebar_menus)): ?>
                    <?php if (in_array($action, $admin_account)) : ?>
                        <!-- home and profile start -->
                        <!-- <li class="<?php if (($action == 'dashboard') || ($action == 'home') || ($action == '')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>dashboard/">Dashboard</a>
                        </li> -->
                        <li class="<?php if ($action == 'profile') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>profile/">Profile</a>
                        </li>
                        <!-- <li class="<?php ## if (($action == 'setting')) : ?>active<?php ## endif; ?>">
                            <a class="fload" href="setting/">Settings</a>
                        </li> -->
                    <?php endif; ?>
                    <?php if(in_array($action, $employee_management)): ?>
                        <li class="<?php if ($action == 'employees') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employees/">Employees</a>
                        </li>
                        <li class="<?php if (($action == 'add-employee')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>add-employee/">Add New Employee</a>
                        </li>
                        <li class="<?php if ($action == 'departments') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>departments/">Departments</a>
                        </li>
                        <li class="<?php if ($action == 'designations') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>designations/">Designations</a>
                        </li>
                    <?php endif; ?>
                    <?php if(in_array($action, $admin_dept)): ?>
                        <!-- <li class="<?php ##if (($action == 'admin-manage-attendance')) : ?>active<?php ## endif; ?>">
                            <a class="fload" href="admin-manage-attendance/">Attendance</a>
                        </li> -->
                        <li class="<?php if (($action == 'admin-manage-attendance') || ($action == 'admin-datewise-attendance')) : ?>active<?php endif; ?>">
                            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle" style="<?php if (($action == 'admin-manage-attendance') || ($action == 'admin-datewise-attendance')) : ?> <?php else: ?> background: none; <?php endif; ?>">Attendance</a>
                            <ul class="list-unstyled collapse show" id="pageSubmenu">
                                <li class="<?php if (($action == 'admin-manage-attendance')) : ?>active<?php endif; ?>">
                                    <a href="<?= HOST_URL ?>admin-manage-attendance/">Worker wise</a>
                                </li>
                                <li class="<?php if (($action == 'admin-datewise-attendance')) : ?>active<?php endif; ?>">
                                    <a href="<?= HOST_URL ?>admin-datewise-attendance/">Date wise</a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php if (($action == 'payroll')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>payroll/">Payroll</a>
                        </li>
                        <li class="<?php if ($action == 'manage-leaves') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>manage-leaves/">Manage Leaves</a>
                        </li>
                        <li class="<?php if ($action == 'admin-apply-leave') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>admin-apply-leave/">Apply Leave</a>
                        </li>
                        <li class="<?php if ($action == 'admin-view-leaves') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>admin-view-leaves/">Applied Leaves</a>
                        </li>
                        <li class="<?php if ($action == 'notices') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>notices/">Notices</a>
                        </li>
                        <li class="<?php if (($action == 'holidays')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>holidays/">Holidays</a>
                        </li>
                        <!-- <li class="<?php ##if (($action == 'policies')) : ?>active<?php ##endif; ?>">
                            <a class="fload" href="<?php ## echo HOST_URL;?>policies/">Policies</a>
                        </li> -->
                    <?php endif; ?>
                    <?php if(in_array($action, $admin_invoice)): ?>
                        <li class="<?php if (($action == 'generate-invoice')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>generate-invoice/">Generate Invoice</a>
                        </li>
                        <li class="<?php if (($action == 'old-invoice')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>old-invoice/">Old Invoices</a>
                        </li>
                    <?php endif; ?>
                <?php 
                endif;
                // Admin Sidebar Menu End

                // Super Admin Sidebar Menu Start
                if(in_array($action, $sadmin_sidebar_menus)): ?>
                    <?php if (in_array($action, $sadmin_account)) : ?>
                        <!-- home and profile start -->
                        <!-- <li class="<?php if (($action == 'dashboard') || ($action == 'home') || ($action == '')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>dashboard/">Dashboard</a>
                        </li> -->
                        <li class="<?php if ($action == 'sadmin-profile') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-profile/">Profile</a>
                        </li>
                        <!-- <li class="<?php ## if (($action == 'setting')) : ?>active<?php ## endif; ?>">
                            <a class="fload" href="setting/">Settings</a>
                        </li> -->
                    <?php endif; ?>
                    <?php if(in_array($action, $sadmin_employee_management)): ?>
                        <li class="<?php if ($action == 'sadmin-employees') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-employees/">Employees</a>
                        </li>
                        <li class="<?php if (($action == 'sadmin-add-employee')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-add-employee/">Add New Employee</a>
                        </li>
                        <li class="<?php if ($action == 'sadmin-departments') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-departments/">Departments</a>
                        </li>
                        <li class="<?php if ($action == 'sadmin-designations') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-designations/">Designations</a>
                        </li>
                    <?php endif; ?>
                    <?php if(in_array($action, $sadmin_dept)): ?>
                        <!-- <li class="<?php if (($action == 'sadmin-manage-attendance')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-manage-attendance/">Attendance</a>
                        </li> -->
                        <li class="<?php if (($action == 'sadmin-manage-attendance') || ($action == 'sadmin-datewise-attendance')) : ?>active<?php endif; ?>">
                            <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle" style="<?php if (($action == 'sadmin-manage-attendance') || ($action == 'sadmin-datewise-attendance')) : ?> <?php else: ?> background: none; <?php endif; ?>">Attendance</a>
                            <ul class="list-unstyled collapse show" id="pageSubmenu">
                                <li class="<?php if (($action == 'sadmin-manage-attendance')) : ?>active<?php endif; ?>">
                                    <a href="<?= HOST_URL ?>sadmin-manage-attendance/">Worker wise</a>
                                </li>
                                <li class="<?php if (($action == 'sadmin-datewise-attendance')) : ?>active<?php endif; ?>">
                                    <a href="<?= HOST_URL ?>sadmin-datewise-attendance/">Date wise</a>
                                </li>
                            </ul>
                        </li>
                        <li class="<?php if (($action == 'sadmin-payroll')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-payroll/">Payroll</a>
                        </li>
                        <li class="<?php if ($action == 'sadmin-manage-leaves') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-manage-leaves/">Manage Leaves</a>
                        </li>
                        <li class="<?php if ($action == 'sadmin-notices') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-notices/">Notices</a>
                        </li>
                        <li class="<?php if (($action == 'sadmin-holidays')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-holidays/">Holidays</a>
                        </li>
                        <!-- <li class="<?php ##if (($action == 'policies')) : ?>active<?php ##endif; ?>">
                            <a class="fload" href="<?php ## echo HOST_URL;?>policies/">Policies</a>
                        </li> -->
                    <?php endif; ?>
                    <?php if(in_array($action, $sadmin_invoice)): ?>
                        <li class="<?php if (($action == 'sadmin-generate-invoice')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-generate-invoice/">Generate Invoice</a>
                        </li>
                        <li class="<?php if (($action == 'sadmin-old-invoice')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>sadmin-old-invoice/">Old Invoices</a>
                        </li>
                    <?php endif; ?>
                <?php 
                // Super Admin Sidebar Menu End
                endif;
                // Employee Sidebar Menu Start
                if(in_array($action, $employee_sidebar_menus)): 
                ?>
                    <?php if (in_array($action, $employee_account)) : ?>
                        <li class="<?php if ($action == 'employee-profile') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employee-profile/">Profile</a>
                        </li>
                        <!-- <li class="<?php ## if (($action == 'employee-setting')) : ?>active<?php ##endif; ?>">
                            <a class="fload" href="employee-setting/">Settings</a>
                        </li> -->
                    <?php endif; ?>
                    <?php if (in_array($action, $employee_administrative)) : ?>
                        <li class="<?php if (($action == 'employee-notices')) : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employee-notices/">Notices</a>
                        </li>
                        <li class="<?php if ($action == 'employee-payslip') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employee-payslip/">Pay Slips</a>
                        </li>
                        <li class="<?php if ($action == 'employee-leaves') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employee-leaves/">Apply Leave</a>
                        </li>
                        <li class="<?php if ($action == 'employee-view-leaves') : ?>active<?php endif; ?>">
                            <a class="fload" href="<?= HOST_URL ?>employee-view-leaves/">Applied Leaves</a>
                        </li>
                    <?php endif; ?>
                <?php
                // Employee Sidebar Menu End
                endif;
                // Manager Sidebar Menu Start
                if(in_array($action, $manager_sidebar_menus)): 
                    ?>
                        <?php if (in_array($action, $manager_account)) : ?>
                            <li class="<?php if ($action == 'manager-profile') : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-profile/">Profile</a>
                            </li>
                        <?php endif; ?>
                        <?php if (in_array($action, $manager_administrative)) : ?>
                            <li class="<?php if (($action == 'manager-notices')) : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-notices/">Notices</a>
                            </li>
                            <li class="<?php if ($action == 'manager-payslip') : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-payslip/">Pay Slips</a>
                            </li>
                            <li class="<?php if ($action == 'manager-leaves') : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-leaves/">Apply Leave</a>
                            </li>
                            <li class="<?php if ($action == 'manager-view-leaves') : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-view-leaves/">Applied Leaves</a>
                            </li>
                        <?php endif; ?>
                        <?php if (in_array($action, $manager_manage_leaves)) : ?>
                            <li class="<?php if ($action == 'manager-manage-leaves') : ?>active<?php endif; ?>">
                                <a class="fload" href="<?= HOST_URL ?>manager-manage-leaves/">Manage Employee Leaves</a>
                            </li>
                        <?php endif; ?>
                        
                    <?php
                    // Manager Sidebar Menu End
                    endif;
                ?>
                <?php 
                    switch ($_SESSION[USER_TYPE]) {
                        case MANAGER:
                        case EMPLOYEE:
                        case SADMIN:
                            if(count($domestic_clients)>0):
                                if(in_array($action, $domestic_clients)): 
                            ?>
                                <li class="<?php if (($action == 'mod-domestic-clients')) : ?>active<?php endif; ?>">
                                    <a class="fload" href="<?= HOST_URL ?>mod-domestic-clients/">Domestic Clients</a>
                                </li>
                            <?php 
                                if(($_SESSION[USER_TYPE] == MANAGER) || ($_SESSION[USER_TYPE] == SADMIN)):
                            ?>
                                <li class="<?php if (($action == 'domestic-client-list')) : ?>active<?php endif; ?>">
                                    <a class="fload" href="<?= HOST_URL ?>domestic-client-list/">List</a>
                                </li>
                            <?php
                                endif; 
                                endif; 
                            endif; 
                            break;
                        default:
                            break;
                    }
                    ?>
                
                </ul>

                <ul class="list-unstyled CTAs d-none">
                    <li>
                        <a href="<?=CDN_URL;?>docs/idea.pdf" target="_blank" class="download">Download Tutorial &NonBreakingSpace;<i class="far fa-file-pdf"></i></a>
                    </li>
                    <li>
                        <a href="tel:+91 9123456789" class="article">Make a call &NonBreakingSpace;<i class="fas fa-phone-alt"></i></a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
        <!-- Page Content  -->
        <div id="content">

            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse" class="btn btn-warning">
                        <i class="fas fa-align-left"></i>
                        <span></span>
                    </button>
                    <?php if((is_mobile()) || (is_tablet())): ?>
                        <span style="font-weight: bold; font-size: small;">&nbsp; <?php echo 'WELCOME ' . strtoupper(strtolower($_SESSION[USERNAME])); ?></span>
                    <?php endif; ?>
                    <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-align-justify"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <?php if((is_mobile()) || (is_tablet())): ?><?php else: ?>    
                        <span style="font-weight: bold;">&nbsp; <?php echo 'WELCOME ' . strtoupper(strtolower($_SESSION[USERNAME])); ?></span>
                    <?php endif; ?>
                        <ul class="nav navbar-nav ml-auto">

                            <?php
                            switch ($_SESSION[USER_TYPE]) {
                                case EMPLOYEE:
                            ?>
                                    <li class="nav-item <?php if (in_array($action,$employee_dashboard)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'dashboard/'; ?>">Dashboard</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$employee_account)): ?>active<?php endif; ?>">
                                        <a class="fload nav-link" href="<?= HOST_URL . 'employee-profile/'; ?>">Account</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$employee_administrative)): ?>active<?php endif; ?>">
                                        <a class="fload nav-link" href="<?= HOST_URL . 'employee-notices'; ?>">Administrative</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$employee_chat)): ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="fload nav-link" href="<?= HOST_URL . 'employee-chat'; ?>">Chat<span id="header_menu_chat_alert" class="blinking badge badge-primary" style="position: absolute;<?php if(NEW_CHAT_COUNT <= 0): ?> display:none;<?php endif; ?>"><?=NEW_CHAT_COUNT?></span></a>
                                    </li>
                                    <?php if(count($domestic_clients)>0): ?>
                                    <li class="nav-item <?php if(in_array($action,$domestic_clients)): ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="fload nav-link" href="<?= HOST_URL . 'mod-domestic-clients'; ?>">Domestic</a>
                                    </li>
                                    <?php endif; ?>
                                <?php
                                    break;
                                case MANAGER:
                            ?>
                                    <li class="nav-item <?php if (in_array($action,$manager_dashboard)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'dashboard/'; ?>">Dashboard</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$manager_account)): ?>active<?php endif; ?>">
                                        <a class="fload nav-link" href="<?= HOST_URL . 'manager-profile/'; ?>">Account</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$manager_administrative)): ?>active<?php endif; ?>">
                                        <a class="fload nav-link" href="<?= HOST_URL . 'manager-notices'; ?>">Administrative</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$manager_manage_leaves)): ?>active<?php endif; ?>">
                                        <a class="fload nav-link" href="<?= HOST_URL . 'manager-manage-leaves'; ?>">Manage Leaves</a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$manager_chat)): ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="fload nav-link" href="<?= HOST_URL . 'manager-chat'; ?>">Chat<span id="header_menu_chat_alert" class="blinking badge badge-primary" style="position: absolute;<?php if(NEW_CHAT_COUNT <= 0): ?> display:none;<?php endif; ?>"><?=NEW_CHAT_COUNT?></span></a>
                                    </li>
                                    <?php if(count($domestic_clients)>0): ?>
                                    <li class="nav-item <?php if(in_array($action,$domestic_clients)): ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="fload nav-link" href="<?= HOST_URL . 'mod-domestic-clients'; ?>">Domestic</a>
                                    </li>
                                    <?php endif; ?>
                                <?php
                                    break;
                                case ADMIN:
                                ?>
                                    <li class="nav-item <?php if (in_array($action,$admin_dashboard)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'dashboard/'; ?>">Dashboard</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action,$admin_account)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'profile/'; ?>">Account</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $employee_management)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'employees/'; ?>">Employees</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $admin_dept)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'admin-manage-attendance/'; ?>">Administrative</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $admin_invoice)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'generate-invoice/'; ?>">Invoice</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $admin_chat)) : ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="nav-link" href="<?= HOST_URL . 'admin-chat/'; ?>">Chat<span id="header_menu_chat_alert" class="blinking badge badge-primary" style="position: absolute;<?php if(NEW_CHAT_COUNT <= 0): ?> display:none;<?php endif; ?>"><?=NEW_CHAT_COUNT?></span></a>
                                    </li>
                                <?php
                                    break;
                                case SADMIN:
                                ?>
                                    <li class="nav-item <?php if (in_array($action,$sadmin_dashboard)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'dashboard/'; ?>">Dashboard</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action,$sadmin_account)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'sadmin-profile/'; ?>">Account</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $sadmin_employee_management)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'sadmin-employees/'; ?>">Employees</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $sadmin_dept)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'sadmin-manage-attendance/'; ?>">Administrative</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $sadmin_invoice)) : ?>active<?php endif; ?>">
                                        <a class="nav-link" href="<?= HOST_URL . 'generate-invoice/'; ?>">Invoice</a>
                                    </li>
                                    <li class="nav-item <?php if (in_array($action, $sadmin_chat)) : ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="nav-link" href="<?= HOST_URL . 'sadmin-chat/'; ?>">Chat<span id="header_menu_chat_alert" class="blinking badge badge-primary" style="position: absolute;<?php if(NEW_CHAT_COUNT <= 0): ?> display:none;<?php endif; ?>"><?=NEW_CHAT_COUNT?></span></a>
                                    </li>
                                    <li class="nav-item <?php if(in_array($action,$domestic_clients)): ?>active<?php endif; ?>">
                                        <a style="margin-right: 5px;" class="fload nav-link" href="<?= HOST_URL . 'mod-domestic-clients'; ?>">Domestic</a>
                                    </li>
                                <?php
                                    break;
                            }
                            ?>
                            <li class="nav-item">
                                <a class="fload nav-link text-danger" href="<?=HOST_URL . 'logout/'; ?>">Log Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>