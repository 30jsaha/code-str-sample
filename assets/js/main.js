$(document).ready(function() {
    // console.clear();
    //function for sticky navbar start
    if ($(window).width() > 992) {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 40) {
                $('#navbar_top').addClass("fixed-top");
                // add padding top to show content behind navbar
                $('body').css('padding-top', $('.navbar').outerHeight() + 'px');
            } else {
                $('#navbar_top').removeClass("fixed-top");
                // remove padding top from body
                $('body').css('padding-top', '0');
            }
        });
    }

    $(".card").addClass("shadow");
    //function for sticky navbar End

    //datatable js start
    // $('#example').DataTable();
    //datatable js End

    //function for Datepicker Start
    // $.fn.datepicker.defaults.format = "dd/mm/yyyy";
    // $('.datepicker').datepicker({});
    //function for Datepicker End

    // const getChatCount = () => {
    //     const $header_menu_chat_alert = $("#header_menu_chat_alert"),
    //         $dashboard_chat_count = $(".dashboard_chat_count"),
    //         $user_list_card_body = $(".user_list_card_body"),
    //         $chat_card_body = $("#chat_card_body");
    //     // $header_menu_chat_alert.hide();
    //     var previousChatCount = $header_menu_chat_alert.val();
    //     // $chat_card_body.animate({ scrollTop: chat_card_body.prop("scrollHeight")}, 1000);
    //     switch (PAGE_ACTION) {
    //         case 'admin-chat':
    //         case 'sadmin-chat':
    //         case 'employee-chat':
    //         case 'manager-chat':
    //         case 'chat':
    //             ajaxRequest(data = { ajax_action: "GET_USER_LIST_FOR_CHAT" }, (res) => {
    //                 if (!(res.error)) {
    //                     $user_list_card_body.html('');
    //                     $user_list_card_body.html(res.user_list);
    //                     if (CURRENT_CHAT_USER_ID != 0) {
    //                         $("#chat_user_items_" + CURRENT_CHAT_USER_ID).addClass("chat_user_selected");
    //                         getChatHistory(CURRENT_CHAT_USER_ID, false);
    //                     }
    //                 }
    //             });
    //             break;
    //     }
    //     ajaxRequest(data = { ajax_action: "GET_TOTAL_CHAT_COUNT" }, (res) => {
    //         if (!(res.error)) {
    //             if (res.new_chat_count > 0) {
    //                 $header_menu_chat_alert.html(res.new_chat_count);
    //                 $dashboard_chat_count.html(res.new_chat_count);
    //                 $header_menu_chat_alert.show();
    //                 if ((res.new_chat_count) > previousChatCount) {
    //                     $chat_card_body.animate({ scrollTop: $chat_card_body.prop("scrollHeight") }, 1000);
    //                 }
    //             } else {
    //                 $header_menu_chat_alert.hide();
    //             }
    //             clog("Chat Count: " + res.new_chat_count);
    //         }
    //     });
    // };
    if (PAGE_ACTION == "mod-domestic-clients") {
        let business_phone = $("#business_phone").val();
        if (!isInvalidValue(business_phone)) {
            checkClientByPhone();
        }
    }
    const getChatCount = () => {
        const $header_menu_chat_alert = $("#header_menu_chat_alert"),
            $dashboard_chat_count = $(".dashboard_chat_count"),
            $user_list_card_body = $(".user_list_card_body"),
            $chat_card_body = $("#chat_card_body");
        // $header_menu_chat_alert.hide();
        var previousChatCount = $header_menu_chat_alert.val();
        // $chat_card_body.animate({ scrollTop: chat_card_body.prop("scrollHeight")}, 1000);
        
        ajaxRequest(data = { ajax_action: "GET_TOTAL_CHAT_COUNT" }, (res) => {
            if (!(res.error)) {
                if (res.new_chat_count > 0) {
                    $header_menu_chat_alert.html(res.new_chat_count);
                    $dashboard_chat_count.html(res.new_chat_count);
                    $header_menu_chat_alert.show();
                    if ((res.new_chat_count) > previousChatCount) {
                        switch (PAGE_ACTION) {
                            case 'admin-chat':
                            case 'sadmin-chat':
                            case 'employee-chat':
                            case 'manager-chat':
                            case 'chat':
                                ajaxRequest(data = { ajax_action: "GET_USER_LIST_FOR_CHAT" }, (res) => {
                                    if (!(res.error)) {
                                        $user_list_card_body.html('');
                                        $user_list_card_body.html(res.user_list);
                                        if (($("#myInput").val()) != "") {
                                            JoysEye();
                                        }
                                        if (CURRENT_CHAT_USER_ID != 0) {
                                            $("#chat_user_items_" + CURRENT_CHAT_USER_ID).addClass("chat_user_selected");
                                            getChatHistory(CURRENT_CHAT_USER_ID, false);
                                            $chat_card_body.animate({ scrollTop: $chat_card_body.prop("scrollHeight") }, 1000);
                                        }
                                    }
                                });
                                break;
                        }
                    }
                } else {
                    $header_menu_chat_alert.hide();
                }
                clog("Chat Count: " + res.new_chat_count);
            }
        });
    };
    if (ISUSERLOGGEDIN == 1) {
        setInterval(() => {
            getChatCount();
        }, 3000);
    }
});
if (document.querySelector('#myInput')) {
    document.querySelector('#myInput').addEventListener('keyup', JoysEye);   
}
function LoginValidation() {
    const usid = $('#login_usid');
    const user_id = $('#login_user_id');
    const password = $('#login_pass');
    var usid_val = usid.val();
    var user_id_val = user_id.val();
    var pass_val = password.val();
    if (isInvalidValue(usid_val)) {
        pointInvalid(usid);
        toastAlert('Please Enter Your USID', 'error');
        return false;
    } else {
        usid.removeClass('error_input');
    }
    if (isNaN(usid_val)) {
        pointInvalid(usid);
        toastAlert('Please Enter A Valid USID', 'error');
        return false;
    } else {
        usid.removeClass('error_input');
    }
    if (isInvalidValue(user_id_val)) {
        pointInvalid(user_id);
        toastAlert('Please Enter Your User Id', 'error');
        return false;
    } else {
        user_id.removeClass('error_input');
    }
    if (isInvalidValue(pass_val)) {
        pointInvalid(password);
        toastAlert('Please Enter Your Password', 'error');
        return false;
    } else {
        password.removeClass('error_input');
    }

    const data = {
        ajax_action: 'ADMIN_LOGIN',
        usid_val,
        user_id_val,
        pass_val
    };
    ajaxRequest(data, function(response) {
        let res_msg = response.message;
        let res_error = response.error;
        if (res_error) {
            toastAlert(res_msg, 'error');
            return false;
        } else {
            toastAlert(res_msg, 'success');
            // console.log(response);
            // return;
            // window.location.reload();
            setInterval(() => {
                window.location.href = HOST_URL;
            }, 1000);
            return false;
        }
    });
}

function eventListner(event) {
    var keycode = event.which;
    if (keycode == 13) {
        $('#login_submit').click();
    }
}

const checkIfLoggedIn = (during = "N/A") => {
    ajaxRequest({ ajax_action: "ChkIflogged", during: during }, res => {
        if (res.kkout) {
            toastAlert(res.message, "warning");
            setInterval(() => {
                window.location.href = HOST_URL;
                return false;
            }, 1000);
        }
    });
};

// function for reset the account password
$("#reset_pass").on('click', () => {
    const $old_pass = $("#old_pass"),
        $new_pass = $("#reset_pass"),
        $cnew_pass = $("#cnew_pass"),
        $err = $("#err"),
        $err_sp = $err.find(".err_sp");

    var old_pass = $old_pass.val(),
        new_pass = $new_pass.val(),
        cnew_pass = $cnew_pass.val();
    $err_sp.val("");
    $err.hide();
    checkIfLoggedIn();
    if (isInvalidValue(old_pass)) {
        pointInvalid($old_pass);
        $err_sp.text("Please Enter the Old Password");
        $err.show();
        return false;
    }
    ajaxRequest({ ajax_action: "getOldPass", op: old_pass }, res => {
        if (res.error) {
            pointInvalid($old_pass);
            $err_sp.text(res.message);
            $err.show();
            return false;
        }
    });

});
const frgt_pass = () => {
    alert("Please Contact Your Administrator !");
};

$("#user_sign_in").on('click', () => {
    const $user_id = $('#user_id'),
        $user_pass = $("#user_pass"),
        $err_sp = $("#err_sp"),
        $signin_progress = $("#signin_progress"),
        $progress_bar = $signin_progress.find(".progress-bar");
    var user_id = $user_id.val(),
        user_pass = $user_pass.val(),
        signin_progress_val = 56;

    $err_sp.text("");
    $err_sp.hide();

    if (isInvalidValue(user_id)) {
        pointInvalid($user_id);
        $err_sp.text("Please Enter your Registered Email / Mobile Number");
        $err_sp.show();
        return false;
    } else {
        checkRtype();
        $user_id.removeClass("error_input");
        $err_sp.text("");
        $err_sp.hide();
    }
    if (isInvalidValue(user_pass)) {
        pointInvalid($user_pass);
        $err_sp.text("Please Enter your Password");
        $err_sp.show();
        return false;
    } else {
        $user_pass.removeClass("error_input");
        $err_sp.text("");
        $err_sp.hide();
    }
    const data = {
        ajax_action: "LOGIN",
        em: user_id,
        ep: user_pass,
        rt: login_rtype
    };
    $signin_progress.show();
    for (let i = 1; i <= signin_progress_val; i++) {
        $progress_bar.css("width", i + "%");
        $progress_bar.text("" + i + "%");
    }
    ajaxRequest(data, (res) => {
        err = res.error;
        msg = res.message;
        // return false;
        if (err) {
            $signin_progress.hide();
            $progress_bar.css("width", "1%");
            $progress_bar.text("1%");
            $err_sp.text(msg);
            $err_sp.show();
            return false;
        } else {
            // $signin_progress.show();
            for (let i = signin_progress_val; i <= 100; i++) {
                $progress_bar.css("width", i + "%");
                $progress_bar.text("" + i + "%");
                clog(i);
                if (i == 100) {
                    toastAlert(msg, "success");
                    $progress_bar.css("width", "100%");
                    $progress_bar.text("100%");
                    setInterval(() => {
                        window.location.href = HOST_URL;
                        return false;
                    }, 1500);
                    return false;
                }
            }
        }
    });
});

$('#user_id').on('keypress', () => {
    checkRtype();
});

const checkRtype = (act = "") => {
    const $user_id = $('input[name="user_id"]');
    let user_id = extractValue($user_id);
    rtype = isNaN(removeCountryCode(user_id)) ? 'e' : 'p';
    clog(rtype);
    const $err_sp = $("#err_sp");
    // const error_msg_p = error_div.find('p');
    switch (act) {
        case "fpass":
            const button = $('#sub_button');
            var error = '';
            if (!isEmail(user_id) && !isPhone(user_id)) {
                error = "Please enter a valid email or phone number.";
            }
            var isemail = (error == "") ? true : false;
            clog(isemail);
            if (!isemail) {
                error_msg_p.html(error);
                error_div.show();
                button.prop('disabled', true);
                button.prop('readonly', true);
                button.css('cursor', 'not-allowed');
            } else {
                error_msg_p.html(error);
                error_div.hide();
                button.prop('disabled', false);
                button.prop('readonly', false);
                button.css('cursor', 'pointer');
            }
            break;

        default:
            // let label = th.prev('label');
            // (rtype == 'p') ? login_rtype = 'm': login_rtype = 'Email';
            login_rtype = rtype;
            break;
    }
};
const removeCountryCode = (number) => {
    // return (number.replace(/^(?:\+?27|\+?91|0)?/, '')).trim();
    var value=number;
    // Remove all spaces
    var mobile = value.replace(/ /g,'');

    // If string starts with +, drop first 3 characters
    if(value.slice(0,1)=='+'){
        mobile = mobile.substring(3)
        }

    // If string starts with 0, drop first 4 characters
    if(value.slice(0,1)=='0'){
        mobile = mobile.substring(4)
        }

    // clog(mobile);
    return mobile;
};
const togglePassword = (th) => {
    const pass_input = th.closest('.input-group').find('input'),
        type = pass_input.attr('type') === 'password' ? 'text' : 'password',
        iele = (type === 'text') ? `<i class="fa fa-eye-slash" aria-hidden="true"></i>` : `<i class="fa fa-eye" aria-hidden="true"></i>`;
    pass_input.attr('type', type);
    // toggle the eye / eye slash icon
    // th.find('i').addClass('fa fa-eye-slash');
    th.html(iele);
};

// $("#designation_active").on("click", () => {
//     var desig_active = $("#designation_active");
//     if (desig_active.checked) {
//         toastAlert("Activated", "success");
//     } else {
//         toastAlert("Deactivated", "error");
//     }
// });

// const desig_active = document.getElementById('designation_active');

// desig_active.addEventListener('change', e => {
//     if (e.target.checked === true) {
//         // console.log("Checkbox is checked - boolean value: ", e.target.checked);
//         toastAlert("Activated :" + e.target.checked, "success");
//     }
//     if (e.target.checked === false) {
//         // console.log("Checkbox is not checked - boolean value: ", e.target.checked);
//         toastAlert("Deactivated : " + e.target.checked, "error");
//     }
// });

