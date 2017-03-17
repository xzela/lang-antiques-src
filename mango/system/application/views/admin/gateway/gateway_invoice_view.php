<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <title><?php echo $this->config->item('project_name'); ?> - Processor - Charge Invoice</title>
    <style type="text/css">

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
        <h3>Charge an Invoice</h3>
        <p>
            Charge an Invoice. Here is all of the customer information that is related to their invoice.
            At this point because <strong>we're still testing</strong> the Payment Gateway so any changes you make here will not be saved.
        </p>
        <p class='warning'>
            This does not automatically add a credit card payment to the invoice.
            because we're testing this feature we want to make sure it works first before it starts automagically changing things.
             :)
        </p>
        <?php echo form_open('gateway/invoice/' . $invoice_data['invoice_id']); ?>
        <table class="form_table">
            <tr>
                <td class='title'></td>
                <td colspan='3'>
                    <?php echo validation_errors(); ?>
                </td>
            </tr>

            <tr>
                <td colspan='4'><h3>Invoice Information:</h3></td>
            </tr>
            <tr>
                <td class='title'>Invoice ID:</td>
                <td ><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], $invoice_data['invoice_id']); ?></td>
            </tr>
            <?php if(sizeof($invoice_items_data) > 0): ?>
                <tr>
                    <td class='title'>&nbsp;</td>
                    <td class='title'>Item Number</td>
                    <td class='title'>Item Price</td>
                    <td class='title'>Item Tax</td>
                </tr>
                <?php foreach($invoice_items_data as $item): ?>
                    <tr>
                        <td class='title'></td>
                        <td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?></td>
                        <td style='text-align: right;'>$<?php echo number_format($item['sale_price'],2); ?></td>
                        <td style='text-align: right;'>$<?php echo number_format($item['sale_tax'],2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <tr>
                <td class='title'>Price/Tax:</td>
                <td></td>
                <td >$<?php echo number_format($invoice_data['total_price'], 2); ?></td>
                <td style='text-align: right;'>$<?php echo number_format($invoice_data['tax'], 2); ?></td>
            </tr>
            <tr>
                <td class='title'>Shipping Cost:</td>
                <td>$<?php echo number_format($invoice_data['ship_cost'], 2); ?></td>
            </tr>
            <tr>
                <td class='title'>Total Charge:</td>
                <td><strong>$<?php echo number_format($invoice_data['ship_cost'] + $invoice_data['total_price'], 2); ?></strong></td>
            </tr>

            <tr>
                <td colspan='4'> <h3>Billing Information:</h3></td>
            </tr>
            <tr>
                <td class='title'>Name:</td>
                <td colspan='3'>
                    <?php echo anchor($buyer_data['link'], $buyer_data['name']); ?>
                    <input type='hidden' name='first_name' value="<?php echo $buyer_data['first_name'];?>" />
                    <input type='hidden' name='last_name' value="<?php echo $buyer_data['last_name'];?>" />
                </td>
            </tr>
            <tr>
                <td class="title">Home Phone:</td>
                <td colspan='3'>
                    <input type='text' name='home_phone' class='input_field' value='<?php echo set_value('home_phone', $buyer_data['home_phone']); ?>' />
                </td>
            </tr>
            <tr>
                <td class="title">Address Line 1:</td>
                <td colspan="3">
                    <input type='text' name='address' class='input_field' value='<?php echo set_value('address', $buyer_data['address']); ?>' style='width: 300px;'/>
                </td>
            </tr>
            <tr>
                <td class="title">Address Line 2:</td>
                <td colspan="3">
                    <input type='text' name='address2' class='input_field' value='<?php echo set_value('address2', $buyer_data['address2']); ?>' style='width: 300px;'/>
                </td>
            </tr>
            <tr>
                <td class="title">City:</td>
                <td>
                    <input type='text' name='city' class='input_field' value='<?php echo set_value('city', $buyer_data['city']); ?>' />
                </td>
                <td class="title">State:</td>
                <td>
                    <input type='text' name='state' class='input_field' value='<?php echo set_value('state', $buyer_data['state']); ?>' style='width: 25px;' />
                    <strong>Zip: </strong><input type='text' name='zip' class='input_field' value='<?php echo set_value('zip', $buyer_data['zip']); ?>' style='width: 75px;' />
                </td>
            </tr>
            <tr>
                <td class="title">Country:</td>
                <td colspan='3'>
                    <input type='text' name='country' class='input_field' value='<?php echo set_value('country', $buyer_data['country']); ?>' />
                </td>
            </tr>
            <tr>
                <td colspan='4'><h3>Credit Card Information</h3></td>
            </tr>
            <?php if(!empty($buyer_card_data)): ?>
                <tr>
                    <td class='title'>Card Number:</td>
                    <td colspan='3'><input name='card_number' type='text' value='<?php echo set_value('card_number', $buyer_decyrpted_card_data['decrypt_card_number']); ?>' /></td>
                </tr>
                <tr>
                    <td class='title'>Card CVV:</td>
                    <td colspan='3'><input name='card_cvv' type='text' value='<?php echo set_value('card_cvv', $buyer_decyrpted_card_data['decrypt_card_cvv']); ?>' /></td>
                </tr>
                <tr>
                    <td class='title'>Card Month:</td>
                    <td colspan='3'><input name='card_month' type='text' size='2' value='<?php echo set_value('card_month', $buyer_card_data['card_month']); ?>' /> <span style="color: #a1a1a1;">MM</span></td>
                </tr>
                <tr>
                    <td class='title'>Card Year:</td>
                    <td colspan='3'><input name='card_year' type='text' size='2' value='<?php echo set_value('card_year', $buyer_card_data['card_year']); ?>' /> <span style="color: #a1a1a1;">20YY</span></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan='4'><span class='warning'>No credit card on file. You MUST enter a credit card number.</span></td>
                </tr>
                    <tr>
                        <td class='title'>Card Number:</td>
                        <td colspan='3'><input name='card_number' type='text' value='<?php echo set_value('card_number'); ?>' /></td>
                    </tr>
                    <tr>
                        <td class='title'>Card CVV:</td>
                        <td colspan='3'><input name='card_cvv' type='text' value='<?php echo set_value('card_cvv'); ?>' /></td>
                    </tr>
                    <tr>
                        <td class='title'>Card Month:</td>
                        <td colspan='3'><input name='card_month' type='text' size='2' value='<?php echo set_value('card_month'); ?>' /> <span style="color: #a1a1a1;">MM</span></td>
                    </tr>
                    <tr>
                        <td class='title'>Card Year:</td>
                        <td colspan='3'><input name='card_year' type='text' size='2' value='<?php echo set_value('card_year'); ?>' /> <span style="color: #a1a1a1;">20YY</span></td>
                    </tr>
            <?php endif;?>
            <tr>
                <td class='title'></td>
                <td colspan='3'>
                    <?php echo validation_errors(); ?>
                </td>
            </tr>
        </table>
        <div>
            <button type='submit' name="charge_bill" > Charge Using Billing Address </button>
        </div>

        <?php echo form_close(); ?>
    </div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>