<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Customer Sign In - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Signin Page. Please login" />
	
	<style type='text/css'>
		#signin {
			padding: 5px;
			margin: 5px;
		}
		#login {
			
			padding: 20px;
			padding-top: 0px;
			padding-bottom: 30px;
			width: 350px;
			border: 1px solid #60272F;
			background-color: #eee;
		}
		#login input {
			margin-left: 20px;
		}
		
		#login input[type=submit] {
			font-weight: bold;
			padding-left: 20px;
			padding-right: 20px;
		}
		
		#mid {
			width: 350px;
			text-align: center;
			text-decoration: underline;
		}
		#signup {
			padding: 20px;
			padding-top: 0px;
			width: 350px;
			border: 1px solid #60272F;
			background-color: #eee;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Customer Sign In 
			</div>
			<h2 id='top_h2'>Lang Antiques Customer Sign In</h2>
			<div id='signin'>
				<div id='login'>
					<h3>Login Now!</h3>
					<div>
						<?php echo form_open('user/signin');?>
							<div>
								<label class='input_label'>Email Address:</label>
								<br />
								<input name='email_address' type='text' size='40' />
							</div>
							<div>
								<label class='input_label'>Password: </label>
								<br />
								<input name='password' type='password' size='40' />
							</div>
							<div>
								<?php echo validation_errors(); ?>
							</div>
							<div>
								<input type='submit' value='Sign In!' /> <span style='font-size: 10px;'><?php echo anchor('user/forgot-password', 'Forgot your password?')?></span>
							</div>
						<?php echo form_close();?>
					</div>
				</div>
				<div id='mid'>
					<h2>OR</h2>
				</div>
				<div id='signup'>
					<h3>Register For A Free Account!</h3>
					<div>
						<h4>Click here to <?php echo anchor('user/create-account', 'create an account'); ?>.</h4>
						<p>Benefits for registering an account</p>
						<ul>
							<li>Easy Checkout...</li>
							<li>Receive a copy of our free e-Newsletter...</li>
							<li>Receive our Holiday Greeting Cards...</li>
							<li>Ability to Create a list of Favorite Items...</li>
						</ul>						
					</div>
				</div>
			</div>			
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