$("#designation_submit").on('click', () => {
    const $desig_name = $("#desig_name"),
        $desig_responcibilities = $("#desig_responcibilities"),
        $desig_exp = $("#desig_exp"),
        $designation_active = document.getElementById('designation_active'),
        loader = $("#desig_loader");
    var desig_name = $desig_name.val(),
        desig_exp = $desig_exp.val(),
        desig_responcibilities = $desig_responcibilities.val(),
        designation_active = true;
    $designation_active.addEventListener('change', e => {
        if (e.target.checked === true) {
            // console.log("Checkbox is checked - boolean value: ", e.target.checked);
            designation_active = e.target.checked;
            toastAlert("Set to Active :" + e.target.checked, "success");
        }
        if (e.target.checked === false) {
            // console.log("Checkbox is not checked - boolean value: ", e.target.checked);
            designation_active = e.target.checked;
            toastAlert("Set to Deactive : " + e.target.checked, "error");
        }
    });

    $desig_name.removeClass('error_input');
    $desig_responcibilities.removeClass('error_input');
    $desig_exp.removeClass('error_input');

    if (isInvalidValue(desig_name)) {
        pointInvalid($desig_name);
        toastAlert("Designation name cannot be left blank", "error");
        return false;
    }
    if (isInvalidValue(desig_responcibilities)) {
        desig_responcibilities = "";
    }
    if (isInvalidValue(desig_exp)) {
        desig_exp = "";
    }
    if (designation_active) {
        designation_active = 1;
    } else {
        designation_active = 0;
    }
    loader.css({ display: 'flex' });
    const data = {
        ajax_action: "ADD_DESIGNATION",
        dname: desig_name,
        dres: desig_responcibilities,
        dact: designation_active,
        dexp: desig_exp
    };
    clog(data);
    // return false;
    ajaxRequest(data, (res) => {
        err = res.error;
        msg = res.message;
        alert_color = (err) ? "error" : "success";
        loader.hide();
        toastAlert(msg, alert_color);
        if (err) {
            return false;
        }
        $desig_name.val("");
        $desig_responcibilities.val("");
        $desig_exp.val("");
        setInterval(() => {
            location.reload();
            return false;
        }, 1200);
    });
});


const changeNavigateBtn = ($action) => {
    var list_row, add_row,
        btn = ``,
        txt = "",
        $btn = $("#list_nav_btn"),
        $btn_name = $("#list_nav_btn").children(".btn").attr("name");
    switch ($action) {
        case "designation":
            add_row = $("#add_designation_row");
            list_row = $("#view_designation_row");
            break;
        case "payslip":
            add_row = $("#upload_payslip_row");
            list_row = $("#view_payslip_row");
            break;
        case "notice":
            add_row = $("#upload_notice_row");
            list_row = $("#view_notice_row");
            break;
    }
    // clog($btn_name);

    switch ($btn_name) {
        case "list":
            btn = `<button class="btn btn-primary" name="add" onclick="changeNavigateBtn('${$action}');">Add</button>`;
            txt = "Add";
            add_row.hide();
            list_row.show();
            break;
        case "add":
            btn = `<button class="btn btn-primary" name="list" onclick="changeNavigateBtn('${$action}');">View List</button>`;
            txt = "View List";
            add_row.show();
            list_row.hide();
            break;
    }
    $btn.find(".btn").remove();
    $btn.append(btn);
};

$("#emp_add_btn").on("click", () => {
    const $emp_name = $("#emp_name"),
        $emp_date_of_birth = $("#emp_date_of_birth"),
        $emp_mother_name = $("#emp_mother_name"),
        $emp_father_name = $("#emp_father_name"),
        $emp_mobile = $("#emp_mobile"),
        $emp_email = $("#emp_email"),
        $blood_group = $("#blood_group"),
        $emp_payroll = $("#emp_payroll"),
        $emp_date_of_join = $("#emp_date_of_join"),
        $emp_desig_id = $("#emp_desig_id"),
        $emp_remarks = $("#emp_remarks"),
        $emp_exp = $("#emp_exp"),
        loader = $("#emp_loader");

    const $emp_id = $("#emp_id"),
        $emp_department = $("#emp_department"),
        $emp_salary = $("#emp_salary"),
        $emp_webmail = $("#emp_webmail"),
        $emergeny_contact_name = $("#emergeny_contact_name"),
        $emergeny_contact_mobile = $("#emergeny_contact_mobile"),
        $current_address = $("#current_address"),
        $permanent_address = $("#permanent_address"),
        $emp_aadhaar = $("#emp_aadhaar"),
        $emp_pan = $("#emp_pan"),
        $emp_salary_ac_number = $("#emp_salary_ac_number"),
        $emp_salary_ac_ifsc = $("#emp_salary_ac_ifsc"),
        $emp_uan = $("#emp_uan"),
        $emp_esic_ip_number = $("#emp_esic_ip_number"),
        $employee_user_type = $("#employee_user_type"), //select box
        $user_password = $("#user_password"),
        $employee_report_manager = $("#employee_report_manager"),
        $employee_report_time = $("#employee_report_time");


    var emp_name = $emp_name.val(),
        emp_date_of_birth = $emp_date_of_birth.val(),
        emp_mother_name = $emp_mother_name.val(),
        emp_father_name = $emp_father_name.val(),
        emp_mobile = $emp_mobile.val(),
        emp_email = $emp_email.val(),
        blood_group = $blood_group.val(),
        emp_payroll = $emp_payroll.children("option:selected").val(),
        emp_date_of_join = $emp_date_of_join.val(),
        emp_desig_id = $emp_desig_id.children("option:selected").val(),
        emp_remarks = $emp_remarks.val(),
        emp_exp = $emp_exp.val();

    var emp_id = $emp_id.val(),
        emp_department = $emp_department.children("option:selected").val(),
        emp_salary = $emp_salary.val(),
        emp_webmail = $emp_webmail.val(),
        emergeny_contact_name = $emergeny_contact_name.val(),
        emergeny_contact_mobile = $emergeny_contact_mobile.val(),
        current_address = $current_address.val(),
        permanent_address = $permanent_address.val(),
        emp_aadhaar = $emp_aadhaar.val(),
        emp_pan = $emp_pan.val(),
        emp_salary_ac_number = $emp_salary_ac_number.val(),
        emp_salary_ac_ifsc = $emp_salary_ac_ifsc.val(),
        emp_uan = $emp_uan.val(),
        emp_esic_ip_number = $emp_esic_ip_number.val(),
        user_password = $user_password.val(),
        employee_user_type = $employee_user_type.children("option:selected").val(),
        employee_report_manager = $employee_report_manager.children("option:selected").val(),
        employee_report_time = $employee_report_time.children("option:selected").val();

    $emp_mobile.removeClass("error_input");
    if (isInvalidValue(emp_name)) {
        pointInvalid($emp_name);
        toastAlert("Employee name cannot be left blank", "error");
        return false;
    } else {
        $emp_name.removeClass("error_input");
    }
    if (isInvalidValue(emp_id)) {
        pointInvalid($emp_id);
        toastAlert("Employee ID cannot be left blank", "error");
        return false;
    } else {
        $emp_id.removeClass("error_input");
    }
    if (!(emp_desig_id)) {
        pointInvalid($emp_desig_id);
        toastAlert("Employee Designation cannot be left blank", "error");
        return false;
    } else {
        $emp_desig_id.removeClass("error_input");
    }
    if (!(emp_department)) {
        pointInvalid($emp_department);
        toastAlert("Employee Department cannot be left blank", "error");
        return false;
    } else {
        $emp_department.removeClass("error_input");
    }
    if (isInvalidValue(emp_mobile)) {
        pointInvalid($emp_mobile);
        toastAlert("Mobile Number cannot be left blank", "error");
        return false;
    } else {
        if (isNaN(emp_mobile)) {
            pointInvalid($emp_mobile);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emp_mobile.removeClass("error_input");
        }
        if ((emp_mobile.length) > 13) {
            pointInvalid($emp_mobile);
            clog(emp_mobile.length);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emp_mobile.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_email)) {
        pointInvalid($emp_email);
        toastAlert("Email cannot be left blank", "error");
        return false;
    } else {
        $emp_email.removeClass("error_input");
    }
    if (isInvalidValue(emergeny_contact_mobile)) {

    } else {
        if (isNaN(emergeny_contact_mobile)) {
            pointInvalid($emergeny_contact_mobile);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emergeny_contact_mobile.removeClass("error_input");
        }
        if ((emergeny_contact_mobile.length) > 13) {
            pointInvalid($emergeny_contact_mobile);
            clog(emergeny_contact_mobile.length);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emergeny_contact_mobile.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_aadhaar)) {

    } else {
        if (isNaN(emp_aadhaar)) {
            pointInvalid($emp_aadhaar);
            toastAlert("Enter a valid Aadhaar number", "error");
            return false;
        } else {
            $emp_aadhaar.removeClass("error_input");
        }
        if ((emp_aadhaar.length) > 13) {
            pointInvalid($emp_aadhaar);
            clog(emp_aadhaar.length);
            toastAlert("Enter a valid Aadhaar number", "error");
            return false;
        } else {
            $emp_aadhaar.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_salary_ac_number)) {

    } else {
        if (isNaN(emp_salary_ac_number)) {
            pointInvalid($emp_salary_ac_number);
            toastAlert("Enter a valid Account number", "error");
            return false;
        } else {
            $emp_salary_ac_number.removeClass("error_input");
        }
        if ((emp_salary_ac_number.length) > 13) {
            pointInvalid($emp_salary_ac_number);
            clog(emp_salary_ac_number.length);
            toastAlert("Enter a valid Account number", "error");
            return false;
        } else {
            $emp_salary_ac_number.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_uan)) {

    } else {
        if (isNaN(emp_uan)) {
            pointInvalid($emp_uan);
            toastAlert("Enter a valid UAN number", "error");
            return false;
        } else {
            $emp_uan.removeClass("error_input");
        }
        if ((emp_uan.length) > 13) {
            pointInvalid($emp_uan);
            clog(emp_uan.length);
            toastAlert("Enter a valid UAN number", "error");
            return false;
        } else {
            $emp_uan.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_esic_ip_number)) {

    } else {
        if (isNaN(emp_esic_ip_number)) {
            pointInvalid($emp_esic_ip_number);
            toastAlert("Enter a valid IP number", "error");
            return false;
        } else {
            $emp_esic_ip_number.removeClass("error_input");
        }
        if ((emp_esic_ip_number.length) > 13) {
            pointInvalid($emp_esic_ip_number);
            clog(emp_esic_ip_number.length);
            toastAlert("Enter a valid IP number", "error");
            return false;
        } else {
            $emp_esic_ip_number.removeClass("error_input");
        }
    }
    if (!(employee_user_type)) {
        pointInvalid($employee_user_type);
        toastAlert("Employee User Type Must be Selected", "error");
        return false;
    } else {
        $employee_user_type.removeClass("error_input");
    }
    if (!(employee_report_manager)) {
        pointInvalid($employee_report_manager);
        toastAlert("Employee Reporting Manager Must be Selected", "error");
        return false;
    } else {
        $employee_report_manager.removeClass("error_input");
    }
    if (!(employee_report_time)) {
        pointInvalid($employee_report_time);
        toastAlert("Employee Reporting Time Must be Selected", "error");
        return false;
    } else {
        $employee_report_time.removeClass("error_input");
    }
    if (isInvalidValue(user_password)) {
        pointInvalid($user_password);
        toastAlert("Password cannot be left blank", "error");
        return false;
    } else {
        $user_password.removeClass("error_input");
    }
    loader.show();
    const data = {
        ajax_action: "ADD_EMPLOYEE",
        enm: emp_name,
        edb: emp_date_of_birth,
        emn: emp_mother_name,
        efn: emp_father_name,
        emb: emp_mobile,
        eeml: emp_email,
        ebg: blood_group,
        eprl: emp_payroll,
        edtj: emp_date_of_join,
        ermk: emp_remarks,
        exp: emp_exp,
        edgn: emp_desig_id,
        emp_esic_ip_number,
        emp_uan,
        emp_salary_ac_ifsc,
        emp_salary_ac_number,
        emp_pan,
        emp_aadhaar,
        permanent_address,
        current_address,
        emergeny_contact_mobile,
        emergeny_contact_name,
        emp_webmail,
        emp_salary,
        emp_department,
        emp_id,
        utype: employee_user_type,
        upass: user_password,
        rpt_mngr: employee_report_manager,
        employee_report_time
    };
    clog(data);
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        if (err) {
            toastAlert(msg, 'error');
            loader.hide();
            return false;
        }
        toastAlert(msg);
        loader.hide();
        setInterval(() => {
            window.location.href = HOST_URL + 'add-employee';
            return false;
        }, 1500);
    });
});

//code for upload payslip start

