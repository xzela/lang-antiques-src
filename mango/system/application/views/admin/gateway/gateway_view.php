<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>
    <title><?php echo $this->config->item('project_name'); ?> - Credit Card Processor Options</title>
    <style type="text/css">

    </style>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <p>This is the Credit Card Processor Center.</p>
        <h2 class="admin_h2">Processor</h2>
            <h3 class="admin_h3">Processor Options:</h3>
            <ul class="admin_ul">
                <li><?php echo anchor('admin/company_information', snappy_image('icons/building_edit.png') . ' Run a Credit Card'); ?></li>
                <li><?php echo anchor('gateway/invoice', snappy_image('icons/picture_edit.png') . ' Charge an Invoice'); ?></li>
            </ul>
        <p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>