<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>


    <?php echo snappy_style('calendar.css'); ?>
    <?php echo snappy_script('calendar_us.js'); ?>

    <title><?php echo $this->config->item('project_name'); ?> - Customer Reset Password</title>

</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <h2>Customer Reset Password</h2>
        <ul id="submenu">
            <li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
            <li>|</li>
        </ul>
        <p> Change a customer password here!</p>
        <?php $attributes = array('method' => 'post', 'name' => 'reset_password');?>
        <?php echo form_open('customer/reset_password/' . $customer['customer_id'], $attributes) ?>
        <table class='form_table'>
            <tr>
                <td class='title'><span class='warning'>*</span>Password :</td>
                <td>
                    <input name="password" type="text" value="" />
                </td>
            </tr>
            <tr>
                <td class='title'><span class='warning'>*</span>Verify:</td>
                <td>
                    <input name="password2" type="text" value="" />
                </td>
            </tr>
            <tr>
                <td colspan='4' style="text-align: center;">
                    <?php echo validation_errors();  ?>
                </td>
            </tr>
            <tr>
                <td colspan="4" style="text-align: center;" >
                    <input name="customer_sbmt" type="submit" value="Save" />  | <?php echo anchor('customer/edit/' . $customer['customer_id'], 'Cancel'); ?>
                </td>
            </tr>

        </table>
        <?php echo form_close(); ?>
        <p>Customer Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>