$("#upload_payslip").on("click", () => {
    const $payslip_month = $("#payslip_month"),
        $payslip_employee = $("#payslip_employee"),
        $payslip_file = $("#payslip_file"),
        loader = $("#payroll_loader");
    var payslip_month = $payslip_month.children("option:selected").val(),
        payslip_employee = $payslip_employee.children("option:selected").val(),
        payslip_file = $payslip_file.val();

    $payslip_month.removeClass("error_input");
    $payslip_employee.removeClass("error_input");
    $payslip_file.removeClass("error_input");

    if (isInvalidValue(payslip_month)) {
        pointInvalid($payslip_month);
        toastAlert("Please Select Payslip Month", "error");
        return false;
    } else if (payslip_month == 0) {
        pointInvalid($payslip_month);
        toastAlert("Please Select Payslip Month", "error");
        return false;
    }
    if (isInvalidValue(payslip_employee)) {
        pointInvalid($payslip_employee);
        toastAlert("Please Select Employee", "error");
        return false;
    } else if (payslip_employee == 0) {
        pointInvalid($payslip_employee);
        toastAlert("Please Select Employee", "error");
        return false;
    }
    if (isInvalidValue(payslip_file)) {
        pointInvalid($payslip_file);
        toastAlert("Please Select Payslip file", "error");
        return false;
    } else {
        if (isEmpty($("#file_name_preview").find("span"))) {
            clog($("#file_name_preview").find("span").html());
            toastAlert("File is being uploaded. Please wait", "error");
            return false;
        }
        var allowedFiles = [".pdf"];
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(payslip_file.toLowerCase())) {
            var str = allowedFiles.join(', ');
            toastr['error']("Please upload files having extensions: " + str + " only.");
            return false;
        }
    }
    loader.show();
    let file = $payslip_file[0].files[0],
        ajax_request = 'UPLOAD_PAYSLIP',
        form = new FormData();
    form.append('ajax_action', ajax_request);
    form.append('media_file', file);
    form.append('emp_nm', payslip_employee);
    form.append('pay_mnth', payslip_month);

    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round(((evt.loaded / evt.total) * 100));
                    // $(".progress-bar").width(percentComplete + '%');
                    // $(".progress-bar").html(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        url: DEF_AJAX_URL,
        type: "POST",
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            // $('.media_progress').showLoader();
        },
        success: (data) => {
            // Hide the progress bar
            // $('.media_progress').hide();
            // $(".progress-bar").width('0%');
            // $(".progress-bar").html('0%');
            clog(data);
            loader.hide();
            var parseData = IS_CONTENT_TYPE_JSON ? data : JSON.parse(data);
            if (parseData.error) {
                //Some error occurd
                toastr.error(parseData.message);
                return false;
            }
            toastAlert(parseData.message);
            setInterval(() => {
                window.location.href = HOST_URL + 'payroll';
                return false;
            }, 1500);
        },
        error: (xhr, status, error) => {
            clog("AJAX Error");
            clog(xhr);
            clog(status);
            clog(error);
            $('.loader-overlay').hide();
            loader.hide();
            if (xhr.readyState == 0) {
                toastr.error("Opps! Could not connect to the server. Please try again.");
            } else if (xhr.readyState == 500) {
                toastr.error("Opps! Forbidden.");
            } else if (xhr.readyState == 404) {
                toastr.error("Not found Error.");
            }
        }
    });

});

//code for upload payslip end

$("#department_submit").on('click', () => {
    const $department_name = $("#department_name"),
        loader = $("#department_loader");
    var department_name = $department_name.val();

    loader.show();
    $department_name.removeClass("error_input");
    if (isInvalidValue(department_name)) {
        pointInvalid($department_name);
        toastAlert($department_name.attr('name') + " cannot be left blank", "error");
        loader.hide();
        return false;
    }
    loader.show();
    const data = { ajax_action: "ADD_DEPARTMENT", dnm: department_name };
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message,
            color = "success";
        if (err) {
            color = "error";
            toastAlert(msg, "error");
            loader.hide();
            return false;
        }
        toastAlert(msg, color);
        loader.hide();
        setInterval(() => {
            location.reload();
            return false;
        }, 1200);
    });
});

const changeEmployeeActiveStatus = (id) => {
    var modal = $("#emp_inactive_status_update");
    $("#emp_inactive_id").val(id);
    modal.modal('show');
};
$("#emp_modal_update_btn").on("click", () => {
    var emp_id = $("#emp_inactive_id").val(),
        inactive_status = $("#emp_inactive_reason").children("option:selected").val(),
        emp_last_working_day = $("#emp_last_working_day").val(),
        modal = $("#emp_inactive_status_update");
    if (isInvalidValue(emp_last_working_day)) {
        pointInvalid($("#emp_last_working_day"));
        toastAlert("Last Working Day cannot be left blank", "error");
        return false;
    }
    const data = {
        ajax_action: "EMPLOYEE_ACTIVE",
        row_id: emp_id,
        act_status: inactive_status,
        lwd: emp_last_working_day
    };
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        if (err) {
            toastAlert(msg, "error");
            // loader.hide();
            return false;
        }
        toastAlert(msg);
        // loader.hide();
        modal.modal('hide');
        setInterval(() => {
            location.reload();
            return false;
        }, 1000);
    });
});

const changeActiveStatus = (action, id, page_loader) => {
    const active_input = document.getElementById('' + action + '_active_' + id + '');

    active_input.addEventListener('change', e => {
        var data = {
                row_id: id
            },
            active = 0,
            loader = $("#" + page_loader);
        loader.show();
        if (e.target.checked === true) {
            // console.log("Checkbox is checked - boolean value: ", e.target.checked);
            // toastAlert("Activated :" + e.target.checked, "success");
            active = 1;
        }
        if (e.target.checked === false) {
            // console.log("Checkbox is not checked - boolean value: ", e.target.checked);
            // toastAlert("Deactivated : " + e.target.checked, "error");
            active = 0;
        }
        switch (action) {
            case "employee":
                data["ajax_action"] = "EMPLOYEE_ACTIVE";

                if (active == 0) {
                    changeEmployeeActiveStatus(id);
                    loader.hide();
                    return false;
                }
                break;
            case "notice":
                data['ajax_action'] = "NOTICE_ACTIVE";
                break;
            case "payslip":
                data['ajax_action'] = "PAYSLIP_ACTIVE";
                break;
        }
        data["act_status"] = active;
        // clog(data);
        ajaxRequest(data, (res) => {
            var err = res.error,
                msg = res.message;
            if (err) {
                toastAlert(msg, "error");
                loader.hide();
                return false;
            }
            toastAlert(msg);
            loader.hide();
            switch (action) {
                case "notice":
                    var $label_class = (active == 1) ? "text-success" : "text-warning",
                        $label_removeclass = (active == 1) ? "text-warning" : "text-success",
                        $label_text = (active == 1) ? "Published" : "Draft";
                    $('#notice_' + id + '').find(".custom-control-label").html($label_text);
                    $('#notice_' + id + '').find(".custom-control-label").removeClass($label_removeclass);
                    $('#notice_' + id + '').find(".custom-control-label").addClass($label_class);
                    break;
                case "payslip":
                    var $label_class = (active == 1) ? "text-success" : "text-danger",
                        $label_removeclass = (active == 1) ? "text-danger" : "text-success",
                        $label_text = (active == 1) ? "A" : "D";
                    $('#' + action + '_' + id + '').find(".custom-control-label").html($label_text);
                    $('#' + action + '_' + id + '').find(".custom-control-label").removeClass($label_removeclass);
                    $('#' + action + '_' + id + '').find(".custom-control-label").addClass($label_class);
                    break;
                case "employee":
                    setInterval(() => {
                        location.reload();
                        return false;
                    }, 1000);
                    break;
            }
        });
    });
}

const updateEmployee = (data) => {
    // clog(JSON.parse(data));
    clog(data);
    clog(data.employee_name);

    const $emp_name = $("#emp_name"),
        $emp_date_of_birth = $("#emp_date_of_birth"),
        $emp_mother_name = $("#emp_mother_name"),
        $emp_father_name = $("#emp_father_name"),
        $emp_mobile = $("#emp_mobile"),
        $emp_email = $("#emp_email"),
        $blood_group = $("#blood_group"),
        $emp_payroll = $("#emp_payroll"),
        $emp_date_of_join = $("#emp_date_of_join"),
        $emp_desig_id = $("#emp_desig_id"),
        $emp_remarks = $("#emp_remarks"),
        $emp_exp = $("#emp_exp"),
        loader = $("#employee_loader");

    const $emp_id = $("#emp_id"),
        $emp_department = $("#emp_department"),
        $emp_salary = $("#emp_salary"),
        $emp_webmail = $("#emp_webmail"),
        $emergeny_contact_name = $("#emergeny_contact_name"),
        $emergeny_contact_mobile = $("#emergeny_contact_mobile"),
        $current_address = $("#current_address"),
        $permanent_address = $("#permanent_address"),
        $emp_aadhaar = $("#emp_aadhaar"),
        $emp_pan = $("#emp_pan"),
        $emp_salary_ac_number = $("#emp_salary_ac_number"),
        $emp_salary_ac_ifsc = $("#emp_salary_ac_ifsc"),
        $emp_uan = $("#emp_uan"),
        $emp_esic_ip_number = $("#emp_esic_ip_number"),
        $list_employee_row = $("#list_employee_row"),
        $edit_employee_row = $("#edit_employee_row"),
        $employee_user_type = $("#employee_user_type"), //select box
        $user_password = $("#user_password"),
        $employee_report_time = $("#employee_report_time"), //select box
        $employee_report_manager = $("#employee_report_manager"); //select box

    loader.show();
    $emp_name.val(data.employee_name);
    $emp_date_of_birth.val(data.employee_date_of_birth);
    $emp_mother_name.val(data.employee_mother_name);
    $emp_father_name.val(data.employee_father_name);
    $emp_mobile.val(data.employee_mobile);
    $emp_email.val(data.employee_email);
    $blood_group.val(data.employee_blood_group);
    $emp_payroll.val(data.employee_payroll).change();
    $emp_date_of_join.val(data.employee_date_of_joinning);
    $emp_desig_id.val(data.employee_designation_id).change();
    $emp_remarks.val(data.remarks);
    $emp_exp.val(data.employee_experience_duration);

    $emp_id.val(data.employee_id);
    $emp_department.val(data.department_id).change();
    $emp_salary.val(data.salary_amount);
    $emp_webmail.val(data.webmail_address);
    $emergeny_contact_name.val(data.emergency_contact_person_name);
    $emergeny_contact_mobile.val(data.emergency_contact_person_mobile_number);
    $current_address.val(data.current_address);
    $permanent_address.val(data.permanent_address);
    $emp_aadhaar.val(data.aadhaar_number);
    $emp_pan.val(data.pan_number);
    $emp_salary_ac_number.val(data.salary_account_number);
    $emp_salary_ac_ifsc.val(data.salary_account_ifsc_code);
    $emp_uan.val(data.uan_number);
    $emp_esic_ip_number.val(data.esic_ip_number);
    $employee_user_type.val(data.user_type).change();
    $employee_report_time.val(data.reporting_time).change();
    $employee_report_manager.val(data.reporting_manager_user_id).change();
    $user_password.val(data.password);

    $employee_user_type.attr("disabled", "disabled");
    $employee_report_manager.attr("disabled", "disabled");
    $user_password.attr("disabled", "disabled");
    $employee_user_type.attr("readonly", "readonly");
    $employee_report_manager.attr("readonly", "readonly");
    $user_password.attr("readonly", "readonly");

    $("#emp_update_btn").attr("data-id", data.id);

    $("#last_update_display").find("#last_update_date_span").children("span").text(data.last_update_date);
    $("#emp_active_status").children("span").text((data.active == 1) ? "ACTIVE" : EMPLOYEE_INACTIVE_REASONS[data.active]);
    if (data.active != 1) {
        $("#last_update_display").children("div").show();
        $("#last_update_display").find("#last_working_day_span").children("span").text(data.last_working_day);
        $("#emp_active_status").children("span").removeClass("text-success");
        $("#emp_active_status").children("span").addClass("text-secondary");
    } else {
        $("#emp_active_status").children("span").removeClass("text-secondary");
        $("#emp_active_status").children("span").addClass("text-success");
        $("#last_update_display").children("div").hide();
    }

    $list_employee_row.hide();
    $edit_employee_row.show();
    loader.hide();
};

