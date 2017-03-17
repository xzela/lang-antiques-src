<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	
	<?php echo snappy_style('calendar.css'); ?>
	<?php echo snappy_script('calendar_us.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customer Add Special Order</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer Add New Special Order</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
		</ul>
		<?php $attributes = array('method' => 'post', 'name' => 'add_special_order_form');?>
		<?php echo form_open('customer/add_special_order/' . $customer['customer_id'], $attributes) ?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>Order Description:</td>
				<td>
					<textarea name="order_description" cols='50' rows='4'><?php echo set_value('order_description'); ?></textarea>
				</td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>Company Name:</td>
				<td>
					<input type='text' name='company_name' value='<?php echo set_value('company_name'); ?>' />
				</td>
			</tr>
			<tr>
				<td class="title"><span class='warning'>*</span>Order Date:</td>
				<td>
					<input name="order_date" type="text" value="<?php echo set_value('order_date') == '' ? date("m/d/Y") : set_value('order_date'); ?>"/>
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'add_special_order_form',
						// input name
						'controlname': 'order_date'
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class="title">Invoice ID:</td>
				<td colspan='3'><input name="invoice_id" type="text" value="<?php echo set_value('invoice_id'); ?>" /> </td>
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