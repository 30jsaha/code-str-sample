<?php 
function printContent() {

$getdname = getData(Table::DESIGNATIONS, [
    DESIGNATIONS::ID,
    DESIGNATIONS::DESIGNATION_TITLE,
    DESIGNATIONS::RESPONSIBILITIES,
    DESIGNATIONS::EXPERIENCE_REQUIRED,
    DESIGNATIONS::ACTIVE,
    DESIGNATIONS::LAST_UPDATE_DATE
],[
    DESIGNATIONS::CLIENT_ID => $_SESSION[CLIENT_ID],
    DESIGNATIONS::STATUS => ACTIVE_STATUS
]);
$tr = '';
if (count($getdname) > 0) {
    foreach ($getdname as $key => $des_value) {
     $sl = ($key + 1);
     $dname = altRealEscape($des_value[DESIGNATIONS::DESIGNATION_TITLE]);
     $dres = (!empty($des_value[DESIGNATIONS::RESPONSIBILITIES])) ? altRealEscape($des_value[DESIGNATIONS::RESPONSIBILITIES]) : "N/A";
     $dexp = (!empty($des_value[DESIGNATIONS::EXPERIENCE_REQUIRED])) ? altRealEscape($des_value[DESIGNATIONS::EXPERIENCE_REQUIRED]) : "N/A";
     $dact = (($des_value[DESIGNATIONS::ACTIVE]) == 1) ? "checked" : "";
     $action = '
     <div class="row">
        <!--<div class="col-6 text-success" style="padding: 5px;"><i class="far fa-edit"></i></div>-->
        <div class="col-12 text-danger cursor-pointer" onclick="initiateDelete('. $des_value[DESIGNATIONS::ID] .', \'designation\')" style="padding: 5px;"><i class="fas fa-trash-alt"></i></div>
     </div>
     ';

     $tr .= '<tr id="designation_'.$des_value[DESIGNATIONS::ID].'">
     <td>'.$sl.'</td>
     <td>'.$dname.'</td>
     <td>'.$dres.'</td>
     <td>'.$dexp.'</td>
     <td>'.$action.'</td>
 </tr>';
    }
} else {
    $tr = '<tr class="animated fadeInDown">
    <td colspan="5">
        <div class="alert alert-danger" role="alert">
            No Designations found ! <a style="text-decoration: underline; font-weight: bold;" href="url:void();" onclick="changeNavigateBtn(\'designation\');">Click Here</a> to add Employees First.
        </div>
    </td>
    </tr>';
}
?>
<div class="row">
    <div class="col-sm-10 col-md-10 col-lg-10 text-right">
        <h2 class="text-center">Employee Designations</h2>
    </div>
    <div class="col-sm-2 col-md-2 col-lg-2 text-right" id="list_nav_btn">
        <button class="btn btn-primary" name="list" onclick="changeNavigateBtn('designation');">View List</button>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "desig_loader");?>
    <div class="card-body" style="padding: 15px;">
        <div class="row" id="add_designation_row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <h5 class="text-center font-weight-bold">Add / Update Designations</h5>
                <div class="row mt-4">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-outline">
                            <label class="form_label" for="desig_name">Designation Title</label>
                            <input type="text" id="desig_name" class="form-control" placeholder="Designation Name" onkeypress="enterEventListner($(this), $('#designation_submit'));"/>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-outline">
                            <label class="form_label" for="desig_responcibilities">Responsibilities</label>
                            <input type="text" class="form-control" id="desig_responcibilities" onkeypress="enterEventListner($(this), $('#designation_submit'));">
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="form-outline">
                            <label class="form_label" for="desig_exp">Experience Required</label>
                            <input type="text" id="desig_exp" class="form-control" placeholder="Format: XX Years XX Months" onkeypress="enterEventListner($(this), $('#designation_submit'));"/>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-lg-6 col-md-6 col-sm-12 noselect">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" checked class="custom-control-input" id="designation_active">
                            <label class="custom-control-label text-success" for="designation_active">Active</label>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12 text-right">
                        <button type="button" class="btn btn-success sbmt" data-action="designation" id="designation_submit">
                            Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="view_designation_row" style="display: none;">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center designation_table" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase;">
                                <th>SL.</th>
                                <th>Designation Title</th>
                                <th>Designation Responsibility</th>
                                <th>Required Experience Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?=$tr;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>