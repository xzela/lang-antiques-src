<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<?php echo snappy_style('calendar.css'); ?>
	
	<?php echo snappy_script('calendar_us.js'); ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Customer Edit Store Credit</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Customer - Edit Store Credit</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer/edit/' . $customer['customer_id'], snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer'); ?></li>
			<li>|</li>
		</ul>
		<?php
			$attributes = array('method' => 'post', 'name' => 'store_credit_form');
			echo form_open('customer/edit_store_credit/' . $customer['customer_id'] . '/' . $action, $attributes);
		?>
		<table class='form_table'>
			<tr>
				<td class='title'>Amount:</td>
				<td>
					<input name="amount" type="text" value="<?php echo set_value('amount'); ?>" />
				</td>
			</tr>
			<tr>
				<td class='title'>Date:</td>
				<td>
					<input name="transaction_date" type="text" value="<?php echo date('m/d/Y'); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'store_credit_form',
						// input name
						'controlname': 'transaction_date'
					});
					</script>
				</td>
			</tr>			
			<tr>
				<td class="title">Reason:</td>
				<td>
					<textarea name="reason" cols='50' rows='5'><?php echo set_value('reason'); ?></textarea> 
				</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;" >
					<input name="customer_sbmt" type="submit" value="<?php echo $action . ' credit'; ?>" />  | <?php echo anchor('customer/edit/' . $customer['customer_id'], 'Cancel'); ?>
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