$("#emp_update_btn").on("click", () => {
    const $emp_name = $("#emp_name"),
        $emp_date_of_birth = $("#emp_date_of_birth"),
        $emp_mother_name = $("#emp_mother_name"),
        $emp_father_name = $("#emp_father_name"),
        $emp_mobile = $("#emp_mobile"),
        $emp_email = $("#emp_email"),
        $blood_group = $("#blood_group"),
        $emp_payroll = $("#emp_payroll"),
        $emp_date_of_join = $("#emp_date_of_join"),
        $emp_desig_id = $("#emp_desig_id"),
        $emp_remarks = $("#emp_remarks"),
        $emp_exp = $("#emp_exp"),
        loader = $("#employee_loader");

    const $emp_id = $("#emp_id"),
        $emp_department = $("#emp_department"),
        $emp_salary = $("#emp_salary"),
        $emp_webmail = $("#emp_webmail"),
        $emergeny_contact_name = $("#emergeny_contact_name"),
        $emergeny_contact_mobile = $("#emergeny_contact_mobile"),
        $current_address = $("#current_address"),
        $permanent_address = $("#permanent_address"),
        $emp_aadhaar = $("#emp_aadhaar"),
        $emp_pan = $("#emp_pan"),
        $emp_salary_ac_number = $("#emp_salary_ac_number"),
        $emp_salary_ac_ifsc = $("#emp_salary_ac_ifsc"),
        $emp_uan = $("#emp_uan"),
        $emp_esic_ip_number = $("#emp_esic_ip_number"),
        $employee_report_time = $("#employee_report_time");


    var emp_name = $emp_name.val(),
        emp_date_of_birth = $emp_date_of_birth.val(),
        emp_mother_name = $emp_mother_name.val(),
        emp_father_name = $emp_father_name.val(),
        emp_mobile = $emp_mobile.val(),
        emp_email = $emp_email.val(),
        blood_group = $blood_group.val(),
        emp_payroll = $emp_payroll.children("option:selected").val(),
        emp_date_of_join = $emp_date_of_join.val(),
        emp_desig_id = $emp_desig_id.children("option:selected").val(),
        emp_remarks = $emp_remarks.val(),
        emp_exp = $emp_exp.val();

    var emp_id = $emp_id.val(),
        emp_department = $emp_department.children("option:selected").val(),
        employee_report_time = $employee_report_time.children("option:selected").val(),
        emp_salary = $emp_salary.val(),
        emp_webmail = $emp_webmail.val(),
        emergeny_contact_name = $emergeny_contact_name.val(),
        emergeny_contact_mobile = $emergeny_contact_mobile.val(),
        current_address = $current_address.val(),
        permanent_address = $permanent_address.val(),
        emp_aadhaar = $emp_aadhaar.val(),
        emp_pan = $emp_pan.val(),
        emp_salary_ac_number = $emp_salary_ac_number.val(),
        emp_salary_ac_ifsc = $emp_salary_ac_ifsc.val(),
        emp_uan = $emp_uan.val(),
        emp_esic_ip_number = $emp_esic_ip_number.val();

    if (isInvalidValue(emp_name)) {
        pointInvalid($emp_name);
        toastAlert("Employee name cannot be left blank", "error");
        return false;
    } else {
        $emp_name.removeClass("error_input");
    }
    if (isInvalidValue(emp_id)) {
        pointInvalid($emp_id);
        toastAlert("Employee ID cannot be left blank", "error");
        return false;
    } else {
        $emp_id.removeClass("error_input");
    }
    if (!(emp_desig_id)) {
        pointInvalid($emp_desig_id);
        toastAlert("Employee Designation cannot be left blank", "error");
        return false;
    } else {
        $emp_desig_id.removeClass("error_input");
    }
    if (!(emp_department)) {
        pointInvalid($emp_department);
        toastAlert("Employee Department cannot be left blank", "error");
        return false;
    } else {
        $emp_department.removeClass("error_input");
    }
    if (!(employee_report_time)) {
        pointInvalid($employee_report_time);
        toastAlert("Employee Reporting Time cannot be left blank", "error");
        return false;
    } else {
        $employee_report_time.removeClass("error_input");
    }
    if (isInvalidValue(emp_mobile)) {

    } else {
        if (isNaN(emp_mobile)) {
            pointInvalid($emp_mobile);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emp_mobile.removeClass("error_input");
        }
        if ((emp_mobile.length) > 13) {
            pointInvalid($emp_mobile);
            clog(emp_mobile.length);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emp_mobile.removeClass("error_input");
        }
    }
    if (isInvalidValue(emergeny_contact_mobile)) {

    } else {
        if (isNaN(emergeny_contact_mobile)) {
            pointInvalid($emergeny_contact_mobile);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emergeny_contact_mobile.removeClass("error_input");
        }
        if ((emergeny_contact_mobile.length) > 13) {
            pointInvalid($emergeny_contact_mobile);
            clog(emergeny_contact_mobile.length);
            toastAlert("Enter a valid mobile number", "error");
            return false;
        } else {
            $emergeny_contact_mobile.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_aadhaar)) {

    } else {
        if (isNaN(emp_aadhaar)) {
            pointInvalid($emp_aadhaar);
            toastAlert("Enter a valid Aadhaar number", "error");
            return false;
        } else {
            $emp_aadhaar.removeClass("error_input");
        }
        if ((emp_aadhaar.length) > 13) {
            pointInvalid($emp_aadhaar);
            clog(emp_aadhaar.length);
            toastAlert("Enter a valid Aadhaar number", "error");
            return false;
        } else {
            $emp_aadhaar.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_salary_ac_number)) {

    } else {
        if (isNaN(emp_salary_ac_number)) {
            pointInvalid($emp_salary_ac_number);
            toastAlert("Enter a valid Account number", "error");
            return false;
        } else {
            $emp_salary_ac_number.removeClass("error_input");
        }
        if ((emp_salary_ac_number.length) > 13) {
            pointInvalid($emp_salary_ac_number);
            clog(emp_salary_ac_number.length);
            toastAlert("Enter a valid Account number", "error");
            return false;
        } else {
            $emp_salary_ac_number.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_uan)) {

    } else {
        if (isNaN(emp_uan)) {
            pointInvalid($emp_uan);
            toastAlert("Enter a valid UAN number", "error");
            return false;
        } else {
            $emp_uan.removeClass("error_input");
        }
        if ((emp_uan.length) > 13) {
            pointInvalid($emp_uan);
            clog(emp_uan.length);
            toastAlert("Enter a valid UAN number", "error");
            return false;
        } else {
            $emp_uan.removeClass("error_input");
        }
    }
    if (isInvalidValue(emp_esic_ip_number)) {

    } else {
        if (isNaN(emp_esic_ip_number)) {
            pointInvalid($emp_esic_ip_number);
            toastAlert("Enter a valid IP number", "error");
            return false;
        } else {
            $emp_esic_ip_number.removeClass("error_input");
        }
        if ((emp_esic_ip_number.length) > 13) {
            pointInvalid($emp_esic_ip_number);
            clog(emp_esic_ip_number.length);
            toastAlert("Enter a valid IP number", "error");
            return false;
        } else {
            $emp_esic_ip_number.removeClass("error_input");
        }
    }
    loader.show();
    const data = {
        ajax_action: "UPDTAE_EMPLOYEE",
        enm: emp_name,
        edb: emp_date_of_birth,
        emn: emp_mother_name,
        efn: emp_father_name,
        emb: emp_mobile,
        eeml: emp_email,
        ebg: blood_group,
        eprl: emp_payroll,
        edtj: emp_date_of_join,
        ermk: emp_remarks,
        exp: emp_exp,
        edgn: emp_desig_id,
        emp_esic_ip_number,
        emp_uan,
        emp_salary_ac_ifsc,
        emp_salary_ac_number,
        emp_pan,
        emp_aadhaar,
        permanent_address,
        current_address,
        emergeny_contact_mobile,
        emergeny_contact_name,
        emp_webmail,
        emp_salary,
        emp_department,
        emp_id,
        emp_row_id: $("#emp_update_btn").attr("data-id"),
        emp_rprt_tm: employee_report_time
    };
    clog(data);
    // return false;
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        if (err) {
            toastAlert(msg, 'error');
            loader.hide();
            return false;
        }
        toastAlert(msg);
        loader.hide();
        setInterval(() => {
            window.location.href = HOST_URL + 'employees';
            return false;
        }, 1500);
    });
});

$("#upload_notice").on("click", () => {
    const $notice_status = $("#notice_status"),
        $notice_file = $("#notice_file"),
        $notice_subject = $("#notice_subject"),
        loader = $("#notice_loader");
    var notice_status = $notice_status.children("option:selected").val(),
        notice_subject = $notice_subject.val(),
        notice_file = $notice_file.val();

    $notice_status.removeClass("error_input");
    $notice_file.removeClass("error_input");

    if ((isInvalidValue(notice_status)) || (notice_status == 0)) {
        pointInvalid($notice_status);
        toastAlert("Please Select Publish Status", "error");
        return false;
    }
    if (isInvalidValue(notice_file)) {
        pointInvalid($notice_file);
        toastAlert("You don't have selected any file", "error");
        return false;
    } else {
        var allowedFiles = [".pdf", ".jpeg", ".jpg", ".JPEG", ".png", ".PNG", ".JPG", ".PDF"];
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(notice_file.toLowerCase())) {
            var str = allowedFiles.join(', ');
            toastr['error']("Please upload files having extensions: " + str + " only.");
            return false;
        }
    }
    loader.show();
    // clog($notice_file);
    // return false;
    let file = $notice_file[0].files[0],
        ajax_request = 'UPLOAD_NOTICE',
        form = new FormData();
    form.append('ajax_action', ajax_request);
    form.append('media_file', file);
    form.append('notice_subject', notice_subject);
    form.append('notice_status', notice_status);

    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round(((evt.loaded / evt.total) * 100));
                    $(".progress-bar").width(percentComplete + '%');
                    $(".progress-bar").html(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        url: DEF_AJAX_URL,
        type: "POST",
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            $('.media_progress').showLoader();
        },
        success: (data) => {
            // Hide the progress bar
            $('.media_progress').hide();
            $(".progress-bar").width('0%');
            $(".progress-bar").html('0%');
            clog(data);
            loader.hide();
            var parseData = IS_CONTENT_TYPE_JSON ? data : JSON.parse(data);
            if (parseData.error) {
                //Some error occurd
                toastr.error(parseData.message);
                return false;
            }
            toastAlert(parseData.message);
            setInterval(() => {
                window.location.href = HOST_URL + 'notices';
                return false;
            }, 1200);
        },
        error: (xhr, status, error) => {
            clog("AJAX Error");
            clog(xhr);
            clog(status);
            clog(error);
            $('.loader-overlay').hide();
            loader.hide();
            if (xhr.readyState == 0) {
                toastr.error("Opps! Could not connect to the server. Please try again.");
            } else if (xhr.readyState == 500) {
                toastr.error("Opps! Forbidden.");
            } else if (xhr.readyState == 404) {
                toastr.error("Not found Error.");
            }
        }
    });
});

const fileNamePreview = (file_input_tag) => {
    const file = file_input_tag,
        preview = $("#file_name_preview"),
        file_clear_btn = $("#file_clear_btn");
    var file_name = file[0].files[0].name,
        percentComplete = 100,
        after20 = (Math.floor(Math.random() * 6) + 5);

    if (after20 <= 20) {
        after20 = (after20 + 47);
    }
    if (after20 >= 100) {
        after20 = (after20 - 14);
    }
    preview.children("span").html(file_name);
    $(".progress-bar").width(0 + '%');
    $(".progress-bar").html(0 + '%');
    $('.media_progress').show();
    var interval1 = setInterval(() => {
        $(".progress-bar").width(20 + '%');
        $(".progress-bar").html(20 + '%');
    }, 1000);
    var interval2 = setInterval(() => {
        $(".progress-bar").width(after20 + '%');
        $(".progress-bar").html(after20 + '%');
    }, 1500);
    var interval3 = setInterval(() => {
        $(".progress-bar").width(percentComplete + '%');
        $(".progress-bar").html(percentComplete + '%');
    }, 1700);
    var interval4 = setInterval(() => {
        $('.media_progress').hide();
        preview.show();
        file_clear_btn.show();
        clearInterval(interval1);
        clearInterval(interval2);
        clearInterval(interval3);
        clearInterval(interval4);
        return false;
    }, 2000);
}
$("#file_clear_btn").on('click', () => {
    const file_upload = $('input[name="file_upload"]'),
        preview = $("#file_name_preview");
    file_upload.val('');
    preview.hide();
    preview.children("span").html('');
    $("#file_clear_btn").hide();
});

