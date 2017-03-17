<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title>Mango - User Options</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>User Options</h2>
		<ul id='submenu'>
			<li><?php echo anchor('user', '<< Back to User Options'); ?></li>
		</ul>
		<p>So, you need to change your name eh? Fear not, fill out this form below to change your name! Please note that when you change your name, all of the items you have entered will be updated with the new name. </p>
		<p>Currently your name is: <b><?php echo $user_info['first_name'] . " " . $user_info['last_name']; ?> </b></p>
		<p>Please note that this will  not change your username. If you need to change your username please contact your system administator.</p>

		<?php echo form_open('user/change_name'); ?>
			<table class="form_table">
				<tr> 
					<td class="title">First Name:</td>
					<td><input name="first_name" type="text" value="<?php echo set_value('first_name');?>"/></td>
				</tr>
				<tr>
					<td class="title">Last Name:</td>
					<td><input name="last_name" type="text" value="<?php echo set_value('last_name'); ?>" /></td>
				</tr>
				<tr>
					<td class="title">Password:</td>
					<td><input name="password" type="password" /></td>
				</tr>
				<tr>
					<td colspan='2'>
						<?php echo validation_errors(); ?>
						<?php echo $message; ?>				
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<?php echo form_submit('change_email', 'Submit'); ?> 
						<?php echo anchor('user/', 'Cancel'); ?>
					</td>
				</tr>
			</table>
		<?php echo form_close(); ?>	
		<p>User Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>