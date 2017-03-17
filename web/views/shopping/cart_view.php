<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>View Your Shopping Cart - Lang Antiques</title>
	<?php echo snappy_script('browser.selector.js');?>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.checkout.css'); ?>

	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>

	$(document).ready(function() {
		$('#back_shopping').bind('click', function() {
				history.go(-2);
		});
	});
	</script>
	<style>
		table.cart {
			width: 100%;
			border: 1px solid #ccc;
			border-collapse: collapse;
		}
		table.cart th {
			background-color: #ddd;
		}
		table.cart td {
			vertical-align: top;
		}
		table.cart td img.image {
			padding: 5px;
			float: left;
		}


		table.cart td.title {
			text-align: right;
			font-weight: bold;
		}

		table.cart td.left {
			border-left: 1px dashed #ccc;
		}

		table.cart td.top {
			border-top: 1px solid #ddd;
		}

		table.cart td.last {
			border-top: 1px dashed #ccc;
		}

		table td input {
			color: red;
		}
		table button {
			font-size: 12px;
			color: #990000;
			font-weight: bold;
			cursor: pointer;
		}

		table button img {
			vertical-align: top;
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
				<?php echo anchor('/', 'Home'); ?> &gt; Your Shopping Cart
			</div>
			<h2 id='top_h2'>Your Shopping Cart</h2>
			<table class='cart'>
				<tr>
					<th>Item</th>
					<th>Price</th>
				</tr>
				<?php if(sizeof($cart) > 0): ?>
					<?php $t_price = 0;?>
					<?php foreach($cart as $item): ?>
						<tr>
							<td class='top'>
								<strong>[#<?php echo $item['item_number'];?>] <?php echo $item['item_name'];?></strong>
								<br />
								<?php echo "<img class='image' src='" . base_url() . "images/thumbnails/125/" . $item['images'][0]['image_id'] . ".jpg' />";?>
								<?php $summary = preg_replace('/<[^>]*>/',"", $item['item_description']); //remove any HTML elements from the text ?>
									<?php if(strlen($item['item_description']) > 300): ?>
										<?php echo substr($summary, 0, 300); ?> ...
									<?php else: ?>
										<?php echo $summary ?>
									<?php endif;?>
							</td>
							<td class='left top'>
								$<?php echo number_format($item['item_price'], 2);?>
								<br />
								<?php echo form_open('shopping/remove');?>
									<input name='item_id' type='hidden' value='<?php echo $item['item_id']; ?>' />
									<input type='submit' value='Remove Item' />
								<?php echo form_close();?>
							</td>
						</tr>
						<?php $t_price += $item['item_price']; ?>
					<?php endforeach;?>
					<tr>
						<td class='title last'>Shipping:</td>
						<?php $ship = 0; ?>
						<?php if($t_price > 2500): ?>
							<?php $ship = 0; ?>
							<td class='last' colspan='2'>Shipping Is Free!</td>
						<?php else: ?>
							<?php $ship = 45; ?>
							<td class='last' colspan='2'>$45.00</td>
						<?php endif;?>
					</tr>
					<tr>
						<td class='title last'>Tax:</td>
						<td class='last' colspan='2'><i>Shipping Address Required</i></td>
					</tr>
					<tr>
						<td class='title'>Total:</td>
						<td colspan='2'>$<?php echo number_format($t_price + $ship,2);?></td>
					</tr>
					<tr>
						<td style='text-align: center;' colspan='3'>
							<?php echo form_open('shopping/check-out'); ?>
							<a href='javascript:history.go(-2);' >Continue Shopping</a> |
							<button id='to_checkout' type='submit'>Proceed to Checkout <?php echo snappy_image('icons/cart_go.png');?></button>
							<?php echo form_close();?>
						</td>
					</tr>
				<?php else: ?>
					<tr>
						<td colspan='3'>Hmm, Looks like your cart is empty. Please feel free to look around our site to find that special something.</td>
					</tr>
				<?php endif;?>
			</table>
<!-- 			<div style="border: 1px solid #666; padding: 10px; margin: 5px; margin-bottom: 20px; border-radius: 5px; background-color: #f1f1f1;">
				<h2>Holiday Shipping Notice:</h2>
				<p style="font-weight: bold;">
					Last day for 2 day Fed Ex to be delivered by Christmas - <u>Thursday December 20th</u>
					<br />
					Last day for Overnight Fed Ex to be delivered by Christmas - <u>Saturday December 22th</u>
					<br />
					(Orders must be placed by 3PM)
				</p>
			</div>
			<div style="border: 1px solid #666; padding: 10px; margin: 5px; margin-bottom: 20px; border-radius: 5px; background-color: #f1f1f1;">
			<h2>Holiday Store Hours: December 10th - 24th</h2>
				<table style='text-align: left;'>
					<tr>
						<td>Monday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Tuesday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Wednesday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Thursday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Friday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Saturday</td>
						<td>10:30am - 6:00pm</td>
					</tr>
					<tr>
						<td>Sunday</td>
						<td>11:00am - 5:00pm</td>
					</tr>
				</table>
			</div> -->
			<p>We accept the following payement types for on-line purchases: </p>
			<div>
				<?php echo snappy_image('vendor_logos/amex_logo.jpg', 'American Express'); ?>
				<?php echo snappy_image('vendor_logos/discover_card_logo.jpg', 'Discover Card'); ?>
				<?php echo snappy_image('vendor_logos/master_card_logo.jpg', 'Master Card'); ?>
				<?php echo snappy_image('vendor_logos/visa_logo.jpg', 'VISA'); ?>
			</div>
			<p>
				If you do not wish to use your credit card on-line you may contact us over the phone at <span style='white-space: nowrap;'><b>1-800-924-2213</b></span> to setup an alternative method of payment.
				When you call please have ready the item number(s) so we may better serve you.
			</p>
			<p>
				Shipping and or Insurance is not refundable.
				Sales tax added on items shipped to California.
				Please read our <?php echo anchor('pages/shipping-policies', 'Policies page')?> before purchasing.
			</p>
			<p>
				<strong>Return Policy:</strong>
			</p>
			<p>
				You may return your online purchase for a full refund (less shipping), for any reason, within 10 days of the date you receive it.
				Please contact us for return instructions.
				In store purchases may be returned for store credit only within 30 days of the original sale.
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