$("#apply_leave").on('click', () => {
    const $leave_month = $("#leave_month"),
        $leave_year = $("#leave_year"),
        $leave_dates = $("#leave_dates"),
        $leave_subject = $("#leave_subject"),
        $leave_matter = $("#leave_matter"),
        $leave_ref_doc = $("#leave_ref_doc"),
        loader = $("#leave_loader");
    var leave_month = $leave_month.children("option:selected").val(),
        leave_year = $leave_year.children("option:selected").val(),
        leave_dates = $leave_dates.val(),
        leave_subject = $leave_subject.val(),
        leave_matter = $leave_matter.val(),
        leave_ref_doc = $leave_ref_doc.val(),
        hasFile = 0;

    $leave_month.removeClass("error_input");
    $leave_year.removeClass("error_input");
    $leave_dates.removeClass("error_input");
    $leave_subject.removeClass("error_input");
    $leave_matter.removeClass("error_input");
    $leave_ref_doc.removeClass("error_input");

    if (isInvalidValue(leave_month) || (leave_month == 0)) {
        pointInvalid($leave_month);
        toastAlert("Month must be selected", "error");
        return false;
    }
    if (isInvalidValue(leave_year) || (leave_year == 0)) {
        pointInvalid($leave_year);
        toastAlert("Year must be selected", "error");
        return false;
    }
    if (isInvalidValue(leave_dates)) {
        pointInvalid($leave_dates);
        toastAlert("Leave dates must be entered", "error");
        return false;
    }
    if (isInvalidValue(leave_subject)) {
        pointInvalid($leave_subject);
        toastAlert("Leave subject cannot left blank", "error");
        return false;
    }
    if (isInvalidValue(leave_matter)) {
        pointInvalid($leave_matter);
        toastAlert("Leave matter cannot left blank", "error");
        return false;
    }
    if (isInvalidValue(leave_ref_doc)) {

    } else {
        hasFile = 1;
        var allowedFiles = [".pdf", ".PDF"];
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(leave_ref_doc.toLowerCase())) {
            var str = allowedFiles.join(', ');
            pointInvalid($leave_ref_doc);
            toastr['error']("Please upload files having extensions: " + str + " only.");
            return false;
        }
        // toastAlert("File Uploaded: " + hasFile);
        // return false;
    }
    loader.show();

    let file = $leave_ref_doc[0].files[0],
        ajax_request = 'APPLY_LEAVE',
        form = new FormData();
    form.append('ajax_action', ajax_request);
    form.append('media_file', file);
    form.append('lmonth', leave_month);
    form.append('lyear', leave_year);
    form.append('ldates', leave_dates);
    form.append('lsubject', leave_subject);
    form.append('lmatter', leave_matter);
    form.append('hasFile', hasFile);
    const data = form;
    clog(data);
    // return false;


    // ajaxRequest(data, (res) => {
    //     clog(res);
    //     loader.hide();
    //     if (res.error) {
    //         toastAlert(res.message, "error");
    //         return false;
    //     }
    //     toastAlert(res.message);
    //     setInterval(() => {
    //         location.reload();
    //         return false;
    //     }, 1500);
    // });



    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round(((evt.loaded / evt.total) * 100));
                    $(".progress-bar").width(percentComplete + '%');
                    $(".progress-bar").html(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        url: DEF_AJAX_URL,
        type: "POST",
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            $('.media_progress').show();
        },
        success: (data) => {
            // Hide the progress bar
            $('.media_progress').hide();
            $(".progress-bar").width('0%');
            $(".progress-bar").html('0%');
            clog(data);
            loader.hide();
            var parseData = IS_CONTENT_TYPE_JSON ? data : JSON.parse(data);
            if (parseData.error) {
                //Some error occurd
                toastr.error(parseData.message);
                return false;
            }
            toastAlert(parseData.message);
            setInterval(() => {
                location.reload();
                return false;
            }, 1200);
        },
        error: (xhr, status, error) => {
            clog("AJAX Error");
            clog(xhr);
            clog(status);
            clog(error);
            $('.loader-overlay').hide();
            loader.hide();
            if (xhr.readyState == 0) {
                toastr.error("Opps! Could not connect to the server. Please try again.");
            } else if (xhr.readyState == 500) {
                toastr.error("Opps! Forbidden.");
            } else if (xhr.readyState == 404) {
                toastr.error("Not found Error.");
            }
        }
    });
});

const viewLeaveDetails = (action, data) => {
    clog(data);
    const modal = $("#leave_details_modal"),
        modal_body = modal.find(".modal-body");
    var content = `
    <div class="row">
        <div class="col-12 text-right" style="font-size: 12px; margin-bottom: 20px;"><b>Leave Applied On: </b>${data.leave_apply_date}</div>`;
    if (action != 'employee') {
        content += `<div class="col-md-6 col-lg-6 col-sm-12">
            <span class="form_label">Employee Details: </span>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
            <span class="text-secondary">${data.employee_name} [${data.employee_designation}]</span>
        </div>
    </div>
    <div class="row mt-2">`;
    }
    content += `<div class="col-md-6 col-lg-6 col-sm-12">
            <span class="form_label">Leave Dates: </span>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
            <span class="text-danger">${data.leave_dates}</span>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <span class="form_label">Subject: </span>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
            <span class="text-secondary">${data.leave_subject}</span>
        </div>
    </div>
    <div class="row mt-2 leave_matter">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <span class="form_label">Matter: </span>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 text-left" style="padding: 10px;">
            <span class="text-primary">${data.leave_matter.replace(/\\'/g, "'")}</span>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6 col-lg-6 col-sm-12">
            <span class="form_label">Reference Doc: </span>
        </div>
        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
            <span class="text-primary">${data.reference_doc}</span>
        </div>
    </div>`;
    if (action == 'employee') {
        if (data.response_by_admin_id != data.reporting_manager_user_id) {
            content += `<div class="row mt-2">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <span class="form_label">Reporting Manager Action: </span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                    <span class="text-primary">${data.action_taken_status}</span>
                </div>
            </div>`;
        }
    } else {
        if ((data.response_by_user_type != ADMIN) && (data.response_by_user_type != SADMIN)) {
            content += `<div class="row mt-2">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <span class="form_label">Reporting Manager Action: </span>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                    <span class="text-primary">${data.action_taken_status}</span>
                </div>
            </div>`;
        } else {
            if (data.response_by_admin_id != data.reporting_manager_user_id) {
                content += `<div class="row mt-2">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <span class="form_label">Reporting Manager Action: </span>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                        <span class="text-primary">${data.action_taken_status}</span>
                    </div>
                </div>`;
            }
        }
    }
    // if (action == 'manager') {
    //     content += `<div class="row mt-2">
    //         <div class="col-md-6 col-lg-6 col-sm-12">
    //             <span class="form_label">Reporting Manager Action: </span>
    //         </div>
    //         <div class="col-md-6 col-lg-6 col-sm-12 text-right">
    //             <span class="text-primary">${data.action_taken_status}</span>
    //         </div>
    //     </div>`;
    // }
    if (data.admin_action_taken_status != 0) {
        content += `<div class="row mt-2">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <span class="form_label">Final Action: </span>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                <span class="text-primary">${data.final_status}</span>
            </div>
        </div>
        `;
    }
    switch (action) {
        case 'employee':
            if (((data.response !== null) && (data.response_by_user_id !== null)) || (data.admin_action_taken_status != APPLIED)) {
                content += `
                <div class="loader-overlay" id="leave_modal_loader_${data.id}" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <fieldset class="fldset mt-3">
                    <legend>Response</legend>
                    <div class="row mt-2">
                        <div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Last Responced On: </b>${(data.action_taken_status_id == APPLIED) ? ((data.admin_action_taken_status != 0) ? data.admin_response_date : "No Response yet") : ((data.admin_action_taken_status != 0) ? data.admin_response_date : data.response_date)}</div>
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <span class="form_label">Response: </span>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                            <span class="text-dark" style="font-size: 14px;">${(data.admin_action_taken_status == 0) ? ((data.action_taken_status_id == APPLIED) ? "N/A" : data.response) : (data.admin_response)}</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6 col-lg-6 col-sm-12">
                            <span class="form_label">Response by: </span>
                        </div>
                        <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                            <span class="text-primary" style="font-size: 12px;">${data.response_by_user_id}</span>
                        </div>
                    </div>
                </fieldset>
                `;
            }
            break;

        case 'admin':
            content += `
            <fieldset class="fldset mt-3">
                <legend>Response</legend>
                <div class="row mt-2">
                    <div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Last Responced On: </b>${(data.action_taken_status_id == APPLIED) ? ((data.admin_action_taken_status != 0) ? data.admin_response_date : "NEVER RESPONCED") : ((data.admin_action_taken_status != 0) ? data.admin_response_date : data.response_date)}</div>`;
            if (((data.action_taken_status_id == ACCEPTED) || (data.action_taken_status_id == REJECTED)) || (USER_ROW_ID == data.reporting_manager_user_id)) {

                content += `
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <span class="form_label">Write Response: </span>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                        <input type="text" class="form-control tooltip_ele" placeholder="write response" value="${(data.admin_response == null) ? "" : data.admin_response}" title="${(data.admin_response == null) ? "" : data.admin_response}" id="admin_leave_response_${data.id}" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <span class="form_label">Change Status: </span>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                        <select class="form-control" id="admin_leave_status_${data.id}">${data.status_options}</select>
                    </div>
                </div>
                <div class="text-right mt-2">
                    <button type="button" class="btn btn-success" id="admin_response_update_${data.id}" onclick="updateAdminResponse(${data.id});">Update</button>`;
            } else {
                content +=
                    (data.action_taken_status_id == APPLIED) ? ((data.admin_action_taken_status != 0) ? `<div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Response by: </b><span class="text-primary">${data.response_by_user_id}</span></div>` : "") : ((data.admin_action_taken_status != 0) ? `<div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Response by: </b><span class="text-primary">${data.response_by_user_id}</span></div>` : `<div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Response by: </b><span class="text-primary">${data.response_by_user_id}</span></div>`);
            }
            content += `</div>
            </fieldset>
            `;
            break;
        case 'manager':
            content += `
            <fieldset class="fldset mt-3">
                <legend>Response</legend>
                <div class="row mt-2">
                    <div class="col-12 text-center" style="font-size: 12px; margin-bottom: 20px;"><b>Last Responced On: </b>${(data.response == null) ? "NEVER RESPONCED" : data.response_date}</div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <span class="form_label">Write Response: </span>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                        <input type="text" class="form-control tooltip_ele" placeholder="write response" value="${(data.response == null) ? "" : data.response}" title="${(data.response == null) ? "" : data.response}" id="manager_leave_response_${data.id}" />
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <span class="form_label">Change Status: </span>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                        <select class="form-control" id="manager_leave_status_${data.id}">${data.status_options}</select>
                    </div>
                </div>
                <div class="text-right mt-2">
                    <button type="button" class="btn btn-success" id="admin_response_update_${data.id}" onclick="updateManagerResponse(${data.id});">Update</button>
                </div>
            </fieldset>
            `;
            break;
    }
    modal_body.html(content);
    if (action == "manager") {
        $("#manager_leave_status_" + data.id).val(data.action_taken_status_id);
    }
    if (action == "admin") {
        $("#admin_leave_status_" + data.id).val(data.admin_action_taken_status);
    }
    // modal.find(".modal-title").html("Leave Details");
    modal.modal('show');
};
$("#leave_details_modal").find(".modal-footer").find(".btn").on('click', () => {
    $("#leave_details_modal").find(".modal-body").html(``);
});
$("#leave_details_modal").find(".modal-header").find(".close").on('click', () => {
    $("#leave_details_modal").find(".modal-body").html(``);
});

const updateManagerResponse = (id) => {
    const $manager_leave_response = $("#manager_leave_response_" + id),
        $manager_leave_status = $("#manager_leave_status_" + id),
        loader = $("#leave_modal_loader_" + id);
    var manager_leave_response = $manager_leave_response.val(),
        manager_leave_status = $manager_leave_status.children("option:selected").val();

    $manager_leave_response.removeClass("error_input");
    $manager_leave_status.removeClass("error_input");
    loader.show();
    if (isInvalidValue(manager_leave_response)) {
        pointInvalid($manager_leave_response);
        toastAlert("Please write a response", "error");
        loader.hide();
        return false;
    }
    if (manager_leave_status == 0) {
        pointInvalid($manager_leave_response);
        toastAlert("Please select a status", "error");
        loader.hide();
        return false;
    }

    const data = {
        ajax_action: "UPDATE_LEAVE_RESPONSE_BY_MANAGER",
        resp: manager_leave_response,
        resp_status: manager_leave_status,
        lid: id
    };

    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        loader.hide();
        if (err) {
            toastAlert(msg, "error");
            return false;
        }
        toastAlert(msg);
        setInterval(() => {
            $("#leave_details_modal").modal('hide');
            $("#leave_details_modal").find(".modal-body").html(``);
            location.reload();
            return false;
        }, 1200);
    });

};
const updateAdminResponse = (id) => {
    const $admin_leave_response = $("#admin_leave_response_" + id),
        $admin_leave_status = $("#admin_leave_status_" + id),
        loader = $("#leave_modal_loader_" + id);
    var admin_leave_response = $admin_leave_response.val(),
        admin_leave_status = $admin_leave_status.children("option:selected").val();

    $admin_leave_response.removeClass("error_input");
    $admin_leave_status.removeClass("error_input");
    loader.show();
    if (isInvalidValue(admin_leave_response)) {
        pointInvalid($admin_leave_response);
        toastAlert("Please write a response", "error");
        loader.hide();
        return false;
    }
    if (admin_leave_status == 0) {
        pointInvalid($admin_leave_response);
        toastAlert("Please select a status", "error");
        loader.hide();
        return false;
    }

    const data = {
        ajax_action: "UPDATE_LEAVE_RESPONSE_BY_ADMIN",
        resp: admin_leave_response,
        resp_status: admin_leave_status,
        lid: id
    };

    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        loader.hide();
        if (err) {
            toastAlert(msg, "error");
            return false;
        }
        toastAlert(msg);
        setInterval(() => {
            $("#leave_details_modal").modal('hide');
            $("#leave_details_modal").find(".modal-body").html(``);
            location.reload();
            return false;
        }, 1200);
    });

};

$("#admin_profile_update").on('click', () => {
    const $admin_profile_name = $("#admin_profile_name"),
        $admin_profile_email = $("#admin_profile_email"),
        $admin_profile_mobile = $("#admin_profile_mobile"),
        loader = $("#profile_loader");

    var admin_profile_mobile = $admin_profile_mobile.val(),
        admin_profile_name = $admin_profile_name.val(),
        admin_profile_email = $admin_profile_email.val();

    $admin_profile_name.removeClass('error_input');
    $admin_profile_email.removeClass('error_input');
    $admin_profile_mobile.removeClass('error_input');

    if (isInvalidValue(admin_profile_name)) {
        pointInvalid($admin_profile_name);
        toastAlert("Name cannot be left blank", "error");
        return false;
    }
    if (isInvalidValue(admin_profile_email)) {
        pointInvalid($admin_profile_email);
        toastAlert("Email cannot be left blank", "error");
        return false;
    }
    if (isInvalidValue(admin_profile_mobile)) {
        // pointInvalid($admin_profile_mobile);
        // toastAlert("Mobile cannot be left blank", "error");
        // return false;
    } else {
        if (isNaN(admin_profile_mobile)) {
            pointInvalid($admin_profile_mobile);
            toastAlert("Please insert a valid mobile number", 'error');
            return false;
        }
    }
    loader.show();
    const data = {
        ajax_action: "UPDATE_PROFILE",
        nm: admin_profile_name,
        em: admin_profile_email,
        mb: admin_profile_mobile
    };
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        loader.hide();
        if (err) {
            toastAlert(msg, "error");
            return false;
        }
        toastAlert(msg);
        setInterval(() => {
            location.reload();
            return false;
        }, 1200);
        return false;
    });

});

