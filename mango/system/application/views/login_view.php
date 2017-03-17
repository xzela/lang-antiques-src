<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />

	<?php echo snappy_style('styles.css'); //autoloaded ?>

	<title>Welcome to <?php echo $this->config->item('project_name'); ?></title>
	<style>
		.login_content {
			background-color: #fff;
			padding: 5px;
			border: 1px solid #D0D0D0;
			height: 170px;
			margin-bottom: 5px;
		}
	</style>
	<script type='text/javascript'>
	window.onload = function() {
		var input = document.getElementById('username_txt');
		input.focus();
	}
	</script>
</head>
<body>
<div id='header'>
<h1>Welcome to <?php echo $this->config->item('project_name'); ?>!</h1>
</div>
<div class='login_content' >
	<div class='login'>
		<?php echo form_open('login', 'name="frmLogin"'); ?>
		<table class='login_table'>
			<tr>
				<td colspan="2" style="text-align: center;"><b>Please Login to Mango</b></td>
			</tr>
			<tr>
				<td style='text-align: right;'>Username:</td>
				<td><input id='username_txt' type="text" name="username" value="<?php echo set_value('username'); ?>" size='45' /> </td>
			</tr>
			<tr>
				<td style='text-align: right;'>Password:</td>
				<td><input type="password" name="password" value="" size='45' /></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<?php echo validation_errors();  ?>
					<?php echo $message; ?>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;" ><input type="submit" value="Submit" /></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
	</div>
	<p>
		<?php echo $this->config->item('project_name'); ?> is an awesome tool used by the best Gemologists and Jewelers of our time.
		Login to use <?php echo $this->config->item('project_name'); ?> and its feature rich applications to help you organize all of your jewelry and gemology related needs (mostly for jewelry though).
	</p>
</div>
<div id="footer">
	<p>&copy; <?php echo $this->config->item('project_name'); ?> is a <a href="http://www.langantiques.com">Lang Antiques</a> Product. All Rights Reserved.</p>
</div>
</body>
</html>