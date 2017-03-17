<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <title><?php echo $this->config->item('project_name'); ?> - Processor - Charge Invoice</title>
    <style type="text/css">
        .gateway_results {
            padding: 10px;
            margin: 10px;
            border: 1px dashed gray;

        }
    </style>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <h2 class='item'>
            Charge Credit Card for Invoice <?php echo $invoice_data['invoice_type_text']?> #<?php echo $invoice_data['invoice_id']; ?> for:
            <?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3): ?>
                Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
            <?php elseif($invoice_data['buyer_type'] == 2):?>
                Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
            <?php endif; ?>
        </h2>
        <ul id="submenu">
            <li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
            <li>|</li>
        </ul>
        <h3 class='warning'>This did not automatically add a credit card payment to the invoice. You still have to do that at this point. </h3>
        <h3>Charge Results:</h3>

        <div>
            <?php if(@$gateway_results['ssl_result_message']): ?>
                <h3><?php echo $gateway_results['ssl_result_message'] ; ?></h3>
            <?php else: ?>
                <h3><?php echo $gateway_results['errorName']; ?></h3>
            <?php endif; ?>

            <p>API and Debugging results:</p>
            <div class="gateway_results">
            <?php foreach($gateway_results as $key => $value): ?>
                [<?php echo $key; ?>]: <?php echo $value; ?> <br />
            <?php endforeach; ?>
            </div>
            <span>Email Zeph this information if something crazy happens.</span>
            <h3 class='warning'>This did not automatically add a credit card payment to the invoice. You still have to do that at this point. </h3>
        </div>
    </div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>