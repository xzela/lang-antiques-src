<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Admin Options - New User</title>
	
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>Create New User</h2>
		<ul id='submenu'>
			<li><?php echo anchor('admin', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Admin Options'); ?></li>
		</ul>
		<p>To create a new user, simply fill out our form. Please note that you will only need to give out a minimal amount of information.</p>					
		<div id="signup">
		<?php echo form_open('admin/user_new'); ?>
				<table class='form_table'>
					<tr>
						<td class='title'>Login Name:</td>
						<td><input name='login_name' type='text' value='<?php echo set_value('login_name'); ?>'/></td>
					</tr>
					<tr>
						<td class="title" >First Name:</td>
						<td><input name="first_name" maxlength="64" type="text" size="30" value="<?php echo set_value('first_name'); ?>" /></td>
					</tr>	
					<tr>
						<td class="title" >Last Name:</td>
						<td><input name="last_name" maxlength="64" type="text" size="30" value="<?php echo set_value('last_name'); ?>"/></td>
					</tr>	
					<tr>
						<td class="title" >Email:</td>
						<td><input name="email" type="text" maxlength="256" size="50" value="<?php echo set_value('email'); ?>"/></td>
					</tr>	
					<tr>
						<td class="title" >Password:</td>
						<td><input name="password" type="password" maxlength="32" /></td>
					</tr>	
					<tr>
						<td class="title"  >Verify Password:</td>
						<td><input name="passconf" type="password" maxlength="32"/></td>
					</tr>
					<tr>
						<td colspan="4"><?php echo validation_errors();  ?></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input type="submit" value="Add User" />
							| <?php echo anchor('admin/', 'Cancel'); ?>
						</td>
					</tr>
				</table>
		<?php echo form_close(); ?>			
		</div>
		<p>Admin Options Section of <?php echo $this->config->item('project_name'); ?></p>
		
</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>