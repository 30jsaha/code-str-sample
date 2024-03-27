<?php 
function printContent() 
{
    $mod_ph = "";
    if (isset($_REQUEST['ph'])) {
        $mod_ph = altRealEscape($_REQUEST['ph']);
    }
    $client_status = '<option selected disabled value="0">--- Select Status ---</option>';
    // for ($i=0; $i < count(DOMESTIC_CLIENTS_STATUSES); $i++) { 
    //     $client_status = '<option value="'.(($i*1)+1).'">--- Select Status ---</option>';
    // }
    foreach (DOMESTIC_CLIENTS_STATUSES as $k => $v) {
        $dis = 'disabled';
        if ($k<DEAL_CLOSED) {
            if ($_SESSION[USER_TYPE] != MANAGER) {
                $dis = '';
            }
        }
        if ($_SESSION[USER_TYPE] == MANAGER) {
            $client_status .= '<option value="'.$k.'">'.$v.'</option>';
        } else {
            $client_status .= '<option '.$dis.' value="'.$k.'">'.$v.'</option>';
        }
    }
?>
<h4 class="text-center">Add / Modify Domestic Clients</h4>

<div class="card mt-5">
    <div class="card-body" style="padding: 25px;">
    <?php getSpinner(true, "domestic_mod_loader"); ?>
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label class="form_label" for="business_phone">Business Phone</label>
                <!-- <div class="search-bar" id="searchBar"> -->
                    <input type="text" class="form-control" value="<?=$mod_ph?>" id="business_phone" onkeyup="checkClientByPhone();"/>
                    <!-- <i class="fas fa-search position"></i> -->
                <!-- </div> -->
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label class="form_label" for="current_status">Select Current Status</label> <?=getAsterics();?>
                <select class="form-control" id="current_status">
                    <?=$client_status;?>
                </select>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12 col-lg-12 col-sm-12">
                <label class="form_label" for="business_details">Business Details</label> <?=getAsterics();?>
                <textarea class="form-control summernote" id="business_details"></textarea>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-6 col-lg-6 col-sm-12 text-left" id="dc_status_historry_tab">
                
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                <button type="button" class="btn custom_btn" data-id="0" data-action="1" id="domestic_clients_action_btn">Add</button>
            </div>
        </div>
    </div>
</div>



<div class="modal animated backInDown" id="dc_status_historry_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Client History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

<?php
}
?>