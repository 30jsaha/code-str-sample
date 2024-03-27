<?php
function printContent()
{
?>

    <div class="row">
        <div class="col-6 text-right">
            <h2 class="text-right" style="text-decoration: underline;">SETTINGS</h4>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 text-right">
            <span style="font-weight: bold;">Last Updated On : <span class="text-primary">Friday 3rd of September 2021 04:47 pm</span></span>
        </div>
        <div class="col-6 text-right">
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body">
            <?php getSpinner(true,'SettingLoader'); ?>
            <!-- <div class="loader-overlay" id="SettingLoader" style="display: none;">
                <div class="spinner-border" role="status" id="" style="">
                    <span class="sr-only">Loading...</span>
                </div>
            </div> -->
            <h4 class="mt-3">Account Settings</h4>
            <small id="passwordHelpInline" class="text-muted">
                <i class="text-danger">*</i>&nbsp; Passwords Must be 8-20 characters long.
            </small>
            <div class="row mt-3">
                <div class="col-lg-1 col-md-1 col-sm-12">
                    <!-- <label for="user_old_pass" class="noselect" style="width: max-content;">Old Password</label> -->
                    <label for="user_old_pass" class="noselect" style="font-size: 12px !important; font-weight: 600;">Old Password</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-inline">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text togglePassword" onclick="togglePassword($(this))">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control border-tr-radius-0 border-br-radius-0" id="user_old_pass" autocomplete="new-password" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12">
                    <label for="user_pass" class="noselect" style="font-size: 12px !important; font-weight: 600;">New Password</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-inline">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text togglePassword" onclick="togglePassword($(this))">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control border-tr-radius-0 border-br-radius-0" id="user_pass" autocomplete="new-password" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-12">
                    <label for="user_cpass" class="noselect" style="font-size: 12px !important; font-weight: 600;">Confirm New Password</label>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-12">
                    <div class="form-inline">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text togglePassword" onclick="togglePassword($(this))">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </span>
                            </div>
                            <input type="password" class="form-control border-tr-radius-0 border-br-radius-0" id="user_cpass" autocomplete="new-password" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-6 col-md-6 col-12 text-left">
                    <span class="text-danger" id="err_sp" style="font-size: 14px; display:none;"></span>
                </div>
                <div class="col-lg-6 col-md-6 col-12 text-right">
                    <button class="btn btn-primary" id="ChangeUserPassword">Change Password</button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>