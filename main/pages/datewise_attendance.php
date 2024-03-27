<?php 
function printContent() {
?>
<h2 class="text-center">Datewise Attendance</h2>

<div class="card mt-5">
    <?=getSpinner(true, "attendance_list_loader");?>
    <div class="card_body" style="padding: 15px;">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <label for="att_date_select" class="form_label">Select Date</label>
                <input type="date" class="form-control" id="att_date_select" value="<?=getToday(false)?>"/>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12">
                <button class="btn btn-success" style="margin-top: 30px;" onclick="dateWiseAttRec();" type="button">Submit</button>
            </div>
        </div>
        <div class="row mt-2" id="list_attendance_row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center attendance_list_table" style="font-size:14px;">
                        <thead class="text-center table-warning">
                            <tr style="text-transform: uppercase; font-size: 12px;">
                                <th class="cursor-pointer">SL.</th>
                                <th class="cursor-pointer">Employee Details</th>
                                <th class="cursor-pointer">Reporting Time</th>
                                <th class="cursor-pointer">Log Off Time</th>
                                <th class="cursor-pointer">Working Hours</th>
                                <th class="cursor-pointer">Late Mints</th>
                                <th class="cursor-pointer">Early Log Off Reason</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 12px;">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php } ?>