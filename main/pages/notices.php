<?php 
function printContent () {
    $cols = [
        NOTICES::ID,
        NOTICES::NOTICE_SUBJECT,
        NOTICES::NOTICE_FILE,
        NOTICES::ACTIVE,
        NOTICES::CREATION_DATE,
        NOTICES::LAST_ACTIVE_DATE
    ];
    $wh = [
        NOTICES::CLIENT_ID => $_SESSION[CLIENT_ID],
        NOTICES::STATUS => ACTIVE_STATUS
    ];
    $colspan = 6;
    switch ($_SESSION[USER_TYPE]) {
        case EMPLOYEE:
        case MANAGER:
            $wh[NOTICES::ACTIVE] = 1;
            $colspan = 4;
            break;
    }
    $getNoticeData = getData(Table::NOTICES, $cols, $wh);
    $notice_tr = '';
    if (count($getNoticeData)>0) {
        foreach ($getNoticeData as $key => $nv) {
            $new = '';
            if ((((getToday(false, 'd')) - (getFormattedDateTime($nv[NOTICES::CREATION_DATE],'d'))) <= 3) && (getFormattedDateTime($nv[NOTICES::LAST_ACTIVE_DATE],'m') == (getToday(false, 'm'))) && (getFormattedDateTime($nv[NOTICES::LAST_ACTIVE_DATE],'Y') == (getToday(false, 'Y')))) {
                $new = '&NonBreakingSpace;<span class="badge badge-danger blinking" style="vertical-align: super;">New</span>';
            }
            $sl_no = ($key + 1).$new;
            $notice_sub = (!empty($nv[NOTICES::NOTICE_SUBJECT]) || ($nv[NOTICES::NOTICE_SUBJECT] != "")) ? altRealEscape($nv[NOTICES::NOTICE_SUBJECT]) : EMPTY_VALUE;
            $notice_file = (!empty($nv[NOTICES::NOTICE_FILE])) ? '<a href="'. UPLOADED_NOTICE_URL.$nv[NOTICES::NOTICE_FILE] .'" target="_blank" class="download">Click Here</a>' : EMPTY_VALUE;
            $nact = (($nv[NOTICES::ACTIVE]) == 1) ? "checked" : "";
            $active_text = (($nv[NOTICES::ACTIVE]) == 1) ? '<label class="custom-control-label text-success form_label" for="notice_active_'.$nv[NOTICES::ID].'">Published</label>' : '<label class="custom-control-label text-warning form_label" for="notice_active_'.$nv[NOTICES::ID].'">Draft</label>';
            $active = '<div class="custom-control custom-switch noselect">
            <input type="checkbox" '.$nact.' class="custom-control-input" id="notice_active_'.$nv[NOTICES::ID].'" onclick="changeActiveStatus(\'notice\','.$nv[NOTICES::ID].', \'notice_loader\');">
            '.$active_text.'
        </div>';
            $action = '
            <div class="row" style="display:flex; justify-content: center;">
                <div class="col-6 text-danger" onclick="initiateDelete('. $nv[NOTICES::ID] .', \'notice\')"><i class="fas fa-trash-alt cursor-pointer"></i></div>
            </div>
            ';
            $date = (!empty($nv[NOTICES::CREATION_DATE])) ? getFormattedDateTime($nv[NOTICES::CREATION_DATE], LONG_DATE_FORMAT) : EMPTY_VALUE;
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                case SADMIN:
                    $notice_tr .= '<tr id="notice_'.$nv[NOTICES::ID].'">
                        <td>'.$sl_no.'</td>
                        <td>'.$notice_sub.'</td>
                        <td>'.$notice_file.'</td>
                        <td>'.$active.'</td>
                        <td>'.$date.'</td>
                        <td>'.$action.'</td>
                    </tr>';
                    break;
                case EMPLOYEE:
                case MANAGER:
                    $notice_tr .= '<tr id="notice_'.$nv[NOTICES::ID].'">
                        <td>'.$sl_no.'</td>
                        <td>'.$notice_sub.'</td>
                        <td>'.$notice_file.'</td>
                        <td>'.getFormattedDateTime($nv[NOTICES::LAST_ACTIVE_DATE],LONG_DATE_TIME_FORMAT).'</td>
                    </tr>';
                    break;
            }
        }
    } else {
        $notice_tr = '<tr class="animated fadeInDown">
    <td colspan="'.$colspan.'">
        <div class="alert alert-danger" role="alert">
            No Notices found !
        </div>
    </td>
</tr>';
    }
 
