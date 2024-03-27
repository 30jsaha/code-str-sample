<?php
function printContent()
{
    $invoice_no = $inv_count_no = (int)01;
    $company_address = $company_gstin = $company_bank_ac_no = $company_bank_ac_name = $company_ifsc_code = "";
    $getInvoiceNo = getData(Table::INVOICE,[
        INVOICE::INVOICE_COUNT_NUMBER
    ],[
        INVOICE::CLIENT_ID => $_SESSION[CLIENT_ID],
        INVOICE::INVOICE_MONTH => getToday(false,'m'),
        INVOICE::INVOICE_YEAR => getToday(false,'Y'),
        INVOICE::STATUS => ACTIVE_STATUS
    ],[],[],[INVOICE::INVOICE_COUNT_NUMBER],"DESC",[0,1]);
    $sc = getData(Table::CLIENT, [
        Client::ADDRESS,
        Client::CITY,
        Client::DISTRICT,
        Client::STATE,
        Client::PIN_CODE,
        Client::GSTIN_NO,
        Client::ACCOUNT_NUMBER,
        Client::ACCOUNT_HOLDER_NAME,
        Client::IFSC_CODE
    ], [
        Client::CLIENT_ID => $_SESSION[CLIENT_ID]
    ]);
    if (count($getInvoiceNo)>0) {
        $inv_count_no = (((int)$getInvoiceNo[0][INVOICE::INVOICE_COUNT_NUMBER]) + 1);
    }
    $invoice_no = getToday(false,'Y').((strlen(getToday(false,'m')) <2 ) ? '0'.getToday(false,'m') : getToday(false,'m')).'/'.((strlen($inv_count_no) <2 ) ? '0'.$inv_count_no : $inv_count_no);
    $pay_mode_options = '<option value="0" selected disabled>--- Select Payment Mode ---</option>';
    if (count(INVOICE_PAY_MODES)>0) {
        foreach (INVOICE_PAY_MODES as $k => $v) {
            $pay_mode_options .= '<option value="'.$k.'">'. $v .'</option>';
        }
    }
    if (count($sc)>0) {
        $cdata = $sc[0];
        $company_address = (!empty($cdata[Client::ADDRESS])) ? altRealEscape($cdata[Client::ADDRESS]) : '';
        $company_address .= ((!empty($cdata[Client::CITY])) && ($company_address != '')) ? ', '.altRealEscape($cdata[Client::CITY]) : '';
        $company_address .= ((!empty($cdata[Client::DISTRICT])) && ($company_address != '')) ? ', '.altRealEscape($cdata[Client::DISTRICT]) : '';
        $company_address .= ((!empty($cdata[Client::STATE])) && ($company_address != '')) ? ', '.altRealEscape($cdata[Client::STATE]) : '';
        $company_address .= ((!empty($cdata[Client::PIN_CODE])) && ($company_address != '')) ? ', '.altRealEscape($cdata[Client::PIN_CODE]) : '';
        $company_gstin = (!empty($cdata[Client::GSTIN_NO])) ? altRealEscape($cdata[Client::GSTIN_NO]) : '';
        $company_bank_ac_no = (!empty($cdata[Client::ACCOUNT_NUMBER])) ? altRealEscape($cdata[Client::ACCOUNT_NUMBER]) : '';
        $company_bank_ac_name = (!empty($cdata[Client::ACCOUNT_HOLDER_NAME])) ? altRealEscape($cdata[Client::ACCOUNT_HOLDER_NAME]) : '';
        $company_ifsc_code = (!empty($cdata[Client::IFSC_CODE])) ? altRealEscape($cdata[Client::IFSC_CODE]) : '';


    }
?>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 text-right">
        <h4 class="text-center font-weight-bold">Generate Invoice</h4>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "invoice_loader")?>
    <div class="card-body" style="padding: 15px;">
    <form name="billing_form" id="billing_form" action="<?php echo HOST_URL; ?>generated-invoice/?save=1" method="POST">
        <input type="hidden" style="visibility: hidden; display:none;" value="<?=$inv_count_no?>" name="inv_count_no" id="inv_count_no" />
        <fieldset class="fldset">
            <legend>Invoice Details</legend>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <div class="form-outline">
                        <label class="form_label" for="inv_no">Invoice No.</label><?=getAsterics();?>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1"><?=INVOICE_NUMBER_PREFIX?></span>
                        </div>
                        <input type="text" class="form-control" placeholder="<?=$invoice_no?>" aria-label="Invoice No" aria-describedby="basic-addon1" id="inv_no" name="inv_no" value="<?=$invoice_no?>" />
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <label class="form_label" for="inv_date">Invoice Date</label>
                    <input type="date" class="form-control" id="inv_date" name="inv_date" value="<?=getToday(false);?>" />
                </div>
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <label class="form_label" for="inv_pay_mode">Mode of Payment</label>
                    <select class="form-control" id="inv_pay_mode" name="inv_pay_mode">
                        <?=$pay_mode_options?>
                    </select>
                </div>
            </div>
        </fieldset>
        <fieldset class="fldset mt-2 d-none">
            <legend>Company Details</legend>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label" for="comp_address">Addess</label>
                    <input type="text" class="form-control" id="comp_address" name="comp_address" value="<?=$company_address?>"/>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label">Saved Addess</label>
                    <textarea class="form-control" readonly><?=$company_address?></textarea>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="comp_gstin">GSTIN No.</label>
                    <input type="text" class="form-control" id="comp_gstin" name="comp_gstin" value="<?=$company_gstin?>"/>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="comp_bank_ac_no">Bank A/C No.</label>
                    <input type="text" class="form-control" id="comp_bank_ac_no" name="comp_bank_ac_no" value="<?=$company_bank_ac_no?>" onkeydown="return acceptNumber(event, true)"/>
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="comp_ifsc_code">IFSC Code</label>
                    <input type="text" class="form-control" id="comp_ifsc_code" name="comp_ifsc_code" value="<?=$company_ifsc_code?>"/>
                </div>
            </div>
        </fieldset>
        <fieldset class="fldset mt-2" id="billing_details">
            <legend>Billing Details</legend>
            <div class="row">
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="billing_name">Name</label>
                    <input type="text" class="form-control" id="billing_name" name="billing_name" />
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="billing_address">Address</label>
                    <input type="text" class="form-control" id="billing_address" name="billing_address" />
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12">
                    <label class="form_label" for="billing_gstin">GSTIN / UNI No.</label>
                    <input type="text" class="form-control" id="billing_gstin" name="billing_gstin" />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label" for="billing_email">Email</label>
                    <input type="text" class="form-control" id="billing_email" name="billing_email" />
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label" for="billing_phone">Phone</label>
                    <input type="text" class="form-control" id="billing_phone" name="billing_phone" onkeydown="return acceptNumber(event, true)"/>
                </div>
            </div>
            <fieldset class="fldset mt-2 billing_item_fldset">
                <legend>Billing Items</legend>
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
                        <input type="text" class="form-control text-right billing_item_rate" name="billing_item_rate[]" id="billing_item_rate" value="<?=DEFAULT_AMOUNT?>" onkeydown="return acceptNumber(event, true);" onkeyup="calculateItemTotal($(this));"/>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-12">
                        <label class="form_label" for="billing_item_per">Per</label>
                        <input type="text" class="form-control billing_item_per" id="billing_item_per" name="billing_item_per[]" onkeydown="return acceptNumber(event, true);"/>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-9 col-lg-9 col-sm-12 text-right">
                        <span class="form_label">Total Amount: </span>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-12">
                        <input type="text" class="form-control text-right billing_item_total_amount" name="billing_item_total_amount[]" id="billing_item_total_amount" value="<?=DEFAULT_AMOUNT?>" onfocus="calculateItemTotal($(this));" onkeydown="return acceptNumber(event, true)"/>
                    </div>
                </div>
            </fieldset>
        </fieldset>
        <div class="text-right mt-2 add_more_item">
            <button type="button" class="btn-sm btn-secondary" style="font-size: 12px;" id="add_more_billing_item"><i class="fas fa-plus"></i>&nbsp;Add more item</button>
        </div>
        <fieldset class="fldset mt-2">
            <legend>Price Breakup</legend>
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label" for="total_taxable_amount">Total Taxable Amount</label>
                    <input type="text" class="form-control text-right" id="total_taxable_amount" name="total_taxable_amount" value="<?=DEFAULT_AMOUNT?>" onkeydown="return acceptNumber(event, true)"/>
                </div>
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <label class="form_label" for="discount_amount">Discount (If any)</label>
                    <input type="text" class="form-control text-right" id="discount_amount" name="discount_amount" value="<?=DEFAULT_AMOUNT?>" onkeydown="return acceptNumber(event, true)"/>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 col-lg-4 col-sm-12" style="display: none;">
                    <label class="form_label" for="cgst">CGST</label>
                    <input type="hidden" readonly class="form-control text-right" id="cgst" name="cgst" value="<?=DEFAULT_AMOUNT?>" />
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12" style="display: none;">
                    <label class="form_label" for="sgst">SGST</label>
                    <input type="hidden" readonly class="form-control text-right" id="sgst" name="sgst" value="<?=DEFAULT_AMOUNT?>" />
                </div>
                <div class="col-md-4 col-lg-4 col-sm-12" style="display: none;">
                    <label class="form_label" for="igst">IGST</label>
                    <input type="hidden" readonly class="form-control text-right" id="igst" name="igst" value="<?=DEFAULT_AMOUNT?>" />
                </div>
                <div class="col-md-1 col-lg-1 col-sm-6 noselect text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="gst_check" name="gst_check">
                        <label class="form-check-label" for="gst_check">G</label>
                    </div>
                </div>
                <div class="col-md-11 col-lg-11 col-sm-6 noselect text-left igst_check_col">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="igst_check" name="igst_check">
                        <label class="form-check-label" for="igst_check">I</label>
                    </div>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4 col-lg-4 col-sm-6">
                    <label class="form_label" for="advance_amount">Advance:</label>
                    <input type="text" class="form-control-sm text-right" id="advance_amount" name="advance_amount" value="<?=DEFAULT_AMOUNT?>" onkeydown="return acceptNumber(event, true)" />
                </div>
                <div class="col-md-3 col-lg-3 col-sm-6 text-right">
                    <span class="text-right form_label" id="grand_total_txt">Grand Total (Including GST):</span>
                </div>
                <div class="col-md-5 col-lg-5 col-sm-6">
                    <input type="text" class="form-control text-right" id="grand_total_amount" name="grand_total_amount" value="<?=DEFAULT_AMOUNT?>" onkeydown="return acceptNumber(event, true)"/>
                </div>
            </div>
        </fieldset>
        <div class="mt-3 row">
            <div class="col-md-6 col-lg-6 col-sm-12">
                <small class="text-muted">
                    <i class="text-danger font-weight-bold">NOTE *</i>&nbsp; To obtain automatically generated results, kindly select the specific fields for which you desire results by clicking on them.
                </small>
            </div>
            <div class="col-md-6 col-lg-6 col-sm-12 text-right">
                <!-- <button class="btn btn-success" type="button" id="preview_invoice"><i class="fas fa-eye"></i>&nbsp;Preview</button> -->
                <button class="btn btn-primary" type="submit" id="save_bill"><i class="fas fa-print"></i>&nbsp;Preview & Print</button>
            </div>
        </div>
    </form>
    </div>
</div>
<?php } ?>