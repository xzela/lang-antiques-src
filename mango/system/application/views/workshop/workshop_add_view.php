<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Add Workshop</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Add New Workshop</h2>
		<ul id="submenu">
			<li><?php echo anchor('workshop/', 'Back to Workshop Main'); ?></li>
			<li>|</li>
		</ul>
		<?php echo form_open('workshop/add') ?>
		<table class='form_table'>
			<tr>
				<td class='title'><span class='warning'>*</span>Company Name:</td>
				<td colspan='3'><input name="name" type="text" size='40' value="<?php echo set_value('name'); ?>" /></td>
			</tr>
			<tr>
				<td class='title'><span class='warning'>*</span>First Name:</td>
				<td><input name="first_name" type="text" value="<?php echo set_value('first_name'); ?>" /></td>
				<td class='title'><span class='warning'>*</span>Last Name:</td>
				<td><input name="last_name" type="text" value="<?php echo set_value('last_name'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Phone:</td>
				<td><input name="phone" type="text" value="<?php echo set_value('phone'); ?>" /></td>
				<td class="title">Fax:</td>
				<td><input name="fax" type="text" value="<?php echo set_value('fax'); ?>" /></td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'><input name="email" type="text" size='50' value="<?php echo set_value('email'); ?>" /></td>
			</tr>					
			<tr>
				<td class="title">Address:</td>
				<td colspan="3" nowrap>
					<input name="address" type="text" size="70" value="<?php echo set_value('address'); ?>"/>
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
					<input name="workshop_sbmt" type="submit" value="Save" />  | <?php echo anchor('workshop/', 'Cancel'); ?>
				</td>
			</tr>
			
		</table>
		<?php echo form_close(); ?>
		<p>Workshop Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>