const initiateDelete = (id, action) => {
    const modal = $("#delete_modal"),
        delete_id = $("#delete_id"),
        modal_body = modal.find(".modal-body");

    var body = `
    <div class="row">
        <div class="col-12 text-center" style="margin-bottom:10px;"><i>Are sure you want to delete this item?</i></div>
        <div class="col-6 text-right">
            <button type="button" class="btn btn-danger" id="delete_confirm" onclick="deleteData(${id}, '${action}');">Delete</button>
        </div>
        <div class="col-6 text-left">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close" id="delete_cancel">Cancel</button>
        </div>
    </div>
    `;
    // if (CURRENT_USER_TYPE != SADMIN) {
    //     body = `
    //     <div class="row">
    //         <div class="col-12 text-center" style="margin-bottom:10px;"><i>You are not allowed to perform this action</i></div>
    //     </div>
    //     `;
    // }

    delete_id.val('');
    modal_body.html('');
    delete_id.val(id);
    modal_body.html(body);
    modal.modal('show');
}

const deleteData = (id, action) => {
    const modal = $("#delete_modal"),
        delete_id = $("#delete_id"),
        modal_body = modal.find(".modal-body"),
        table = $("." + action + "_table"),
        data = {
            ajax_action: "DELETE_ITEM",
            id: id,
            action: action
        };
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        if (err) {
            toastAlert(msg, 'error');
            modal.modal('hide');
            return false;
        }
        toastAlert(msg);
        modal.modal('hide');
        table.find('tbody').find("#" + action + "_" + id).addClass("animated fadeOut");
        setInterval(() => {
            // location.reload();
            table.find('tbody').find("#" + action + "_" + id).remove();
            return false;
        }, 1000);

    });
};

$("#add_more_billing_item").on('click', () => {
    var field = `
    <fieldset class="fldset mt-2 billing_item_fldset">
        <legend>Billing Items</legend>
        <div class="text-right">
            <span class="remove_billing_item cursor-pointer" onclick="removeMoreItem($(this))">[ <span class="text-danger">Remove</span> ]</span>
        </div>
        <div class="row mt-2">
            <div class="col-md-8 col-lg-8 col-sm-12">
                <label class="form_label" for="billing_item_desc">Description</label>
                <input type="text" class="form-control billing_item_desc" name="billing_item_desc[]" id="billing_item_desc" />
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12">
                <label class="form_label" for="billing_item_quantity">Quantity</label>
                <input type="text" class="form-control billing_item_quantity" id="billing_item_quantity" name="billing_item_quantity[]" onkeydown="return acceptNumber(event, true);" onkeyup="calculateItemTotal($(this));"/>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label class="form_label" for="billing_item_rate">Rate</label>
                <input type="text" class="form-control text-right billing_item_rate" name="billing_item_rate[]" id="billing_item_rate" value="${DEFAULT_AMOUNT}" onkeydown="return acceptNumber(event, true);" onkeyup="calculateItemTotal($(this));"/>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label class="form_label" for="billing_item_per">Per</label>
                <input type="text" class="form-control billing_item_per" id="billing_item_per" name="billing_item_per[]" onkeydown="return acceptNumber(event, true)"/>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-9 col-lg-9 col-sm-12 text-right">
                <span class="form_label">Total Amount: </span>
            </div>
            <div class="col-md-3 col-lg-3 col-sm-12">
                <input type="text" class="form-control text-right billing_item_total_amount" name="billing_item_total_amount[]" id="billing_item_total_amount" value="${DEFAULT_AMOUNT}" onfocus="calculateItemTotal($(this));" onkeydown="return acceptNumber(event, true)"/>
            </div>
        </div>
    </fieldset>
    `;
    document.getElementById('billing_details').insertAdjacentHTML('beforeend', field);
});

function removeMoreItem(field) {
    field.closest('.MoreBillingItem').remove();
}

const AcceptPayslip = (id) => {
    const selectbox = $("#emp_payslip_accept_status_" + id);
    var val = selectbox.children("option:selected").val();
    $color = '';
    if (val == PAYSLIP_PENDING) {
        $color = 'text-warning';
    }
    if (val == PAYSLIP_DISPUTE_RAISED) {
        $color = 'text-danger';
    }
    if (val == PAYSLIP_ACCEPTED) {
        $color = 'text-success';
    }
    selectbox.removeClass('text-success');
    selectbox.removeClass('text-warning');
    selectbox.removeClass('text-danger');
    selectbox.addClass($color);
    // clog(val);
    const data = {
        ajax_action: "ACCEPT_PAYSLIP",
        pid: id,
        st: val
    };
    ajaxRequest(data, (res) => {
        var err = res.error,
            msg = res.message;
        if (err) {
            toastAlert(msg, "error");
            setInterval(() => {
                location.reload();
                return false;
            }, 1000);
        }
        toastAlert(msg);
        return false;
    });
};

$("#save_bill").on('click', () => {
    $('form#billing_form :input').each(function() {
        // ('form#billing_form :input[name="billing_item_desc[]"]').each(function() {
        clog($(this));
    });
});

const calculateTaxableAmount = () => {
    let billing_item_total_amount = $('input[name="billing_item_total_amount[]"]'),
        index = 0,
        total = 0;
    billing_item_total_amount.each(function() {
        let item_total = $(billing_item_total_amount[index]).val();
        if ((item_total == 0) || (isInvalidValue(item_total))) {
            item_total = 0;
        }
        clog('item_total: ' + item_total);
        total += parseInt(item_total);
        index++;
    });
    if (isNaN(total)) {
        total = 0;
    }
    $("#total_taxable_amount").val(parseInt(total));
}

$("#total_taxable_amount").on('focus', () => {
    calculateTaxableAmount();
});

if (document.getElementById("gst_check") !== null) {
    document.getElementById("gst_check").addEventListener('change', e => {
        const gst_check = $("#gst_check"),
            igst_check = $("#igst_check"),
            grand_total_txt = $("#grand_total_txt"),
            igst_check_col = $(".igst_check_col");

        if (e.target.checked === true) {
            igst_check_col.show();
            clog('true');
            grand_total_txt.html("Grand Total (Including GST):");
        }
        if (e.target.checked === false) {
            grand_total_txt.html("Grand Total:");
            igst_check_col.hide();
            igst_check_col.find("#igst_check").prop("checked", false);
            clog('false');
        }
    });
}

$("#grand_total_amount").on('focus', () => {
    const $total_taxable_amount = $("#total_taxable_amount"),
        gst_check = $("#gst_check"),
        igst_check = $("#igst_check"),
        $cgst = $("#cgst"),
        $sgst = $("#sgst"),
        $igst = $("#igst"),
        $discount_amount = $("#discount_amount"),
        $grand_total_amount = $("#grand_total_amount"),
        $advance_amount = $("#advance_amount");

    var gst_checked = gst_check.prop("checked"),
        igst_checked = igst_check.prop("checked"),
        total_taxable_amount = (isInvalidValue($total_taxable_amount.val())) ? 0 : parseInt($total_taxable_amount.val()),
        discount_amount = (isInvalidValue($discount_amount.val())) ? 0 : parseInt($discount_amount.val()),
        igst_amount = 0,
        cgst_amount = 0,
        sgst_amount = 0,
        grand_total_amount = 0;

    if ((total_taxable_amount == 0) || total_taxable_amount < 0) {
        return false;
    }

    if ((discount_amount != 0) && (discount_amount > 0)) {
        let dis_amt = (total_taxable_amount - discount_amount);
        total_taxable_amount = dis_amt;
    }

    grand_total_amount = total_taxable_amount;

    if (gst_checked) {
        if (igst_checked) {
            igst_amount = ((DEFAULT_GST_PERCENTAGE > 0) && (total_taxable_amount > 0)) ? ((total_taxable_amount * DEFAULT_GST_PERCENTAGE) / 100) : 0;
            grand_total_amount = (igst_amount > 0) ? parseInt(total_taxable_amount + igst_amount) : total_taxable_amount;
        } else {
            cgst_amount = ((DEFAULT_GST_PERCENTAGE > 0) && (total_taxable_amount > 0)) ? ((total_taxable_amount * (DEFAULT_GST_PERCENTAGE / 2)) / 100) : 0;
            sgst_amount = ((DEFAULT_GST_PERCENTAGE > 0) && (total_taxable_amount > 0)) ? ((total_taxable_amount * (DEFAULT_GST_PERCENTAGE / 2)) / 100) : 0;
            grand_total_amount = ((cgst_amount > 0) && (sgst_amount > 0)) ? parseInt(total_taxable_amount + cgst_amount + sgst_amount) : total_taxable_amount;
        }
    }
    $cgst.val(cgst_amount);
    $sgst.val(sgst_amount);
    $igst.val(igst_amount);
    clog('cgst: ' + cgst_amount + ', sgst: ' + sgst_amount + ', igst: ' + igst_amount);
    $grand_total_amount.val(parseInt(grand_total_amount));
    $advance_amount.val(parseInt(grand_total_amount));
    return false;
});

$("#billing_form").on('submit', () => {
    const $inv_no = $("#inv_no"),
        $inv_date = $("#inv_date"),
        $inv_pay_mode = $("#inv_pay_mode"), //select input
        $billing_name = $("#billing_name"),
        $billing_address = $("#billing_address"),
        $billing_gstin = $("#billing_gstin"),
        $billing_email = $("#billing_email"),
        $billing_phone = $("#billing_phone"),
        $total_taxable_amount = $("#total_taxable_amount"),
        $discount_amount = $("#discount_amount"),
        $cgst = $("#cgst"),
        $sgst = $("#sgst"),
        $igst = $("#igst"),
        $gst_check = $("#gst_check"),
        $igst_check = $("#igst_check"),
        $grand_total_amount = $("#grand_total_amount");


    let $billing_item_desc = $('input[name="billing_item_desc[]"]'),
        $billing_item_quantity = $('input[name="billing_item_quantity[]"]'),
        $billing_item_rate = $('input[name="billing_item_rate[]"]'),
        $billing_item_per = $('input[name="billing_item_per[]"]'),
        $billing_item_total_amount = $('input[name="billing_item_total_amount[]"]');

    var inv_no = $inv_no.val(),
        inv_date = $inv_date.val(),
        inv_pay_mode = $inv_pay_mode.children('option:selected').val(),
        billing_name = $billing_name.val(),
        billing_address = $billing_address.val(),
        billing_gstin = $billing_gstin.val(),
        billing_email = $billing_email.val(),
        billing_phone = $billing_phone.val(),
        total_taxable_amount = $total_taxable_amount.val(),
        discount_amount = $discount_amount.val(),
        cgst = $cgst.val(),
        sgst = $sgst.val(),
        igst = $igst.val(),
        gst_check = $gst_check.prop('checked'),
        igst_check = $igst_check.prop('checked'),
        grand_total_amount = $grand_total_amount.val();

    $inv_no.removeClass("error_input");
    $inv_pay_mode.removeClass("error_input");
    $billing_name.removeClass("error_input");
    $billing_address.removeClass("error_input");
    $billing_gstin.removeClass("error_input");
    $billing_email.removeClass("error_input");
    $billing_phone.removeClass("error_input");
    $total_taxable_amount.removeClass("error_input");
    $discount_amount.removeClass("error_input");
    $cgst.removeClass("error_input");
    $sgst.removeClass("error_input");
    $igst.removeClass("error_input");
    $gst_check.removeClass("error_input");
    $igst_check.removeClass("error_input");
    $grand_total_amount.removeClass("error_input");

    if (isInvalidValue(inv_no)) {
        pointInvalid($inv_no);
        toastAlert("Invoice No. Cannot be left blank", "error");
        return false;
    }
    if (isInvalidValue(inv_pay_mode) || (inv_pay_mode == 0)) {
        pointInvalid($inv_pay_mode);
        toastAlert("Please select a Payment mode", "error");
        return false;
    }
    if (isInvalidValue(billing_name)) {
        pointInvalid($billing_name);
        toastAlert("Billing Name Cannot be left blank", "error");
        return false;
    }
    if (isInvalidValue(billing_address)) {
        $billing_address.val(EMPTY_VALUE);
    }
    if (isInvalidValue(billing_gstin)) {
        $billing_gstin.val(EMPTY_VALUE);
    }
    if (isInvalidValue(billing_email)) {
        $billing_email.val(EMPTY_VALUE);
    }
    if (isInvalidValue(billing_phone)) {
        $billing_phone.val(EMPTY_VALUE);
    }
    if (isInvalidValue(total_taxable_amount)) {
        pointInvalid($total_taxable_amount);
        toastAlert("Billing Name Cannot be left blank", "error");
        return false;
    } else {
        if (isNaN(total_taxable_amount)) {
            toastAlert("Invalid Amount", "error");
            return false;
        }
    }
    if (isInvalidValue(discount_amount)) {
        $discount_amount.val(0);
    } else {
        if (isNaN(discount_amount)) {
            toastAlert("Invalid Amount", "error");
            return false;
        }
    }
    if (isInvalidValue(grand_total_amount)) {
        pointInvalid($grand_total_amount);
        toastAlert("Grand Total Amount Cannot be left blank", "error");
        return false;
    } else {
        if (isNaN(grand_total_amount)) {
            toastAlert("Invalid Amount", "error");
            return false;
        }
    }
    if (gst_check) {
        if (igst_check) {
            if (isInvalidValue(igst)) {
                pointInvalid($igst);
                toastAlert("IGST value is missing", "error");
                return false;
            } else {
                if (isNaN(igst)) {
                    toastAlert("Invalid Amount", "error");
                    return false;
                }
            }
        } else {
            if (isInvalidValue(cgst)) {
                pointInvalid($cgst);
                toastAlert("CGST value is missing", "error");
                return false;
            } else {
                if (isNaN(cgst)) {
                    toastAlert("Invalid Amount", "error");
                    return false;
                }
            }
            if (isInvalidValue(sgst)) {
                pointInvalid($sgst);
                toastAlert("SGST value is missing", "error");
                return false;
            } else {
                if (isNaN(sgst)) {
                    toastAlert("Invalid Amount", "error");
                    return false;
                }
            }
        }
    }
    return true;
});

