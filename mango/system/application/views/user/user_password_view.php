<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title>Mango - User Options</title>
	<style type="">
		#users_table {
			padding: 10px;
			border: 1px solid #919191;
			background-color: #f1f1f1;
		}	
	</style>
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
		<p>Think someone knows what your password is? Need to change it before it's too late? Fear not, fill out this form below to change your password!</p>
		<?php echo form_open('user/change_password'); ?>
			<table class="form_table">
				<tr> 
					<td class="title">New Password:</td>
					<td><input name="new_password" type="password" value="<?php echo set_value('new_password');?>"/></td>
				</tr>
				<tr>
					<td class="title">Confirm Password:</td>
					<td><input name="new_passconf" type="password" value="<?php echo set_value('new_passconf'); ?>" /></td>
				</tr>
				<tr>
					<td class="title">Old Password:</td>
					<td><input name="old_password" type="password" /></td>
				</tr>
				<tr>
					<td colspan='2'>
						<?php echo validation_errors(); ?>
						<?php echo $message; ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<?php echo form_submit('change_password', 'Submit'); ?> 
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