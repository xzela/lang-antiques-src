<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Create Customer Account - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Create Account Page. Please Create an Account With Us." />
	
	<style type='text/css'>
	table.signup_table {
		width: 713px;
		padding: 5px;
		margin: 5px;
		border: 1px solid #A1735E;
		background-color: #FFF;
	}
	
	table.signup_table td.title {
		font-weight: bold;
		text-align: right;
	}
	</style>
</head>
<body>
	<div id="container" >
		<span class="rtop">
			<b class="r1"></b>
			<b class="r2"></b>
			<b class="r3"></b>
			<b class="r4"></b>
		</span>
	<?php
		$this->load->view('components/header_view');
		$this->load->view('components/menu_view');
	?>
		<div id="content">
			<div class="breadcrumb">
				<?php echo anchor('/', 'Home'); ?> &gt; Create A Customer Account
			</div>
			<h2 id='top_h2'>Create A Customer Account With Lang Antiques</h2>
			<h4>Don't Have an Account? Signup is Free!</h4>
			<p>Benefits for creating a free account:</p>
			<ul>
				<li>Easy Checkout...</li>
				<li>Receive a copy of our free e-Newsletter...</li>
				<li>Receive our Holiday Greeting Cards...</li>
				<li>Ability to Create a list of Favorite Items...</li>
			</ul>
			<p>Fill out the following fields to get your free account!</p>
			<?php echo form_open('user/create-account'); ?>
			<table class='signup_table' >
				<tr>
					<td class='title'>First Name:</td>
					<td><input name='first_name' type='text' value='<?php echo set_value('first_name'); ?>' size='25' /></td>
					<td class='title'>Last Name:</td>
					<td><input name='last_name' type='text' value='<?php echo set_value('last_name'); ?>' size='25' /></td>
				</tr>
				<tr>
					<td class='title'>Email:</td>
					<td colspan='3'><input name='email' type='text' value='<?php echo set_value('email'); ?>' size='50' /> this will be your login</td>
				</tr>
				<tr>
					<td class='title'>Password:</td>
					<td><input name='password1' type='password' value='' size='25'/></td>
				</tr>
				<tr>
					<td colspan='4' style='text-align: center;'>
						All Fields are Required.
					</td>
				</tr>
				<tr>
					<td colspan='4' style='text-align: center;'>
						<?php echo validation_errors(); ?>
					</td>
				</tr>				
				<tr>
					<td colspan='4' style='text-align: center;'>
						<input name='account_submit' type='submit' value='Create Account'/>
					</td>
				</tr>
			</table>
			<?php echo form_close();?>		
		</div>
	<?php $this->load->view('components/footer_view.php'); ?>
		<span class="rbottom">
			<b class="r4"></b>
			<b class="r3"></b>
			<b class="r2"></b>
			<b class="r1"></b>
		</span>		
	</div>	
</body>
</html>
<?php
ob_flush();
?>
