<?php 
function printContent ()
{
    // rip($_SESSION);
    // echo $_SESSION[SEMSG];
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
                                $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">'.$uv[Users::NAME].$new.'</li>';
                                $user_list .= '</ul>';
                            }
                        }
                    }
                    $user_list .= '</li>';
                }
                break;
            case ADMIN:
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
                            $user_list .= '<li class="list-group-item chat_user_items" id="chat_user_items_'.$uv[Users::ID].'" onclick="getChatHistory('.$uv[Users::ID].');">'.$uv[Users::NAME].$new.'</li>';
                            $user_list .= '</ul>';
                        }
                    }
                }
                $user_list .= '</li>';
                break;
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
            case EMPLOYEE:
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
?>
<h4 class="text-center">Welcome to the Chat Room</h4>

<div class="card mt-5">
    <div class="card-body" style="padding: 25px;">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="card" id="chat_user_list_card">
                    <div class="card-header bg-warning">
                        Select Users to Chat
                    </div>
                    <div class="card-body user_list_card_body">
                        <?=$user_list?>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <div class="card" id="chat_history_card">
                    <div class="card-header bg-success">
                        Name of the User
                    </div>
                    <?=getSpinner(true, "chat_card_body_loader");?>
                    <div class="card-body chat_card_body" id="chat_card_body">
                        <div class="row single_msg_row">
                            <div class="col-6 single_msg_first_col">
                                <div class="jumbotron jumbotron-fluid single_chat">
                                    <div class="container">
                                        <p class="lead display-8">test</p>
                                        <br />
                                        <span class="chat_date_time"><small><em><?=getToday(false, LONG_DATE_TIME_FORMAT);?></em></small></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 single_msg_second_col"></div>
                        </div>
                        <div class="row single_msg_row">
                            <div class="col-6 single_msg_first_col">&nbsp;</div>
                            <div class="col-6 single_msg_second_col">
                                <div class="jumbotron jumbotron-fluid single_chat">
                                    <div class="container">
                                        <p class="lead display-8">test</p>
                                        <br />
                                        <span class="chat_date_time"><small><em><?=getToday(false, LONG_DATE_TIME_FORMAT);?></em></small></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="file" id="msg_file_upload" class="form-control d-none"/>
                        <div class="input-group mb-3">
                            <?=getInlineSpinner("msg_loader");?>
                            <input type="text" class="form-control" placeholder="Type..." value="" aria-label="msg_type" aria-describedby="basic-addon1" id="msg_txt">
                            <div class="input-group-prepend">
                                <span class="input-group-text cursor-pointer" id="msg_file_upload_btn"><i class="fas fa-paperclip"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php } ?>