const calculateItemTotal = (total) => {
    const $billing_item_rate = total.parent().closest('.billing_item_fldset').find('.billing_item_rate'),
        $billing_item_quantity = total.parent().closest('.billing_item_fldset').find('.billing_item_quantity'),
        $billing_item_total_amount = total.parent().closest('.billing_item_fldset').find('.billing_item_total_amount');

    var billing_item_rate = $billing_item_rate.val(),
        billing_item_quantity = $billing_item_quantity.val(),
        t_amount = 0;

    clog("this div: " + total.attr('class'));
    clog("rate: " + $billing_item_rate.val() + ", Qty: " + $billing_item_quantity.val());

    if ((isInvalidValue(billing_item_rate)) || (billing_item_rate == 0)) {
        // pointInvalid($billing_item_rate);
        // toastAlert("CGST value is missing", "error");
        // return false;
        t_amount = 0;
    }
    if ((isInvalidValue(billing_item_quantity)) || (billing_item_quantity == 0)) {
        // pointInvalid($billing_item_rate);
        // toastAlert("CGST value is missing", "error");
        // return false;
        t_amount = 0;
    }

    t_amount = parseInt(parseInt(billing_item_rate) * parseInt(billing_item_quantity));
    if (isNaN(t_amount)) {
        t_amount = 0;
    }
    $billing_item_total_amount.val(t_amount);
};

$("#preview_invoice").on('click', () => {
    alert("Under Development !");
    return false;
    let billing_form = $("#billing_form"),
        url = HOST_URL + "generated-invoice";

    billing_form.attr("action", url);
    billing_form.submit();
});

const saveInvoiceData = (info, item) => {
    clog("Info Data: " + info.client_id);
    clog("Item Data: " + item);

    const $response = $(".response"),
        $save_info = $("#save_info"),
        loader = $("#" + CUSTOM_SPINNER_ID),
        data = {
            ajax_action: "SAVE_INVOICE",
            info_dt: info,
            item_dt: item
        };
    loader.show();
    $response.html('');
    ajaxRequest(data, (res) => {
        let err = res.error,
            msg = res.message;
        $response.show();
        loader.hide();
        if (err) {
            $response.html(msg);
            $response.css({ color: 'red' });
            toastAlert(msg, 'error');
            return false;
        }
        $response.html(msg);
        $response.css({ color: 'green' });
        $save_info.remove();
        toastAlert(msg);
        return false;
    });

}
// const recordAttendance = () => {
//     const btn = $("#attendance_btn"),
//         $attendance_msg = $("#attendance_msg"),
//         $attendance_row = $("#attendance_row"),
//         loader = $("#attendance_loader"),
//         modal = $("#earlyLoggOffReason_modal"),
//         modal_body = modal.find(".modal-body");

//     var attCycle = btn.attr("data-att"),
//         attendance_id = 0;

//     loader.show();
//     if (attCycle == CYCLE_ACTIVE) {
//         ajaxRequest({ ajax_action: 'CALCULATE_EARLY_LOG_OFF_TIME' }, (res) => {
//             var err = res.error,
//                 late = res.is_late,
//                 mints = res.early_logOff_mints,
//                 att_id = res.att_id;
//             if (err) {
//                 interval = setInterval(() => {
//                     loader.hide();
//                     toastAlert("Something wernt wrong !", "error");
//                     clearInterval(interval);
//                     return false;
//                 }, 1000);
//                 return false;
//             }
//             if (late) {
//                 let body = `
//                 <div class="row">
//                     <div class="col-12">
//                         <input type="text" class="form-control" id="earlyLogOffReason" placeholder="Write reason for your early log off">
//                         <input type="hidden" id="early_logOff_mints" style="display:none;" value="${mints}">
//                     </div>
//                     <div class="col-12 text-right" style="margin-top:10px;">
//                         <button class="btn btn-success" type="button" onclick="logOffWithReason(${att_id}, ${attCycle});">Submit</button>
//                     </div>
//                 </div>
//                     `;
//                 interval = setInterval(() => {
//                     modal_body.html('');
//                     modal_body.html(body);
//                     modal.show();
//                     loader.hide();
//                     clearInterval(interval);
//                     return false;
//                 }, 1000);
//                 return false;
//             }
//             var data = {
//                 ajax_action: "ATTENDANCE_ACTION",
//                 attCycle,
//                 att_id
//             };
//             ajaxRequest(data, (res) => {
//                 let err = res.error,
//                     msg = res.message,
//                     row = res.row;
//                 // loader.hide();
//                 if (err) {
//                     interval = setInterval(() => {
//                         toastAlert(msg, 'error');
//                         loader.hide();
//                         clearInterval(interval);
//                         return false;
//                     }, 1000);
//                     return false;
//                 }
//                 interval = setInterval(() => {
//                     toastAlert(msg);
//                     $attendance_row.html('');
//                     $attendance_row.html(row);
//                     loader.hide();
//                     clearInterval(interval);
//                     return false;
//                 }, 1000);
//                 return false;
//             });
//         });
//         return false;
//     }
//     var data = {
//         ajax_action: "ATTENDANCE_ACTION",
//         attCycle
//     };
//     ajaxRequest(data, (res) => {
//         let err = res.error,
//             msg = res.message,
//             row = res.row,
//             interval;
//         if (err) {
//             toastAlert(msg, 'error');
//             interval = setInterval(() => {
//                 loader.hide();
//                 clearInterval(interval);
//                 return false;
//             }, 1000);
//             return false;
//         }
//         interval = setInterval(() => {
//             loader.hide();
//             toastAlert(msg);
//             $attendance_row.html('');
//             $attendance_row.html(row);
//             clearInterval(interval);
//             return false;
//         }, 1000);
//         return false;
//     });
// };

const recordAttendance = () => {
    const btn = $("#attendance_btn"),
        $attendance_msg = $("#attendance_msg"),
        $attendance_row = $("#attendance_row"),
        loader = $("#attendance_loader"),
        modal = $("#earlyLoggOffReason_modal"),
        modal_body = modal.find(".modal-body");

    var attCycle = btn.attr("data-att"),
        attendance_id = 0;

    loader.show();
    if (attCycle == CYCLE_ACTIVE) {
        // clog("CYCLE_ACTIVE");
        // return false;
        ajaxRequest({ ajax_action: 'CALCULATE_EARLY_LOG_OFF_TIME' }, (res) => {
            var err = res.error,
                late = res.is_late,
                mints = res.early_logOff_mints,
                att_id = res.att_id;
            if (err) {
                interval = setInterval(() => {
                    loader.hide();
                    toastAlert("Something wernt wrong !", "error");
                    clearInterval(interval);
                    return false;
                }, 1000);
                return false;
            }
            if (late) {
                let body = `
                <div class="row">
                    <div class="col-12">
                        <input type="text" class="form-control" id="earlyLogOffReason" placeholder="Write reason for your early log off">
                        <input type="hidden" id="early_logOff_mints" style="display:none;" value="${mints}">
                    </div>
                    <div class="col-12 text-right" style="margin-top:10px;">
                        <button class="btn btn-success" type="button" onclick="logOffWithReason(${att_id}, ${attCycle});">Submit</button>
                    </div>
                </div>
                    `;
                interval = setInterval(() => {
                    modal_body.html('');
                    modal_body.html(body);
                    modal.show();
                    loader.hide();
                    clearInterval(interval);
                    return false;
                }, 1000);
                return false;
            } else {
                var att_ac_data = {
                    ajax_action: "ATTENDANCE_ACTION",
                    attCycle,
                    att_id
                };
                // clog("data: " + att_ac_data);
                // return false;
                ajaxRequest(att_ac_data, (res) => {
                    let err = res.error,
                        msg = res.message,
                        row = res.row;
                    // loader.hide();
                    if (err) {
                        interval = setInterval(() => {
                            toastAlert(msg, 'error');
                            loader.hide();
                            clearInterval(interval);
                            return false;
                        }, 1000);
                        return false;
                    }
                    interval = setInterval(() => {
                        toastAlert(msg);
                        $attendance_row.html('');
                        $attendance_row.html(row);
                        loader.hide();
                        clearInterval(interval);
                        // location.reload();
                        return false;
                    }, 1000);
                    return false;
                });
            }
        });
        return false;
    }
    var data = {
        ajax_action: "ATTENDANCE_ACTION",
        attCycle
    };
    ajaxRequest(data, (res) => {
        let err = res.error,
            msg = res.message,
            row = res.row,
            interval;
        if (err) {
            toastAlert(msg, 'error');
            interval = setInterval(() => {
                loader.hide();
                clearInterval(interval);
                return false;
            }, 1000);
            return false;
        }
        interval = setInterval(() => {
            loader.hide();
            toastAlert(msg);
            $attendance_row.html('');
            $attendance_row.html(row);
            clearInterval(interval);
            return false;
        }, 1000);
        return false;
    });
};

const logOffWithReason = (id, attCycle) => {
    const $early_logOff_mints = $("#early_logOff_mints"),
        $earlyLogOffReason = $("#earlyLogOffReason"),
        $attendance_row = $("#attendance_row"),
        loader = $("#attendance_loader"),
        modal = $("#earlyLoggOffReason_modal"),
        modal_body = modal.find(".modal-body");

    var att_id = id,
        early_logOff_mints = $early_logOff_mints.val(),
        earlyLogOffReason = $earlyLogOffReason.val(),
        data = {
            ajax_action: "ATTENDANCE_ACTION",
            attCycle,
            att_id,
            early_mints: early_logOff_mints,
            early_reason: earlyLogOffReason
        };
    if (isInvalidValue(earlyLogOffReason)) {
        pointInvalid($earlyLogOffReason);
        toastAlert("You must have to enter a reason", "error");
        return false;
    } else {
        $earlyLogOffReason.removeClass("error_input");
    }
    loader.show();
    ajaxRequest(data, (res) => {
        let err = res.error,
            msg = res.message,
            row = res.row;
        // loader.hide();
        if (err) {
            interval = setInterval(() => {
                loader.hide();
                toastAlert(msg, 'error');
                clearInterval(interval);
                return false;
            }, 1000);
            return false;
        }
        interval = setInterval(() => {
            toastAlert(msg);
            $attendance_row.html('');
            $attendance_row.html(row);
            modal_body.html('');
            modal.hide();
            loader.hide();
            clearInterval(interval);
            return false;
        }, 1000);
        return false;
    });
}

const getAttRecord = (mode = 1) => {
    const $att_year_select = $("#att_year_select"),
        $att_month_select = $("#att_month_select"),
        $att_emp_select = (mode != 2) ? $("#att_emp_select") : '',
        $attendance_list_table = (mode != 2) ? $(".attendance_list_table") : $(".attendance_report_table"),
        table = $attendance_list_table.find('tbody'),
        loader = (mode != 2) ? $("#attendance_list_loader") : $("attendance_report_loader");

    var att_emp_select = (mode != 2) ? $att_emp_select.children('option:selected').val() : 0,
        att_month_select = $att_month_select.children('option:selected').val(),
        att_year_select = $att_year_select.children('option:selected').val();

    $att_year_select.removeClass('error_input');
    $att_month_select.removeClass('error_input');
    if (mode != 2) {
        $att_emp_select.removeClass('error_input');
        if (att_emp_select == 0) {
            pointInvalid($att_emp_select);
            toastAlert("Please select an Employee", "error");
            return false;
        }
    }
    if (att_month_select == 0) {
        pointInvalid($att_month_select);
        toastAlert("Please select a Month", "error");
        return false;
    }
    if (att_year_select == 0) {
        pointInvalid($att_year_select);
        toastAlert("Please select a Year", "error");
        return false;
    }
    var data = {
        ajax_action: "GET_ATTENDANCE_RECORD",
        emp: att_emp_select,
        month: att_month_select,
        year: att_year_select,
        mode
    };
    loader.show();
    ajaxRequest(data, (res) => {
        let err = res.error,
            msg = res.message,
            att_tr = res.att_tr;
        loader.hide();
        if (err) {
            toastAlert(msg, 'error');
            return false;
        }
        table.html('');
        table.html(att_tr);
        toastAlert("Fetched Successfully");
        $("#attendance_report_row").find('.table-responsive').css({ height: "400px" });
        $("#list_attendance_row").find('.table-responsive').css({ height: "400px" });
        if (mode != 2) {
            $("#employee_details_row").find('#employee_details_span').html(res.emp_details);
            $("#employee_details_row").show();
        }
        return false;
    });
}

