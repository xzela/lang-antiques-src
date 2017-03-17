<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<title>Checkout - Please Fill Out These Forms - Lang Antiques</title>
	<?php //global style sheets, browser independent; ?>
	<?php echo snappy_style('global.styles.css'); ?>
	<?php echo snappy_style('global.round.css'); ?>


	<?php echo snappy_style('firefox.main.css'); ?>
	<?php echo snappy_style('firefox.checkout.css'); ?>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
	<script type='text/javascript'>

	$(document).ready(function() {
		$('#ship_bill').bind('click', function() {
			if($(this).attr('checked')) {
				$('#ship_contact').val($('#first_name').val() + ' ' + $('#last_name').val());
				$('#ship_phone').val($('#home_phone').val());
				$('#ship_address').val($('#address').val());
				$('#ship_address2').val($('#address2').val());
				$('#ship_city').val($('#city').val());
				$('#ship_state').val($('#state').val());
				$('#ship_zip').val($('#zip').val());
				$('#ship_country').val($('#country').val());
			}
			else {
				$('#ship_contact').val('');
				$('#ship_phone').val('');
				$('#ship_address').val('');
				$('#ship_address2').val('');
				$('#ship_city').val('');
				$('#ship_state').val('');
				$('#ship_zip').val('');
				$('#ship_country').val('');
			}
		});
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
					<li class='selected'>Billing/Shipping Information</li>
					<li>|</li>
					<li class='incomplete'>Payment Information</li>
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
						<?php if($t_price <= 1000): ?>
							<?php $ship = 25; ?>
						<?php elseif($t_price >= 1001 || $t_price <= 1500 ): ?>
							<?php $ship = 30; ?>
						<?php elseif($t_price >= 1501 || $t_price <= 2000): ?>
							<?php $ship = 35; ?>
						<?php elseif($t_price >= 2001 || $t_price <= 2499): ?>
							<?php $ship = 40; ?>
						<?php endif; ?>
						<?php if($this->session->userdata('shipping') != null && $this->session->userdata('shipping') == '75'): ?>
							<?php $ship = 75; ?>
							<td class='last' >$75.00 - <i>Overnight</i></td>
						<?php else: ?>
							<td class='last' >$<?php echo number_format($ship, 2); ?></td>
						<?php endif; ?>
					<?php endif;?>
				</tr>
				<tr>
					<td class='title last'>Tax:</td>
					<td class='last' nowrap title='Tax is based on shipping address'><i>Shipping Address Required</i></td>
				</tr>
				<tr>
					<td class='title'>Total:</td>
					<td >$<?php echo number_format($t_price + $ship,2);?></td>
				</tr>
			</table>
			<p class='warning'>
				<strong>PURCHASES MAY NEED ADDITIONAL VERIFICATION.</strong>
				<br />
				This may delay the processing of your sale. Please provide the best phone number to reach you.
				We may have to contect you prior to processing your order.
			</p>
			<?php echo form_open('shopping/check-out'); ?>
			<table class="checkout_form_table">
				<tr>
					<td colspan='2' class='ship_break' style='border-top: 1px solid #fff;'>
						<p>
							Our bank requires that we ship to your billing address.
							If you have an American Express you can register an alternate shipping address that we can verify.
							If you pay by wire transfer we can ship any where you request.
						</p>
						<p>
							Please Enter Your Billing Address:
						</p>
					</td>
				</tr>
				<tr>
					<td class='title'>First Name:</td>
					<td><input id='first_name' type="text" name="first_name" value="<?php echo set_value('first_name', $customer_data['first_name']); ?>" /></td>
				</tr>

				<tr>
					<td class='title'>Last Name:</td>
					<td><input id='last_name' type="text" name="last_name" value="<?php echo set_value('last_name', $customer_data['last_name'])?>" /></td>
				</tr>
				<tr>
					<td class='title'>Phone:</td>
					<td><input id='home_phone' type="text" name="home_phone" maxlength="20" value="<?php echo set_value('home_phone', $customer_data['home_phone'])?>" /></td>
				</tr>

				<tr>
					<td class='title'>Email:</td>
					<td><input type="text" name="email" size="50" value="<?php echo set_value('email', $customer_data['email'])?>" /></td>
				</tr>
				<tr>
					<td class='title'>Address Line 1:</td>
					<td><input id='address' type="text" name="address" size="50" value="<?php echo set_value('address', $customer_data['address'])?>" /></td>
				</tr>
				<tr>
					<td class='title'>Address Line 2:</td>
					<td><input id='address2' type="text" name="address2" size="50" value="<?php echo set_value('address2', $customer_data['address2'])?>" /></td>
				</tr>

				<tr>
					<td class='title'>City:</td>
					<td><input id='city' type="text" name="city" value="<?php echo set_value('city', $customer_data['city'])?>" /></td>
				</tr>
				<tr>
					<td class='title'>State/Zip:</td>
					<td>
						<input id='state' type="text" name="state" size="2" maxlength="2" type="text" value="<?php echo set_value('state', $customer_data['state'])?>" /> /
						<input id='zip' type="text" name="zip" size="10" maxlength="10" type="text" value="<?php echo set_value('zip', $customer_data['zip'])?>" />
					</td>
				</tr>
				<tr>
					<td class='title'>Country:</td>
					<td><input id='country' type="text" name="country" value="<?php echo set_value('country', $customer_data['country'])?>" /></td>
				</tr>
<!-- 				<tr>
					<td colspan='2' class='ship_break'>Please Enter Your Shipping Address:</td>
				</tr> -->
				<tr>
					<td class='title'>Shipping Contact:</td>
					<td>
						<input id='ship_contact' type='text' name='ship_contact' value="<?php echo set_value('ship_contact', $customer_data['ship_contact']); ?>" />
					</td>
				</tr>
				<tr>
					<td class='title'>Shipping Phone:</td>
					<td><input id='ship_phone' type='text' name='ship_phone' value="<?php echo set_value('ship_phone', $customer_data['ship_phone']) ?>" /></td>
				</tr>
<!-- 				<tr>
					<td class='title'></td>
					<td>
						<input id='ship_bill' type='checkbox' />
						Billing and Shipping Address are the same
					</td>
				</tr>
				<tr>
					<td class='title'>Address Line 1:</td>
					<td><input id='ship_address' type="text" name="ship_address" size="50" value="<?php echo set_value('ship_address', $customer_data['ship_address']) ?>" /></td>
				</tr>
				<tr>
					<td class='title'>Address Line 2:</td>
					<td><input id='ship_address2' type="text" name="ship_address2" size="50" value="<?php echo set_value('ship_address2', $customer_data['ship_address2']) ?>" /></td>
				</tr>
				<tr>
					<td class='title'>City:</td>
					<td><input id='ship_city' type="text" name="ship_city" value="<?php echo set_value('ship_city', $customer_data['ship_city']);?>" /></td>
				</tr>
				<tr>
					<td class='title'>State/Zip:</td>
					<td>
						<input type="text" id="ship_state" name="ship_state" size="2" maxlength="2" type="text" value="<?php echo set_value('ship_state', $customer_data['ship_state']);?>" /> /
						<input type="text" id="ship_zip" name="ship_zip" size="5" maxlength="5" type="text" value="<?php echo set_value('ship_zip', $customer_data['ship_zip']);?>" />
					</td>
				</tr>
				<tr>
					<td class='title'>Country:</td>
					<td><input type="text" id="ship_country"  name="ship_country" value="<?php echo set_value('ship_country', $customer_data['ship_country']);?>" /></td>
				</tr> -->
				<tr>
					<td colspan='2' class='ship_break'>Please Choose A Shipping Method:</td>
				</tr>
				<tr>
					<td class='title'>Shipping Method:</td>
					<td>
						<select name='shipping_type'>
						<?php if($t_price > 2500): ?>
							<option value='free'>Shipping Is Free!</option>
						<?php else: ?>
							<?php if($this->session->userdata('shipping') != null && $this->session->userdata('shipping') == '75'): ?>
								<option value='<?php echo $ship; ?>'>FedEx 2nd Day - $<?php echo number_format($ship, 2); ?></option>
								<option value='75' selected>Overnight - $75.00</option>
							<?php else: ?>
								<option value='<?php echo $ship; ?>'>FedEx 2nd Day - $<?php echo number_format($ship, 2); ?></option>
								<option value='75'>Overnight - $75.00</option>
							<?php endif;?>
						<?php endif;?>
						</select>
					</td>
				</tr>
				<tr>
					<td class='title'></td>
					<td class='warning'>
						<?php echo validation_errors();?>
					</td>
				</tr>
				<tr>
					<td class='title'></td>
					<td>
						<input name='total_amount' type='hidden' value='<?php echo $t_price +$ship; ?>' />
						<button id='to_checkout' type='submit'>Proceed to Payment Information <?php echo snappy_image('icons/cart_go.png');?></button>
					</td>
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