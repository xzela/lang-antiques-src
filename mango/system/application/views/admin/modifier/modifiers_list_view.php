<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
    <?php echo snappy_style('styles.css'); //autoloaded ?>

    <title><?php echo $this->config->item('project_name'); ?> - Admin Options - List All Modifiers </title>

    <script type="text/javascript">
    base_url = '<?php echo base_url(); ?>index.php/';
    </script>
    <style>
    span.embolden a {
        font-weight: bold;
    }
    </style>
</head>
<body>
<?php
    $this->load->view('_global/header');
    $this->load->view('_global/menu');
?>
    <div id="content">
        <h2 class='item'>Admin - List All Modifiers</h2>
        <ul id='submenu'>
            <li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
            <li>|</li>
            <li><?php echo anchor('admin/modifier_add', 'Create New Modifier'); ?></li>
            <li>|</li>
        </ul>
        <p>Here is a list of all the known Modifiers.</p>
        <table class='customer_table'>
            <tr>
                <th>ID</th>
                <th nowrap>Modifier Name</th>
                <th>Count of Items</th>
                <th>Active</th>
                <th>Keyword</th>
                <th>Options</th>
            </tr>
            <?php foreach($modifiers as $modifier):?>
                <?php
                    $bold_class = $modifier['embolden'] == 'yes' ? 'embolden' : '';
                ?>
                <tr>
                    <td><?php echo $modifier['modifier_id']; ?></td>
                    <td><span class="<?php echo $bold_class; ?>"><?php echo anchor('admin/modifier_edit/' . $modifier['modifier_id'], $modifier['modifier_name']); ?></span></td>
                    <td><?php echo $modifier['count']; ?></td>
                    <td><?php echo $yesno[$modifier['active']]; ?></td>
                    <td><?php echo $yesno[$modifier['alt_keyword']]; ?></td>
                    <td>[<?php echo anchor('admin/modifier_edit/' . $modifier['modifier_id'], 'Edit Modifier'); ?>]</td>
                </tr>
            <?php endforeach;?>
        </table>
        <p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
</div>
<?php
    $this->load->view('_global/footer');
?>

</body>
</html>