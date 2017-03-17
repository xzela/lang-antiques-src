<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>

    <title><?php echo $this->config->item('project_name'); ?> - Gateway results</title>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <h2>Gateway Results</h2>
        <ul id="submenu">
            <li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
            <li>|</li>
        </ul>
        <table class='customer_table'>
            <tr>
                <th>ID</th>
                <th>Invoice ID</th>
                <th>Buyer Id</th>
                <th>Amount</th>
                <th>Response</th>
            </tr>
            <?php if(sizeof($gateway_data) > 0): ?>
                <?php foreach($gateway_data as $response): ?>
                    <tr>
                        <td><?php echo $response['id']; ?></td>
                        <td><?php echo anchor('sales/invoice/' . $response['invoice_id'], $response['invoice_id']); ?></td>
                        <td><?php echo anchor('customer/edit/' . $response['buyer_id'], $response['buyer_id']); ?></td>
                        <td>$<?php echo number_format($response['amount'],2); ?></td>
                        <td><?php echo $response['response']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan='8' class='warning'>Nothing Found</td>
                </tr>
            <?php endif;?>
        </table>
        <p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>