<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Checkout - Please Fill Out Your Payment Information - Lang Antiques</title>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.checkout.css'); ?>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>

	$(document).ready(function() {

	});
	</script>
	<style type='text/css'>

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
		$this->load->view('shopping/components/shopping_header_view');
		$this->load->view('shopping/components/shopping_menu_view');
	?>
		<div id="content">
			<div class='checkout_breadcrumb'>
				<ul>
					<li class='completed'>View Cart</li>
					<li>|</li>
					<li class='completed'>Billing/Shipping Information</li>
					<li>|</li>
					<li class='selected'>Payment Information</li>
					<li>|</li>
					<li class='incomplete'>Complete Order</li>
				</ul>
			</div>
			<table class='cart'>
				<tr>
					<th>Item</th>
					<th>Price</th>
				</tr>
				<?php $t_price = 0;?>
				<?php foreach($cart as $item): ?>
					<tr>
						<td class='top'>
							<strong>[#<?php echo $item['item_number'];?>] <?php echo $item['item_name'];?></strong>
							<br />
							<?php echo "<img class='image' src='" . base_url() . "images/thumbnails/75/" . $item['images'][0]['image_id'] . ".jpg' />";?>
								<?php $summary = preg_replace('/<[^>]*>/',"", $item['item_description']); //remove any HTML elements from the text ?>
								<?php if(strlen($item['item_description']) > 300): ?>
									<?php echo substr($summary, 0, 300); ?> ...
								<?php else: ?>
									<?php echo $summary ?>
								<?php endif;?>
						</td>
						<td class='left top'>
							$<?php echo number_format($item['item_price'], 2);?>
						</td>
					</tr>
					<?php $t_price += $item['item_price']; ?>
				<?php endforeach;?>
				<tr>
					<td class='title last'>Shipping:</td>
					<?php $ship = 0; ?>
					<?php if($t_price > 2500): ?>
						<?php $ship = 0; ?>
						<td class='last' nowrap>Shipping Is Free!</td>
					<?php else: ?>
						<?php if($this->session->userdata('shipping') != null && $this->session->userdata('shipping') == '75'): ?>
							<?php $ship = 75; ?>
							<td class='last' nowrap>$75.00 - <i>Overnight</i></td>
						<?php else: ?>
							<?php $ship = $this->session->userdata('shipping'); ?>
							<td class='last' nowrap>$<?php echo number_format($this->session->userdata('shipping'), 2); ?></td>
						<?php endif; ?>
					<?php endif;?>
				</tr>
				<tr>
					<td class='title last'>Tax:</td>
					<?php if(strtolower($customer_data['ship_state']) == 'ca'): ?>
						<?php $t_tax = $t_price * TAX_RATE; ?>
					<?php else: ?>
						<?php $t_tax = 0; ?>
					<?php endif; ?>
					<td class='last'>$<?php echo number_format($t_tax,2); ?></td>
				</tr>
				<tr>
					<td class='title'>Total:</td>
					<?php $price = array('total_price' => $t_price ); ?>
					<?php $this->session->set_userdata($price); ?>
					<?php $tax = array('total_tax' => $t_tax ); ?>
					<?php $this->session->set_userdata($tax); ?>
					<td >$<?php echo number_format($t_price + $ship + $t_tax,2);?></td>
				</tr>
			</table>
			<h2>Please Fill Out Your Payment Information:</h2>
			<p>If you do not wish to use your credit card on-line you may contact us over the phone at <span style='white-space: nowrap;'><b>1-800-924-2213</b></span> to setup an alternative method of payment. When you call please have ready the item number(s) so we may better serve you.</p>
			<?php echo form_open('shopping/payment'); ?>
			<table class='checkout_form_table'>
				<tr>
					<td class='title'>Special Notes:</td>
					<td>
						<textarea id="special_notes" name="special_notes" cols="60" rows="3"><?php echo set_value('special_notes'); ?></textarea>
						<br />
						<span class='faint_text'>Add any notes here such as resizing, shipping instructions, or just to say 'hello'.</span>
					</td>
				</tr>
				<tr>
					<td class='title'>Card Type:</td>
					<td>
						<?php echo form_dropdown('card_type', $card_types, set_value('card_type')); ?>
					</td>
				</tr>
				<tr>
					<td class='title'>Name on Card:</td>
					<td><input type="text" name="card_holder" size="50" /></td>
				</tr>
				<tr>
					<td class='title'>Card Number:</td>
					<td><input type="text" name="card_number" size="40" maxlength="20" /> No Spaces</td>
				</tr>
				<tr>
					<td class='title'>CVV Number:</td>
					<td><input type="text" name="card_cvv" size="5" maxlength="4" /></td>
				</tr>
				<tr>
					<td class='title'>Expiration Date:</td>
					<td>Month:
						<select name="card_month">
							<option value=''></option>
							<option value='01'>01</option>
							<option value='02'>02</option>
							<option value='03'>03</option>
							<option value='04'>04</option>
							<option value='05'>05</option>
							<option value='06'>06</option>
							<option value='07'>07</option>
							<option value='08'>08</option>
							<option value='09'>09</option>
							<option value='10'>10</option>
							<option value='11'>11</option>
							<option value='12'>12</option>
						</select>
						Year:
						<select name="card_year">
						<option value=''></option>
						<?php
							$year = date("Y");
							$maxyear = $year + 9;
							for ($i = $year; $i < $maxyear; ++$i) {
								echo "\t\t\t\t\t\t\t\t<option value='$i'>" . $i . "</option>\n";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'>Agreement:</td>
					<td>
						<?php echo form_checkbox('terms', 'on', set_value('terms')); ?>
						<label>I have read and agree to the <a href="http://www.langantiques.com/shipping-policies/" target="_blank">Terms and Conditions</a></label>
					</td>
				</tr>
				<tr>
					<td></td>
					<td class='warning'><?php echo validation_errors(); ?></td>
				</tr>
				<tr>
					<td></td>
					<td><button type='submit'>Complete My Order <?php echo snappy_image('icons/cart_go.png');?></button></td>
				</tr>
			</table>
			<?php echo form_close(); ?>
			<p>
				Shipping and or Insurance is not refundable.
				Sales tax added on items shipped to California.
				Please read our <?php echo anchor('pages/shipping-policies', 'Policies page')?> before purchasing.
			</p>
			<p>
				<strong>Return Policy:</strong>
			</p>
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