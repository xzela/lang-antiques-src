<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Your Items Have Been Reserved! - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.checkout.css'); ?>


	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>
		$(document).ready(function() {
		});
	</script>

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
			<div class='checkout_breadcrumb'>
				<ul>
					<li class='completed'>View Cart</li>
					<li>|</li>
					<li class='completed'>Billing/Shipping Information</li>
					<li>|</li>
					<li class='completed'>Payment Information</li>
					<li>|</li>
					<li class='completed'>Complete Order</li>
				</ul>
			</div>
			<p></p>
			<h2 id='top_h2'>Your Items Have Been Reserved - Thank You!</h2>
			<p>
				Thank you for shopping at Lang Antiques.
			</p>
			<p>
				Your order has been placed and you will soon receive an email describing your order.
				<strong>Please contact us during normal business personal service regarding payment.</strong>
				Once again, thank you for shopping at Lang Antiques.
			</p>
			<p>
				If you have any questions, feel free to contact us via phone <b>(800) 924-2213</b> at or email at <a href="mailto:info@langantiques.com">info@langantiques.com</a> anytime.
				We will gladly assist you and answer any questions you may have.
			</p>
			<div style="clear: both">&nbsp;</div>
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