switch ($_SESSION[USER_TYPE]) {
    case ADMIN:
?>
    <div class="row">
        <div class="col-sm-10 col-md-10 col-lg-10 text-right">
            <h4 class="text-center font-weight-bold">Notice Management</h4>
        </div>
        <div class="col-sm-2 col-md-2 col-lg-2 text-right" id="list_nav_btn">
            <button class="btn btn-primary" name="list" onclick="changeNavigateBtn('notice');">View List</button>
        </div>
    </div>
<?php
        break;
    case EMPLOYEE:
    case SADMIN:
    case MANAGER:
?>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 text-right">
            <h4 class="text-center font-weight-bold">Notice Board</h4>
        </div>
    </div>
<?php
        break;
}
?>

<div class="card mt-5">
    <?=getSpinner(true, "notice_loader")?>
    <div class="card_body" style="padding: 15px;">
        <?php if(($_SESSION[USER_TYPE] != EMPLOYEE) && ($_SESSION[USER_TYPE] != SADMIN) && ($_SESSION[USER_TYPE] != MANAGER)): ?>
        <div id="upload_notice_row">
        <div class="row">
            <div class="col-md-8 col-lg-8 col-sm-12">
                <label class="form_label" for="notice_subject">Notice Subject</label>
                <input type="text" id="notice_subject" class="form-control" />
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12">
                <div class="form-outline">
                    <label class="form_label" for="notice_status">Select Status</label>
                    <select id="notice_status" class="form-control">
                        <option value='0' selected disabled>--Publish / Draft--</option>
                        <option value='1' >Publish</option>
                        <option value='2' >Draft</option>
                        
                    </select>
                </div>
            </div>
        </div>
            <h6 class="text-left mt-4 font-weight-bold">Upload Notice here</h6>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12 text-left">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="notice_file" name="file_upload" onchange="fileNamePreview($(this));" />
                    <label class="custom-file-label" for="notice_file">Choose file</label>
                </div>
                <div class="progress mt-4 media_progress" style="display: none;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"> 75% </div>
                </div>
                <div class="pt-3">
                    <button class="btn btn-outline-secondary text-success" type="button" id="upload_notice">Upload</button>
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
        <div id="view_notice_row" <?php if(($_SESSION[USER_TYPE] != EMPLOYEE) && ($_SESSION[USER_TYPE] != SADMIN) && ($_SESSION[USER_TYPE] != MANAGER)): ?> style="display: none;" <?php endif; ?>>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped table-hover text-center notice_table" style="font-size:14px;">
                            <thead class="text-center table-warning">
                                
                                <?php 
                                switch ($_SESSION[USER_TYPE]) {
                                    case EMPLOYEE:
                                    case MANAGER:
                                    ?>
                                    <tr style="text-transform: uppercase;">
                                        <th>SL.</th>
                                        <th>Subject</th>
                                        <th>Notice File</th>
                                        <th>Uploaded On</th>
                                    </tr>
                                    <?php
                                        break;
                                    case ADMIN:
                                    case SADMIN:
                                    ?>
                                    <tr style="text-transform: uppercase;">
                                        <th>SL.</th>
                                        <th>Subject</th>
                                        <th>Notice File</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                    <?php
                                        break;
                                }
                                ?>
                            </thead>
                            <tbody>
                                <?=$notice_tr;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>