<?php
function printContent()
{
    $invoice_tr = '';
    $getInvoice = getData(Table::INVOICE,[
        INVOICE::ID,
        INVOICE::INVOICE_NUMBER,
        INVOICE::BILLING_NAME,
        INVOICE::GRAND_TOTAL_AMOUNT,
        INVOICE::INVOICE_DATE,
        INVOICE::INVOICE_MONTH,
        INVOICE::INVOICE_YEAR
    ], [
        INVOICE::CLIENT_ID => $_SESSION[CLIENT_ID],
        INVOICE::STATUS => ACTIVE_STATUS
    ], [], [], [INVOICE::ID], "DESC");
    if (count($getInvoice)>0) {
        foreach ($getInvoice as $k => $v) {
            $slno = ($k+1);
            $inv_no = altRealEscape($v[INVOICE::INVOICE_NUMBER]);
            $bname = altRealEscape($v[INVOICE::BILLING_NAME]);
            $gtotal = moneyFormatIndia($v[INVOICE::GRAND_TOTAL_AMOUNT]);
            $invDate = getFormattedDateTime($v[INVOICE::INVOICE_DATE], LONG_DATE_FORMAT);
            $invmonth = ALL_MONTHS_NAME[$v[INVOICE::INVOICE_MONTH]]." ".$v[INVOICE::INVOICE_YEAR];

            $invoice_tr .= '<tr id="old_invoice_'.$v[INVOICE::ID].'" onclick="window.open(\''.HOST_URL.'generated-invoice?invid='. $v[INVOICE::ID] .'\', \'_blank\');">
            <td>'.$slno.'</td>
            <td>'.$inv_no.'</td>
            <td>'.$bname.'</td>
            <td>'.$gtotal.'</td>
            <td>'.$invmonth.'</td>
            <td>'.$invDate.'</td>
        </tr>';
        }
    } else {
        $invoice_tr ='<tr class="animated fadeInDown">
        <td colspan="6">
            <div class="alert alert-danger" role="alert">
                No Invoices found !
            </div>
        </td>
    </tr>';
    }
?>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12 text-right">
        <h4 class="text-center font-weight-bold">View Invoices here</h4>
    </div>
</div>

<div class="card mt-5">
    <?=getSpinner(true, "old_invoice_loader")?>
    <div class="card-body" style="padding: 15px;">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover text-center _table" id="example" style="font-size:14px;">
                        <thead class="text-center table-warning cursor-pointer">
                            <tr style="text-transform: uppercase;">
                                <th>SL.</th>
                                <th>invoice no.</th>
                                <th>billing name</th>
                                <th>billing Amount</th>
                                <th>Billing month</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody class="cursor-pointer">
                            <?=$invoice_tr;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>