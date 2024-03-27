<?php 
// include './config.php';
function printContent(){
    $response = ['error' => true, 'empty' => false, 'tab' => false, 'message' => '', 'login' => 1];
    $method = $_SERVER['REQUEST_METHOD'];
    $data = $_GET;
    switch ($method) {
        case 'GET':
            $data = $_GET;
            break;
        case 'POST':
            $data = $_POST;
            break;
        default:
            $data = $_REQUEST;
    }   
    // rip($data);
    // exit;

    // $dbSave       = (isset($data['save'])) ? true : false;
    $dbSave         = false;
    $alreadyExist   = false;
    $oldInvoice     = false;
    $bill_item_data = $bill_info_data = [];
    $bill_item_tr   = '';
    $invoice_id     = 0;

    if (isset($data['invid'])) {
        $invoice_id = $data['invid'];
        $oldInvoice = true;
    }
    switch ($oldInvoice) {
        case true:
            // echo "invoice_id: ". $invoice_id;
            $getBllingInfoData = getData(Table::INVOICE, [
                INVOICE::INVOICE_NUMBER,
                INVOICE::INVOICE_COUNT_NUMBER,
                INVOICE::INVOICE_DATE,
                INVOICE::INVOICE_MONTH,
                INVOICE::INVOICE_YEAR,
                INVOICE::COMPANY_ADDRESS,
                INVOICE::COMPANY_GSTIN_NUMBER,
                INVOICE::COMPANY_BANK_ACCOUNT_NO,
                INVOICE::COMPANY_IFSC_CODE,
                INVOICE::MODE_OF_PAYMENT,
                INVOICE::BILLING_NAME,
                INVOICE::BILLING_ADDRESS,
                INVOICE::BILLING_GSTIN,
                INVOICE::BILLING_EMAIL,
                INVOICE::BILLING_PHONE,
                INVOICE::TAXABLE_AMOUNT,
                INVOICE::DISCOUNT_AMOUNT,
                INVOICE::IS_GST_BILL,
                INVOICE::CGST_AMOUNT,
                INVOICE::SGST_AMOUNT,
                INVOICE::IGST_AMOUNT,
                INVOICE::GRAND_TOTAL_AMOUNT,
                INVOICE::CREATION_DATE,
                INVOICE::ADVANCE_AMOUNT,
                INVOICE::DUE_AMOUNT
            ], [
                INVOICE::ID => $invoice_id,
                INVOICE::CLIENT_ID => $_SESSION[CLIENT_ID],
                INVOICE::STATUS => ACTIVE_STATUS
            ]);
            $getBllingItemData = getData(Table::INVOICE_DETAILS, [
                INVOICE_DETAILS::BILLING_DESCRIPTION,
                INVOICE_DETAILS::BILLING_QUANTITY,
                INVOICE_DETAILS::BILLING_RATE,
                INVOICE_DETAILS::BILLING_PER,
                INVOICE_DETAILS::BILLING_AMOUNT,
                INVOICE_DETAILS::CREATION_DATE
            ], [
                INVOICE_DETAILS::INVOICE_ID => $invoice_id,
                INVOICE_DETAILS::CLIENT_ID => $_SESSION[CLIENT_ID],
                INVOICE_DETAILS::STATUS => ACTIVE_STATUS
            ]);
            // rip($getBllingItemData);
            // exit();
            if (count($getBllingInfoData)>0) {
                $data         = $getBllingInfoData[0];
                $inv_no       = $data[INVOICE::INVOICE_NUMBER];
                $ex           = (explode("/",$inv_no));
                $count        = $ex[1];
                $inv_count_no = $count;
                $inv_date     = ((!isset($data[INVOICE::INVOICE_DATE]))     || ($data[INVOICE::INVOICE_DATE] == '')) ? getToday(false) : $data[INVOICE::INVOICE_DATE];
                $inv_pay_mode = ((!isset($data[INVOICE::MODE_OF_PAYMENT]))  || ($data[INVOICE::MODE_OF_PAYMENT] == 0) || ($data[INVOICE::MODE_OF_PAYMENT] == '')) ? NEFT : $data[INVOICE::MODE_OF_PAYMENT];
                //Company Data
                $comp_address    = $data[INVOICE::COMPANY_ADDRESS];
                $comp_gstin      = $data[INVOICE::COMPANY_GSTIN_NUMBER];
                $comp_bank_ac_no = $data[INVOICE::COMPANY_BANK_ACCOUNT_NO];
                $comp_ifsc_code  = $data[INVOICE::COMPANY_IFSC_CODE];
                //Billing info data
                $billing_name    = ((empty($data[INVOICE::BILLING_NAME]))    || ($data[INVOICE::BILLING_NAME] == ""))    ? EMPTY_VALUE : altRealEscape($data[INVOICE::BILLING_NAME]);
                $billing_address = ((empty($data[INVOICE::BILLING_ADDRESS])) || ($data[INVOICE::BILLING_ADDRESS] == "")) ? EMPTY_VALUE : altRealEscape($data[INVOICE::BILLING_ADDRESS]);
                $billing_gstin   = ((empty($data[INVOICE::BILLING_GSTIN]))   || ($data[INVOICE::BILLING_GSTIN] == ""))   ? EMPTY_VALUE : altRealEscape($data[INVOICE::BILLING_GSTIN]);
                $billing_email   = ((empty($data[INVOICE::BILLING_EMAIL]))   || ($data[INVOICE::BILLING_EMAIL] == ""))   ? EMPTY_VALUE : altRealEscape($data[INVOICE::BILLING_EMAIL]);
                $billing_phone   = ((empty($data[INVOICE::BILLING_PHONE]))   || ($data[INVOICE::BILLING_PHONE] == ""))   ? EMPTY_VALUE : altRealEscape($data[INVOICE::BILLING_PHONE]);
                //Billing amount Data
                $gst_check     = $data[INVOICE::IS_GST_BILL];
                $cgst          = $data[INVOICE::CGST_AMOUNT];
                $sgst          = $data[INVOICE::SGST_AMOUNT];
                $igst          = $data[INVOICE::IGST_AMOUNT];
                $igst_check    = (($gst_check == 1) && ($igst != 0)) ? 1 : 0;
                $total_taxable_amount = $data[INVOICE::TAXABLE_AMOUNT];
                $discount_amount      = $data[INVOICE::DISCOUNT_AMOUNT];
                $grand_total_amount   = $data[INVOICE::GRAND_TOTAL_AMOUNT];
                $advance_amount       = $data[INVOICE::ADVANCE_AMOUNT];
                $due_amount           = $data[INVOICE::DUE_AMOUNT];
                $advance_amount       = ($due_amount == 0) ? 'FULL' : $advance_amount;
            }

            //Billing Items Data [multiple value]
            $billing_item_desc         = [];
            $billing_item_quantity     = [];
            $billing_item_rate         = [];
            $billing_item_per          = [];
            $billing_item_total_amount = [];

            if (count($getBllingItemData)>0) {
                foreach ($getBllingItemData as $k => $v) {
                    //Billing Items Data [multiple value]
                    $billing_item_desc[]         = $v[INVOICE_DETAILS::BILLING_DESCRIPTION];
                    $billing_item_quantity[]     = $v[INVOICE_DETAILS::BILLING_QUANTITY];
                    $billing_item_rate[]         = $v[INVOICE_DETAILS::BILLING_RATE];
                    $billing_item_per[]          = $v[INVOICE_DETAILS::BILLING_PER];
                    $billing_item_total_amount[] = $v[INVOICE_DETAILS::BILLING_AMOUNT];
                }
            }
            // rip($billing_item_desc);
            // exit();
            break;
        case false:
            $inv_no       = $data['inv_no'];
            $ex           = (explode("/",$inv_no));
            $count        = $ex[1];
            $inv_count_no = ($data['inv_count_no'] != $count) ? $count : $data['inv_count_no'];
            $inv_date     = ((!isset($data['inv_date']))     || ($data['inv_date'] == '')) ? getToday(false) : $data['inv_date'];
            $inv_pay_mode = ((!isset($data['inv_pay_mode'])) || ($data['inv_pay_mode'] == 0) || ($data['inv_pay_mode'] == '')) ? NEFT : $data['inv_pay_mode'];
            //Company Data
            $comp_address    = $data['comp_address'];
            $comp_gstin      = $data['comp_gstin'];
            $comp_bank_ac_no = $data['comp_bank_ac_no'];
            $comp_ifsc_code  = $data['comp_ifsc_code'];
            //Billing info data
            $billing_name    = ((empty($data['billing_name']))    || ($data['billing_name'] == ""))    ? EMPTY_VALUE : altRealEscape($data['billing_name']);
            $billing_address = ((empty($data['billing_address'])) || ($data['billing_address'] == "")) ? EMPTY_VALUE : altRealEscape($data['billing_address']);
            $billing_gstin   = ((empty($data['billing_gstin']))   || ($data['billing_gstin'] == ""))   ? EMPTY_VALUE : altRealEscape($data['billing_gstin']);
            $billing_email   = ((empty($data['billing_email']))   || ($data['billing_email'] == ""))   ? EMPTY_VALUE : altRealEscape($data['billing_email']);
            $billing_phone   = ((empty($data['billing_phone']))   || ($data['billing_phone'] == ""))   ? EMPTY_VALUE : altRealEscape($data['billing_phone']);
            //Billing amount Data
            $gst_check     = (isset($data['gst_check']))  ? 1 : 0;
            $igst_check    = (isset($data['igst_check'])) ? 1 : 0;
            $cgst          = $data['cgst'];
            $sgst          = $data['sgst'];
            $igst          = $data['igst'];
            $gst_amount    = ($gst_check == 1) ? (($igst_check == 1) ? $igst : ($cgst + $sgst)) : 0 ;
            $total_taxable_amount = $data['total_taxable_amount'];
            $discount_amount      = $data['discount_amount'];
            $grand_total_amount   = $data['grand_total_amount'];
            $advance_amount       = $data['advance_amount'];
            $due_amount           = (($grand_total_amount - $advance_amount) == 0) ? 0 : ($grand_total_amount - $advance_amount);
            $advance_amount       = ($due_amount == 0) ? 'FULL' : $advance_amount;

            //Billing Items Data [multiple value]
            $billing_item_desc         = $data['billing_item_desc'];
            $billing_item_quantity     = $data['billing_item_quantity'];
            $billing_item_rate         = $data['billing_item_rate'];
            $billing_item_per          = $data['billing_item_per'];
            $billing_item_total_amount = $data['billing_item_total_amount'];
            break;
    }


    

    $bill_info_data = [
        INVOICE::CLIENT_ID               => $_SESSION[CLIENT_ID],
        INVOICE::INVOICE_NUMBER          => $inv_no,
        INVOICE::INVOICE_COUNT_NUMBER    => $inv_count_no,
        INVOICE::INVOICE_DATE            => $inv_date,
        INVOICE::INVOICE_MONTH           => getFormattedDateTime($inv_date, 'm'),
        INVOICE::INVOICE_YEAR            => getFormattedDateTime($inv_date, 'Y'),
        INVOICE::COMPANY_ADDRESS         => $comp_address,
        INVOICE::COMPANY_GSTIN_NUMBER    => $comp_gstin,
        INVOICE::COMPANY_BANK_ACCOUNT_NO => $comp_bank_ac_no,
        INVOICE::COMPANY_IFSC_CODE       => $comp_ifsc_code,
        INVOICE::MODE_OF_PAYMENT         => $inv_pay_mode,
        INVOICE::BILLING_NAME            => $billing_name,
        INVOICE::BILLING_ADDRESS         => $billing_address,
        INVOICE::BILLING_GSTIN           => $billing_gstin,
        INVOICE::BILLING_EMAIL           => $billing_email,
        INVOICE::BILLING_PHONE           => $billing_phone,
        INVOICE::TAXABLE_AMOUNT          => $total_taxable_amount,
        INVOICE::DISCOUNT_AMOUNT         => $discount_amount,
        INVOICE::IS_GST_BILL             => $gst_check,
        INVOICE::CGST_AMOUNT             => $cgst,
        INVOICE::SGST_AMOUNT             => $sgst,
        INVOICE::IGST_AMOUNT             => $igst,
        INVOICE::GRAND_TOTAL_AMOUNT      => $grand_total_amount,
        INVOICE::STATUS                  => ACTIVE_STATUS,
        INVOICE::CREATION_DATE           => getToday(),
        INVOICE::ADVANCE_AMOUNT          => $advance_amount,
        INVOICE::DUE_AMOUNT              => $due_amount
    ];
    
    //check invoice no for duplicate entry
    $getDBinvoiceData = getData(Table::INVOICE, [
        INVOICE::ID,
        INVOICE::INVOICE_COUNT_NUMBER,
        INVOICE::INVOICE_NUMBER
    ], [
        INVOICE::CLIENT_ID => $_SESSION[CLIENT_ID],
        INVOICE::STATUS => ACTIVE_STATUS,
        INVOICE::INVOICE_COUNT_NUMBER => $inv_count_no,
        INVOICE::INVOICE_MONTH => getToday(false, 'm'),
        INVOICE::INVOICE_YEAR => getToday(false, 'Y')
    ]);
    if (count($getDBinvoiceData)>0) {
        $alreadyExist = true;
    }
    if ($dbSave) {
        if ($alreadyExist) {
            $response['error'] = true;
            $response['message'] = "INVOICE No already exists";
        } else {
            $save_invoice = setData(Table::INVOICE, $bill_info_data);
            if (!$save_invoice['res']) {
                logError("Unabled to save invoice data, Invoice Number: ".$inv_no.", Billing Name: ".$billing_name.".", $save_invoice['error']);
                $response['error'] = true;
                $response['message'] = "Failed to save Invoice";
            } else {
                $invoice_id = $save_invoice['id'];
                $response['error'] = false;
                $response['message'] = "Invoice Saved Successfully";
            }
        }
    }

    for ($i=0; $i < count($billing_item_desc); $i++) {

        $desc   = (!empty($billing_item_desc[$i]))         ? altRealEscape($billing_item_desc[$i])         : EMPTY_VALUE;
        $qty    = (!empty($billing_item_quantity[$i]))     ? altRealEscape($billing_item_quantity[$i])     : DEFAULT_AMOUNT;
        $rate   = (!empty($billing_item_rate[$i]))         ? altRealEscape($billing_item_rate[$i])         : DEFAULT_AMOUNT;
        $per    = (!empty($billing_item_per[$i]))          ? altRealEscape($billing_item_per[$i])          : DEFAULT_AMOUNT;
        $total  = (!empty($billing_item_total_amount[$i])) ? altRealEscape($billing_item_total_amount[$i]) : DEFAULT_AMOUNT;

        $bill_item_data[] = [
            INVOICE_DETAILS::CLIENT_ID            => $_SESSION[CLIENT_ID],
            // INVOICE_DETAILS::INVOICE_ID           => $invoice_id,
            INVOICE_DETAILS::BILLING_DESCRIPTION  => $billing_item_desc[$i],
            INVOICE_DETAILS::BILLING_QUANTITY     => $billing_item_quantity[$i],
            INVOICE_DETAILS::BILLING_RATE         => $billing_item_rate[$i],
            INVOICE_DETAILS::BILLING_PER          => $billing_item_per[$i],
            INVOICE_DETAILS::BILLING_AMOUNT       => $billing_item_total_amount[$i],
            INVOICE_DETAILS::STATUS               => ACTIVE_STATUS,
            INVOICE_DETAILS::CREATION_DATE        => getToday()
        ];
        // if ($dbSave) {
        //     if ((!$response['error']) && ($invoice_id != 0)) {
        //     }
        // }

        $bill_item_tr .= '<tr>
            <td style="text-align:center;" class="no">'.($i+1).'</td>
            <td style="text-align:left;" class="desc">
                <h3>'.$desc.'</h3>
            </td>
            <td style="text-align:center;" class="unit">'.$qty.'</td>
            <td style="text-align:right;" class="qty">'.moneyFormatIndia($rate).'</td>
            <td style="text-align:center;" class="unit">'.$per.'</td>
            <td style="text-align:right;" class="total">'.moneyFormatIndia($total).'</td>
        </tr>';
    }
    // echo $invoice_id.'<br>';
    // rip($bill_item_data);
    // exit();

    if ($dbSave) {
        if ((!$response['error']) && ($invoice_id != 0) && (count($bill_item_data)>0)) {
            $save_bill_items = setMultipleData(Table::INVOICE_DETAILS, $bill_item_data);
            if (!$save_bill_items['res']) {
                logError("Unabled to save billing items data. Invoice ID: ".$invoice_id.", Invoice No: ".$inv_no.".", $save_bill_items['error']);
                $response['error'] = true;
                $response['message'] = "Failed to save Billing Items data";
                //Now delete the main invoice table row containing the current invoice id
            } else {
                $response['error'] = false;
                $response['message'] .= " With all Items";
            }
        }
    }
    //Spliting long Company address
    $split = (str_split($comp_address,43));
    $s1 = $split[0];
    $s2 = implode("", [$split[1], $split[2]]);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>TAX Invoice</title>
    <link href="<?php echo PAGE_URL; ?>invoice/style.css?v=<?php echo ASSETS_VERSION; ?>" rel="stylesheet">
    <?php include(BASE_DIR . 'includes/admin_url_to_css.php'); ?>
</head>

<body>
    <div id="loading-bar-spinner" class="spinner" style="display: none;"><div class="spinner-icon"></div></div>
    
    <div style="width:100%;display: flex;justify-content: space-between;">
        <div style="width: 50%;padding: 15px;">
            <button id="print_invoice" type="button" style="padding: 5px 5px;border-radius: 4px;border: solid 1px; cursor:pointer;" onclick="printDiv('invoice_div');">Print</button>
            <?php if ((!$oldInvoice) && (!$alreadyExist)): ?>
            <button id="save_info" type="button" style="padding: 5px 5px;border-radius: 4px;border: solid 1px; cursor:pointer;" onclick='saveInvoiceData(<?=json_encode($bill_info_data);?>,<?=json_encode($bill_item_data);?>);'>Save</button>
            <?php endif; ?>
            <button id="close_info" type="button" style="padding: 5px 5px;border-radius: 4px;border: solid 1px; cursor:pointer;" onclick="<?php if($oldInvoice): ?>javascript:window.close('','_parent','');<?php else: ?>window.location.href = '<?=HOST_URL?>generate-invoice';<?php endif; ?>">Close</button>
        </div>
        <div style="width: 50%; padding: 15px; text-align: end;">
            <span class="response" style="font-weight: bold;"><?=(($oldInvoice) || ($alreadyExist)) ? "Invoice has already been saved !" : "";?></span>
        </div>
    </div>
    <div id="invoice_div">
        <header class="clearfix">
            <div id="logo">
                <img src="<?php echo PAGE_URL; ?>invoice/pl-logo.png">
            </div>
            <div id="company">
                <h2 class="name"><?=COMPANY_BUSINESS_NAME?></h2>
                <div></div>
                <!-- <div>WEBEL TOWER 2, BN 9, BN BLOCK, SECTOR V, <br> BIDHANNAGAR, KOLKATA, WEST BENGAL - 700091</div> -->
                <div><?=$s1?><br><?=$s2?></div>
                <div>GST NUMBER : <?=$comp_gstin?></div>
            </div>
        </header>
        <main>
            <div id="details" class="clearfix">
                <div id="client">
                    <div style="text-align: left;" class="to">INVOICE TO:</div>
                    <h2 style="text-align: left;" class="name"><?=$billing_name;?></h2>
                    <div style="text-align: left;" class="address"><?=$billing_address?></div>
                    <div style="text-align: left;" class="address">GSTIN / UNI : <?=$billing_gstin?></div>
                    <div style="text-align: left;" class="address">PHONE : <?=$billing_phone?></div>
                    <div style="text-align: left;" class="email"><a href="mailto:<?=$billing_email?>"><?=$billing_email?></a></div>
                </div>
                <div id="invoice">
                    <h1>TAX INVOICE NO : <?=$inv_no?></h1>
                    <div class="date">Date of Invoice: <?=$inv_date?></div>
                    <div class="date">Mode of Payment : <?=INVOICE_PAY_MODES[$inv_pay_mode]?></div>
                </div>
            </div>
            <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="no">#</th>
                        <th class="desc">DESCRIPTION</th>
                        <th class="unit">QUANTITY</th>
                        <th class="qty">UNIT PRICE</th>
                        <th class="unit">PER</th>
                        <th class="total">TOTAL</th>
                    </tr>
                </thead>
                <tbody><?=$bill_item_tr?></tbody>
                <tfoot>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">Total Taxable Amount</td>
                        <td><?=moneyFormatIndia($total_taxable_amount)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">Discount(If Any)</td>
                        <td><?=moneyFormatIndia($discount_amount)?></td>
                    </tr>
                    <?php if($gst_check == 1): ?>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">ADD : CGST@<?=($igst_check == 1) ? 0 : ((int)(DEFAULT_GST_PERCENTAGE) / 2);?>%</td>
                        <td><?=moneyFormatIndia($cgst)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">ADD : SGST@<?=($igst_check == 1) ? 0 : ((int)(DEFAULT_GST_PERCENTAGE) / 2);?>%</td>
                        <td><?=moneyFormatIndia($sgst)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">ADD : IGST@<?=($igst_check == 1) ? DEFAULT_GST_PERCENTAGE : 0;?>%</td>
                        <td><?=moneyFormatIndia($igst)?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="3">GRAND TOTAL <?php if($gst_check == 1): ?>(Including GST)<?php endif; ?></td>
                        <td><?=moneyFormatIndia($grand_total_amount)?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="color:green" colspan="3">Advance</td>
                        <td style="color:green"><?=(($grand_total_amount == $advance_amount) || ($due_amount == 0)) ? '<span style="color:green">Full</span>' : moneyFormatIndia($advance_amount);?></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td style="color:red" colspan="3">Due</td>
                        <td style="color:red"><?=moneyFormatIndia($due_amount)?></td>
                    </tr>
                    <!-- <tr>
                        <td colspan="2">Amount in Words</td>
                        <td colspan="5"></td>
                    </tr> -->
                    <tr>
                        <!-- <td class="no">03</td> -->
                        <td class="" style="text-align: start; border: 1px solid #AAAAAA; padding: 0px !important; padding-left: 10px !important; font-size: 16px;" colspan="2">
                            <h5>Amount in Words</h5>
                        </td>
                        <td class="" style="text-align: end; padding: 0px !important;" colspan="4">E.& O.E</td>
                    </tr>
                    <tr>
                        <!-- <td class="no">03</td> -->
                        <td class="" style="text-align: start; border: 1px solid #AAAAAA; padding: 0px !important; padding-left: 10px !important; font-size: 16px;" colspan="2">
                            <h4 style="text-transform: uppercase;"><?=getIndianCurrency($grand_total_amount)?></h4>
                        </td>
                        <td class="" style="text-align: end; padding: 0px !important;" colspan="4">&NonBreakingSpace;</td>
                    </tr>

                </tfoot>
            </table>

            <div id="client" style="margin-top: 33px;">
                <div style="text-align: left; font-weight: bold;" class="to">Cheque/NEFT Details :</div>
                <div style="text-align: left;" class="address"><?=COMPANY_BUSINESS_NAME?></div>
                <div style="text-align: left;" class="address">A/C NO : <?=$comp_bank_ac_no?></div>
                <div style="text-align: left;" class="address">IFSC Code : <?=$comp_ifsc_code?></div>
                <div style="text-align: left;" class="address">[Payment Terms : Within 15 Days following the Invoice Date.]
                </div>
            </div>
            <!-- <div id="invoice">
                <div style="text-align: left;" class="to">FOR PAPERLINK SOFTWARES PVT. LTD. <br> <br> <br> <br> <br> <br>
                </div>
                <div style="text-align: right;" class="address">Authorized Signatory</div>
            </div> -->
            <div id="invoice" style="display: grid;">
                <div style="text-align: right;" class="to">FOR <?=strtoupper(COMPANY_BUSINESS_NAME)?></div>
                <div id="logo">
                <img src="<?=CDN_URL?>img/sign_stamp.jpg" style="width: 140px;">
            </div>
                <div style="text-align: right;" class="address">Authorized Signatory</div>
            </div>
            <!-- <div class="foot-note">*Terms and Condition Apply*</div> -->
            <!-- <p style="text-align: center; margin-top: 65px;">*This is a system generated invoice.*</p> -->
        </main>
        <!-- </div> -->


        <!-- <footer>
            <h4 style="text-align: center;">*Terms and Condition Apply*</h4>
        </footer> -->
        <footer>
            <h4 style="text-align: center; padding: 0 !important; margin: 0 !important; font-weight:normal;">*Terms and Condition Apply*</h4>
            <h4 style="text-align: center; padding: 0 !important; margin: 0 !important; font-weight:normal;">*This is a system generated invoice.*</h4>
        </footer>


    </div>
    <?php include BASE_DIR.'includes/admin_url_to_js.php'; ?>

    <script type="text/javascript">
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
        // printDiv('invoice_div');
    </script>
</body>

</html>
<?php 
}
?>