const dateWiseAttRec = () => {
    const $att_date_select = $("#att_date_select"),
        $attendance_list_table = $(".attendance_list_table"),
        table = $attendance_list_table.find('tbody'),
        loader = $("#attendance_list_loader");

    var att_date_select = $att_date_select.val();


    if (isInvalidValue(att_date_select)) {
        pointInvalid($att_date_select);
        toastAlert("You must select a date to get result", "error");
        return false;
    } else {
        $att_date_select.removeClass('error_input');
    }
    loader.show();
    const data = {
        ajax_action: "GET_DATEWISE_ATTENDANCE_RECORD",
        date: att_date_select
    };
    ajaxRequest(data, (res) => {
        let err = res.error,
            msg = res.message,
            att_tr = res.att_tr;
        loader.hide();
        if (err) {
            toastAlert(msg, "error");
            return false;
        }
        table.html('');
        table.html(att_tr);
        toastAlert("Fetched Successfully");
        $("#list_attendance_row").find('.table-responsive').css({ height: "400px" });
        return false;
    });

};

const getChatHistory = (user_id, load = true) => {
    const user_list_row_id = $("#chat_user_items_" + user_id),
        chat_user_list_card = $("#chat_user_list_card"),
        chat_history_card = $("#chat_history_card"),
        chat_card_body = $("#chat_card_body"),
        msg_txt = $("#msg_txt"),
        loader = $("#chat_card_body_loader");
    if ($("#new_msg_alert_" + user_id)) {
        $("#new_msg_alert_" + user_id).remove();
    }
    var user_name = user_list_row_id.html();
    chat_history_card.find(".card-header").html(user_name);
    chat_history_card.show();
    $(".chat_user_items").removeClass("chat_user_selected");
    user_list_row_id.addClass("chat_user_selected");
    CURRENT_CHAT_USER_ID = user_id;
    if (load) {
        loader.show();
    }
    // return false;
    ajaxRequest(data = { ajax_action: "GET_CHAT_HISTOSRY", uid: user_id }, (res) => {
        clog(res);
        let err = res.error,
            msg = res.message,
            html = (res.html) ? res.html : "";
        if (err) {
            loader.hide();
            toastAlert("Unabled to load chat history.", "error");
        } else {
            chat_card_body.html(html);
            if (load) {
                chat_card_body.animate({ scrollTop: chat_card_body.prop("scrollHeight") }, 1000);
                msg_txt.focus();
            }
            loader.hide();
            $("#header_menu_chat_alert").html(res.chat);
        }
    });
};

$("#msg_file_upload_btn").on("click", () => {
    $("#msg_file_upload").click();
});

$("#msg_txt").on('keypress', function(e) {
    if (e.which == 13) {
        // alert("Your Message is: " + $(this).val());
        const loader = $("#msg_loader"),
            $msg = $(this),
            $chat_card_body = $("#chat_card_body");
        var msg = $(this).val();

        loader.show();

        if (isInvalidValue(msg)) {
            $msg.focus();
            toastAlert("Don't worry messaging is working. Just type & send.", "info");
            loader.hide();
            return false;
        }
        const data = {
            ajax_action: "SEND_MESSAGE",
            uid: CURRENT_CHAT_USER_ID,
            msg: msg
        };
        ajaxRequest(data, (res) => {
            let err = res.error,
                msg = res.message,
                html = (res.html) ? res.html : "";
            loader.hide();
            if (err) {
                toastAlert(msg, "error");
                return false;
            }
            toastAlert(msg);
            $msg.val('');
            document.getElementById('chat_card_body').insertAdjacentHTML('beforeend', html);
            $chat_card_body.animate({ scrollTop: $chat_card_body.prop("scrollHeight") }, 1000);
            return false;
        });
    }
});

const fileUpload = () => {
    const loader = $("#msg_loader"),
    $msg_file = $("#msg_file_upload"),
    $chat_card_body = $("#chat_card_body");
    var msg_file = $msg_file.val();



    if (isInvalidValue(msg_file)) {
        toastAlert("You haven't selected any file to send", "warning");
        return false;
    } else {
        // hasFile = 1;
        var allowedFiles = ALLOWED_CHAT_FILE_TYPE;
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(" + allowedFiles.join('|') + ")$");
        if (!regex.test(msg_file.toLowerCase())) {
            var str = allowedFiles.join(', ');
            pointInvalid($msg_file);
            toastr['error']("Please upload files having extensions: " + str + " only.");
            return false;
        }
        // toastAlert("File Uploaded: " + hasFile);
        // return false;
    }
    loader.show();
    let file = $msg_file[0].files[0],
        ajax_request = 'SEND_ATTACHMENT',
        form = new FormData();
    form.append('ajax_action', ajax_request);
    form.append('media_file', file);
    form.append('uid', CURRENT_CHAT_USER_ID);
    const data = form;

    $.ajax({
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = Math.round(((evt.loaded / evt.total) * 100));
                    // $(".progress-bar").width(percentComplete + '%');
                    // $(".progress-bar").html(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        url: DEF_AJAX_URL,
        type: "POST",
        data: form,
        contentType: false,
        processData: false,
        beforeSend: function(xhr) {
            // $('.media_progress').show();
        },
        success: (data) => {
            // Hide the progress bar
            // $('.media_progress').hide();
            // $(".progress-bar").width('0%');
            // $(".progress-bar").html('0%');
            clog(data);
            loader.hide();
            var parseData = IS_CONTENT_TYPE_JSON ? data : JSON.parse(data);
            let msg = parseData.message,
                html = (parseData.html) ? parseData.html : "";
            if (parseData.error) {
                //Some error occurd
                toastr.error(msg);
                return false;
            }
            toastAlert(msg);
            document.getElementById('chat_card_body').insertAdjacentHTML('beforeend', html);
            $chat_card_body.animate({ scrollTop: $chat_card_body.prop("scrollHeight") }, 1000);
            return false;
        },
        error: (xhr, status, error) => {
            clog("AJAX Error");
            clog(xhr);
            clog(status);
            clog(error);
            $('.loader-overlay').hide();
            loader.hide();
            if (xhr.readyState == 0) {
                toastr.error("Opps! Could not connect to the server. Please try again.");
            } else if (xhr.readyState == 500) {
                toastr.error("Opps! Forbidden.");
            } else if (xhr.readyState == 404) {
                toastr.error("Not found Error.");
            }
        }
    });
};

$("#msg_file_upload").on("change", () => {
    fileUpload();
});

// $("#address_samem_check")
if (document.getElementById("address_samem_check")) {
    document.getElementById("address_samem_check").addEventListener('change', e => {
        const current_address = $("#current_address"),
            permanent_address = $("#permanent_address");

        if (e.target.checked === true) {
            permanent_address.val(current_address.val());
        }
        if (e.target.checked === false) {

        }
    });

}

$("#domestic_clients_action_btn").on("click", ()=>{
    var data_action = $("#domestic_clients_action_btn").attr("data-action"),
    data_id = $("#domestic_clients_action_btn").attr("data-id");

    const $business_phone = $("#business_phone"),
    $current_status = $("#current_status"), //select box
    $business_details = $("#business_details"), //summernote
    loader = $("#domestic_mod_loader"),
    $dc_status_historry_tab = $("#dc_status_historry_tab"),
    $domestic_clients_action_btn = $("#domestic_clients_action_btn"),
    modal = $("#dc_status_historry_modal");

    var business_phone = $business_phone.val(),
    current_status = $current_status.children("option:selected").val(),
    business_details = $business_details.val();

    // loader.show();
    $business_phone.removeClass('error_input');
    $current_status.removeClass('error_input');
    $business_details.removeClass('error_input');

    if (isInvalidValue(business_phone)) {
        pointInvalid($business_phone);
        toastAlert("Phone Number cannot be left empty", "error");
        return false;
    } else {
        if (isNaN(removeCountryCode(business_phone))) {
            pointInvalid($business_phone);
            toastAlert("Please Enter a valid phone number", "error");
            return false;
        }
    }

    if ((isInvalidValue(current_status)) || (current_status == 0)) {
        pointInvalid($current_status);
        toastAlert("Please select a valid status", "error");
        return false;
    }
    if (isInvalidValue(business_details)) {
        pointInvalid($business_details);
        toastAlert("Please write some details about the Business", "error");
        return false;s
    }

    loader.show();

    var data = {
        ph: removeCountryCode(business_phone),
        st: current_status,
        dt: business_details,
        ajax_action: (data_action == 1) ? "ADD_DOMESTIC_CLIENT" : "UPDATE_DOMESTIC_CLIENT",
        id: data_id
    };

    ajaxRequest(data, (res)=>{
        clog(res);
        let exists = res.exists,
            msg = res.message,
            err = res.error;
        loader.hide();
        if (err) {
            toastAlert(msg, "error");
            return false;
        }
        toastAlert(msg);
        if (data_action == 1) {
            setInterval(() => {
                location.reload();
            }, 1000);
        } else {
            checkClientByPhone();
        }
        return false;
        if (exists) {}
    });
});

// $("#business_phone").keypress(function(){
const checkClientByPhone = () => {
    const $business_phone = $("#business_phone"),
    $dc_status_historry_tab = $("#dc_status_historry_tab"),
    $business_details = $("#business_details"),
    $current_status = $("#current_status"),
    $domestic_clients_action_btn = $("#domestic_clients_action_btn"),
    modal = $("#dc_status_historry_modal");

    var business_phone = $business_phone.val();

    if (isInvalidValue(business_phone)) {
        $dc_status_historry_tab.html('');
        modal.find('.modal-content').find('.modal-body').html('');
        $business_details.text('');
        $(".summernote").summernote("code", '');
        $current_status.val(0);
        $domestic_clients_action_btn.text("Add")
        $domestic_clients_action_btn.attr("data-action", 1);
        $domestic_clients_action_btn.removeAttr('disabled');
        return false;
    } else {

        if (business_phone.length > 9) {
        
            if (isNaN(removeCountryCode(business_phone))) {
                return false;
            } else {
                let data = {
                    ajax_action: "CHECK_DC_PHONE_NO",
                    ph: removeCountryCode(business_phone)
                };
                $domestic_clients_action_btn.attr('disabled','disabled');
                ajaxRequest(data, (res)=>{
                    let exists = res.exists,
                        msg = res.message,
                        err = res.error;
                    $domestic_clients_action_btn.removeAttr('disabled');
                    if (exists) {
                        $dc_status_historry_tab.html(res.client_action_txt);
                        modal.find('.modal-content').find('.modal-body').html(res.client_actions);
                        $business_details.text(res.bdetails);
                        $(".summernote").summernote("code", res.bdetails);
                        $current_status.val(res.bstatus);
                        $domestic_clients_action_btn.text("Update");
                        $domestic_clients_action_btn.attr("data-action", 2);
                        $domestic_clients_action_btn.attr("data-id", res.b_id);
                        if ((res.bstatus > 3) && (CURRENT_USER_TYPE != MANAGER)) {
                            $domestic_clients_action_btn.attr('disabled','disabled');
                        }
                        // toastAlert(msg);
                    } else {
                        $dc_status_historry_tab.html('');
                        modal.find('.modal-content').find('.modal-body').html('');
                        $business_details.text('');
                        $(".summernote").summernote("code", '');
                        $current_status.val(0);
                        $domestic_clients_action_btn.text("Add")
                        $domestic_clients_action_btn.attr("data-action", 1);
                        $domestic_clients_action_btn.removeAttr('disabled');
                    }
                });
            }
        } else {
            $dc_status_historry_tab.html('');
            modal.find('.modal-content').find('.modal-body').html('');
            $business_details.text('');
            $(".summernote").summernote("code", '');
            $current_status.val(0);
            $domestic_clients_action_btn.text("Add")
            $domestic_clients_action_btn.attr("data-action", 1);
            $domestic_clients_action_btn.removeAttr('disabled');
            // return false;
        }
    }
};

const ViewClientStatusHistory = () =>{
    const modal = $("#dc_status_historry_modal");
    modal.modal('show');
};
function JoysEye() {
    //Declare variables
    var input, filter, ul, listElements, i, txtValue;
    input = document.getElementById("myInput");
    filter = input.value.toUpperCase();
    ul = document.getElementById("chat_usertype_list");
    listElements = ul.getElementsByTagName("li");
  
    // Loop through all list items, and hide those who don't match the search query
    for (i = 0; i < listElements.length; i++) {
      txtValue = listElements[i].textContent || listElements[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        listElements[i].style.display = "";
      } else {
        listElements[i].style.display = "none";
      }
    }
  }