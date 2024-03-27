<?php 
function printContent() 
{
    $getDC = getData(Table::DOMESTIC_CLIENTS_DATA, [
        DOMESTIC_CLIENTS_DATA::ID,
        DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO,
        DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS,
        DOMESTIC_CLIENTS_DATA::CREATION_DATE,
        DOMESTIC_CLIENTS_DATA::STATUS
    ], [
        DOMESTIC_CLIENTS_DATA::CLIENT_ID => $_SESSION[CLIENT_ID],
        DOMESTIC_CLIENTS_DATA::ACTIVE => 1
    ]);
    $dc_tbody = '';
    if (count($getDC)>0) {
        foreach ($getDC as $k => $v) {
            $client_action_txt = $lastUpdateDate = '';
            $getClientHistory = getData(Table::DOMESTIC_CLIENTS_ACTIONS, [
                DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID,
                DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::PREVIOUS_STATUS,
                DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE,
                DOMESTIC_CLIENTS_ACTIONS::INFOTXT,
                DOMESTIC_CLIENTS_ACTIONS::ID,
            ], [
                DOMESTIC_CLIENTS_ACTIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
                DOMESTIC_CLIENTS_ACTIONS::DC_ID => $v[DOMESTIC_CLIENTS_DATA::ID]
            ], [], [], [DOMESTIC_CLIENTS_ACTIONS::ID], "DESC");
            if (count($getClientHistory)>0) {
                foreach ($getClientHistory as $hk => $hv) {
                    $getusername = getData(Table::USERS, [Users::NAME], [Users::ID => $hv[DOMESTIC_CLIENTS_ACTIONS::ACTION_USER_ID], Users::CLIENT_ID => $_SESSION[CLIENT_ID]]);
                    $ac_username = $getusername[0][Users::NAME];
                    if ($hk == 0) {
                        $ch_st = DOMESTIC_CLIENTS_STATUSES[$hv[DOMESTIC_CLIENTS_ACTIONS::CHANGED_STATUS]];
                        $client_action_txt = '<b class="text-secondary">Last: <i class="text-info">'.$ch_st.'</i> &nbsp;By: <i class="text-primary">'.$ac_username.'</i></b>';
                        $lastUpdateDate = getFormattedDateTime($hv[DOMESTIC_CLIENTS_ACTIONS::CREATION_DATE], LONG_DATE_TIME_FORMAT);
                    }
                }
            }

            $ph = ($v[DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO] != "") ? '<a style="text-decoration:underline;" href="'.HOST_URL.'mod-domestic-clients/?ph='. altRealEscape($v[DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO]) .'">'.altRealEscape($v[DOMESTIC_CLIENTS_DATA::BUSINESS_PHONE_NO]).'</a>' : EMPTY_VALUE;
            $bd = ($v[DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS] != "") ? cleanText(html_entity_decode($v[DOMESTIC_CLIENTS_DATA::BUSINESS_DETAILS])) : EMPTY_VALUE;
            $ad = getFormattedDateTime($v[DOMESTIC_CLIENTS_DATA::CREATION_DATE], LONG_DATE_TIME_FORMAT);
            $dc_tbody .= '<tr id="domestic_client_'.$v[DOMESTIC_CLIENTS_DATA::ID].'">
                <td>'.($k+1).'</td>
                <td>'.$ph.'</td>
                <td>'.$bd.'</td>
                <td>'.$client_action_txt.'</td>
                <td>'.$lastUpdateDate.'</td>
                <td>'.$ad.'</td>
            </tr>';
        }
    }
?>

<h4 class="text-center">Domestic Clients List</h4>

<div class="card mt-5">
    <div class="card-body" style="padding: 25px;">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center dc_list_table" id="example" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase;">
                                <th>Sl.</th>
                                <th>Phone</th>
                                <th>Business Details</th>
                                <th>Managing User</th>
                                <th>Update Date</th>
                                <th>Add Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-center"><?=$dc_tbody?></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php

}