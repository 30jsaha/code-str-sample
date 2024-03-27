<?php 
function printContent() {
?>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 text-right">
        <h4 class="text-center font-weight-bold">
            <?php 
            $heading = "";
            switch ($_SESSION[USER_TYPE]) {
                case ADMIN:
                case MANAGER:
                case EMPLOYEE:
                    $heading = "Apply Leaves From Here";
                    break;
            }
            echo $heading;
            ?>
        </h4>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "leave_loader");?>
    <div class="card_body" style="padding: 15px;">
        <div id="apply_leave_row">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <div class="form-outline">
                        <label class="form_label" for="leave_month">Select Month</label><?=getAsterics();?>
                        <select id="leave_month" class="form-control">
                            <!-- <option value='0' selected disabled>--Select Month--</option> -->
                            <?php 
                                $month = getToday(false,'m');
                                for ($i=0; $i <= count(ALL_MONTHS_NAME); $i++) { 
                                    $m = ($i+1);
                                    $selected = ($m == $month) ? 'selected' : '';
                                    echo "<option ".$selected." value='". $m ."'>".ALL_MONTHS_NAME[$m]."</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="leave_year">Select Year</label><?=getAsterics();?>
                    <select id="leave_year" class="form-control">
                        <option value='0' selected disabled>--Select Year--</option>
                        <?php 
                            $year = getToday(false,'Y');
                            for ($i=0; $i <= 1; $i++) { 
                                $y = ($year + $i);
                                $selected = ($y == getToday(false, 'Y')) ? 'selected' : '';
                                echo "<option ".$selected." value='". $y ."'>".$y."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="leave_dates">Write Leave Dates</label> <?=getAsterics();?>
                    <input type="text" class="form-control" id="leave_dates" placeholder="17,18,20,..."/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6 col-lg-6 col-12">
                    <label class="form_label" for="leave_subject">Write Leave Subject</label> <?=getAsterics();?>
                    <input type="text" class="form-control" id="leave_subject" placeholder="Leave for sickness"/>
                </div>
                <div class="col-md-12 col-lg-12 col-12">
                    <label class="form_label" for="leave_matter">Write Leave Matter</label> <?=getAsterics();?>
                    <!-- <input type="text" class="form-control" id="leave_matter" placeholder="Paste the letter (ctrl + v)"/> -->
                    <textarea class="form-control summernote" id="leave_matter" placeholder="Paste the letter (ctrl + v)"></textarea>
                </div>
            </div>
            <h6 class="text-left mt-4 font-weight-bold" style="padding: 15px;">Upload Reference Document</h6>
            <div class="row mt-3">
                <div class="col-md-7 col-lg-7 col-12">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="leave_ref_doc" name="file_upload" onchange="fileNamePreview($(this));" />
                        <label class="custom-file-label" for="leave_ref_doc">Choose file</label>
                    </div>
                    <div class="progress mt-4 media_progress" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"> 75% </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-5 col-12">
                    <span style="font-weight: bold; display:none;" id="file_name_preview">Uploaded File : <span class="text-success"></span></span>
                    <button type="button" class="close" aria-label="Close" id="file_clear_btn" style="display: none;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <br />
                    <small class="text-muted">
                        <i class="text-danger">*</i>&nbsp; Please merge all photos and doc files in one pdf. Multiple files are not allowed.
                    </small>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="button" id="apply_leave">Apply</button>
                </div>
            </div>
        </div>
        <div id="view_leave_row" style="display: none;">
            
        </div>
    </div>
</div>

<?php } ?>