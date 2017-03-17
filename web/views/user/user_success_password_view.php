<?php
ob_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Success! Password Updated!</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>
	
	
	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.item.css'); ?>
	
	<meta name='keywords' content='' />
	<meta name="description" content="Lang Antiques Customer Forgot Customer Password. Please enter your email to recover your password " />
	
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
				<?php echo anchor('/', 'Home'); ?> &gt; Password Updated! 
			</div>
			<h2 id='top_h2'>Success, Password Updated!</h2>
			
			<p>
				You have just reset your password!
				Please click <?php echo anchor('user/signin', 'here to log back in'); ?>.
			</p>
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
