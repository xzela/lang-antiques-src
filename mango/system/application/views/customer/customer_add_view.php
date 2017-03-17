<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Add Customer</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Add New Customer</h2>
		<ul id="submenu">
			<li><?php echo anchor('customer', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer Main'); ?></li>
			<li>|</li>
		</ul>
		<?php echo form_open('customer/add') ?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>First Name:</td>
				<td><input name="first_name" type="text" value="<?php echo set_value('first_name'); ?>" /></td>
				<td class='title'><span class='warning'>*</span>Last Name:</td>
				<td><input name="last_name" type="text" value="<?php echo set_value('last_name'); ?>" /></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td class='title'>Middle Name:</td>
				<td><input name="middle_name" type="text" value="<?php echo set_value('middle_name'); ?>" /></td>			
			</tr>
			<tr>
				<td class='title'>Spouse First:</td>
				<td><input name="spouse_first" type="text" value="<?php echo set_value('spouse_first'); ?>" /></td>
				<td class='title'>Spouse Last:</td>
				<td><input name="spouse_last" type="text" value="<?php echo set_value('spouse_last'); ?>" /></td>
			</tr>			
			<tr>
				<td></td>
				<td></td>
				<td class='title'>Spouse Middle:</td>
				<td><input name="spouse_middle" type="text" value="<?php echo set_value('spouse_middle'); ?>" /></td>			
			</tr>
			<tr title="Phone numbers should look like this: 415-555-0123">
				<td class="title">Home Phone:</td>
				<td><input name="home_phone" type="text" value="<?php echo set_value('home_phone'); ?>" /></td>
				<td class="title">Work Phone:</td>
				<td><input name="work_phone" type="text" value="<?php echo set_value('work_phone'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'><input name="email" type="text" size='50' value="<?php echo set_value('email'); ?>" /></td>
			</tr>					
			<tr>
				<td class="title">Address Line 1:</td>
				<td colspan="3" nowrap>
					<input name="address" type="text" size="70" value="<?php echo set_value('address'); ?>"/>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 2:</td>
				<td colspan="3" nowrap>
					<input name="address2" type="text" size="70" value="<?php echo set_value('address2'); ?>"/>
					<?php echo form_checkbox('mailing_list', 'foobar', true); ?> Mailing List
				</td>
			</tr>
			<tr>
				<td class="title">City:</td>
				<td>
					<input name="city" type="text" value="<?php echo set_value('city'); ?>"/>
				</td>
				<td class="title">State:</td>
				<td>
					<input name="state" type="text" size='3' value="<?php echo set_value('state'); ?>" maxlength='2'/>
					<strong>Zip:</strong>
					<input name="zip" type="text" size='7' value="<?php echo set_value('zip'); ?>" maxlength='11'/>
				</td>						
			</tr>						
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'><input name="country" type="text" size='50' value="<?php echo set_value('county'); ?>" /> </td>
			</tr>
			<tr>
				<td class="title">Notes:</td>

				<td colspan="3">
					<textarea name="notes" cols="60" rows="4"><?php echo set_value('notes'); ?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: right;;" class="warning">* Indicates required field.</td>
			</tr>
			<tr>
				<td colspan='4' style="text-align: center;">
					<?php echo validation_errors();  ?>
				</td>
			</tr>
			<tr>
				<td colspan="4" style="text-align: center;" >
					<input name="customer_sbmt" type="submit" value="Save" />  | <?php echo anchor('customer', 'Cancel'); ?>
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