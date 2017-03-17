<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jeditable.css');?>

	<title>Project Mango - Edit Vendor: <?php echo $vendor['name']; ?> </title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>
	<?php echo snappy_script('jquery/jquery.conditional.js'); ?>

	<?php echo snappy_script('vendor/vendor_main.js'); ?>
	<script type="text/javascript">
	var base_url = <?php echo "'" . base_url() . "'"; ?>;
	var id = <?php echo $vendor['vendor_id']; ?>;
	var url = 'vendor/AJAX_updateVendorField';

	$(document).ready(function() {
		$('#mailing_list').bind('change', function() {
			$.post(base_url + url, {
				vendor_id : id,
				id : 'mailing_list',
				value: ($(this).attr('checked')) ? 1:0
				},
				null,
				"json");
		});

		$('.edit,.textarea_edit').bind('keydown', function(event) {
			object = this;
	        if(event.keyCode==9) {
				$(this).find("input").blur();
				$(this).find("textarea").blur();
				$(this).find("select").blur();
				var nextBox='';
				if ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this) == ($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").length-1)) { //at last box
					nextBox=$(".edit:first"); //last box, go to first
				}
				else {
					nextBox = $(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").get($(".edit,.textarea_edit,.select_edit,.edit_money,.edit_title").index(this)+1);
				}
				$(nextBox).click();
				return false;
			}
	    })
		.If(function() {
				return ($(this).attr('class') == 'textarea_edit') ? true : false;
		})
			.editable(base_url + url, {
				submitdata: {
					vendor_id: id
				},
				type: 'textarea',
				rows: '4',
				cols: '50',
				cssclass: 'inplace_field',
				onblur: 'submit'
			})
	    .Else() //default input text field
	    	.editable(base_url + url, {
		    	submitdata: {
	    			vendor_id: id
		    	},
		    	type: 'text',
	    		cssclass: 'inplace_field',
	    		onblur: 'submit'
	    	});
	});
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2 class='item'>Edit Vendor: <?php echo $vendor['name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('vendor', '<< Back to Vendor Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('vendor/merge/' . $vendor['vendor_id'], 'Merge Vendor')?></li>
		</ul>
		<table class='form_table' >
			<tr>
				<td class='title'>Company Name:</td>
				<td colspan='3'>
					<div id='name' name='name' class='edit'><?php echo $vendor['name']; ?></div>
				</td>
			</tr>

			<tr>
				<td class='title'>Fed Tax ID:</td>
				<td colspan='3'>
					<div id='tax_id' name='tax_id' class='edit'><?php echo $vendor['tax_id']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>First Name:</td>
				<td>
					<div id='first_name' name='first_name' class='edit'><?php echo $vendor['first_name']; ?></div>
				</td>
				<td class='title'>Last Name:</td>
				<td>
					<div id='last_name' name='last_name' class='edit'><?php echo $vendor['last_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td></td>
				<td class='title'>Middle Name:</td>
				<td>
					<div id='middle_name' name='middle_name' class='edit'><?php echo $vendor['middle_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Phone Number:</td>
				<td>
					<div id='phone' name='phone' class='edit'><?php echo $vendor['phone']; ?></div>
				</td>
				<td class="title">Fax Number:</td>
				<td>
					<div id='fax' name='fax' class='edit'><?php echo $vendor['fax']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Alt Phone:</td>
				<td >
					<div id='alt_phone' name='alt_phone' class='edit'><?php echo $vendor['alt_phone']; ?></div>
				</td>
				<td class="title">Cell Phone:</td>
				<td >
					<div id='cell_phone' name='cell_phone' class='edit'><?php echo $vendor['cell_phone']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'>
					<div id='email' name='email' class='edit'><?php echo $vendor['email']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 1:</td>
				<td colspan="3" nowrap>
					<div id='address' name='address' class='edit'><?php echo $vendor['address']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 2:</td>
				<td colspan="3" nowrap>
					<div id='address2' name='address' class='edit'><?php echo $vendor['address2']; ?></div>
					<input id='mailing_list' type="checkbox" name="mailing_list" <?php echo ($vendor['mailing_list']?'checked':''); ?> />
					Mailing List
					<div id='mailing_message' style="display:none;">
						<div id='mailing_content' class='warning'></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="title">City:</td>
				<td colspan='3'>
					<div id='city' name='city' class='edit'><?php echo $vendor['city']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">State:</td>
				<td>
					<div id='state' name='state' class='edit'><?php echo $vendor['state']; ?></div>
				</td>
				<td class='title'>Zip:</td>
				<td>
					<div id='zip' name='zip' class='edit'><?php echo $vendor['zip']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<div id='country' name='country' class='edit'><?php echo $vendor['country']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td colspan="3">
					<div id='notes' name='notes' class='textarea_edit'><?php echo $vendor['notes']; ?></div>
				</td>
			</tr>
		</table>
		<h3 class='section'>Shipping Address <span class='small_text'>[<?php echo anchor('vendor/edit_shipping/' . $vendor['vendor_id'], 'Edit Shipping')?>]</span> <span class='small_text'>[<?php echo anchor('vendor/copy_billing_address/' . $vendor['vendor_id'], 'Same As Billing Address')?>]</span></h3>
		<?php if($vendor['has_ship'] == 1):?>
			<table class='form_table'>
				<tr>
					<td class='title'>Contact: </td>
					<td colspan='3'><?php echo $vendor['ship_contact']; ?></td>
				</tr>
				<tr>
					<td class="title">Ship Phone:</td>
					<td><?php echo $vendor['ship_phone']; ?></td>
					<td class="title">Ship Other Phone:</td>
					<td><?php echo $vendor['ship_other_phone']; ?> </td>
				</tr>
				<tr>
					<td class="title">Address:</td>
					<td colspan="3" nowrap>
						<?php echo $vendor['ship_address']; ?> <br />
						<?php echo $vendor['ship_address2']; ?>
					</td>
				</tr>
				<tr>
					<td class="title">City:</td>
					<td><?php echo $vendor['ship_city']; ?></td>
					<td class="title"> State:</td>
					<td><?php echo $vendor['ship_state']; ?> <strong>Zip:</strong><?php echo $vendor['ship_zip']; ?></td>
				</tr>
				<tr>
					<td class="title">Country:</td>
					<td colspan='3'><?php echo $vendor['ship_country']; ?></td>
				</tr>
				<tr>
					<td class='title'></td>
					<td colspan='3'>
						<?php echo form_open('vendor/remove_shipping')?>
							<input type='hidden' name='vendor_id' value='<?php echo $vendor['vendor_id']?>' />
							<input type='submit' class='warning' value='Remove Shipping Address' />
						<?php echo form_close(); ?>
					</td>
				</tr>
			</table>
		<?php else: ?>
			<h4>No Shipping Address found...</h4>
		<?php endif;?>

		<h3 class='section'>Partnerships</h3>
		<table class='customer_table'>
			<tr>
				<th>Item Number</th>
				<th>Cost</th>
				<th>Retail Price</th>
				<th>Their Ownership</th>
				<th>Our Ownership</th>
				<th>Options</th>
			</tr>
			<?php if(sizeof($partnerships) > 0): ?>
				<?php foreach($partnerships as $partnership): ?>
				<tr>
					<td>
						<?php echo anchor('inventory/edit/' . $partnership['item_id'], $partnership['item_data']['item_number'])?>
						<br />
						<?php if(sizeof($partnership['item_data']['image_array']['external_images']) > 0): ?>
							<?php
								echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $partnership['item_data']['image_array']['external_images'][0]['image_location'] . '&image_type=' . $partnership['item_data']['image_array']['external_images'][0]['image_class'] . '&image_size=' . $partnership['item_data']['image_array']['external_images'][0]['image_size'] . "' />";
							?>
						<?php elseif(sizeof($partnership['item_data']['image_array']['internal_images']) > 0):?>
							<?php
							echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $partnership['item_data']['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $partnership['item_data']['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $partnership['item_data']['image_array']['internal_images'][0]['image_size'] . "' />";
							?>
						<?php else: ?>
							<?php echo snappy_image('no_image.jpg', '', '' , 'height="75px" width="75px"'); ?>
						<?php endif; ?>
					</td>
					<td>$<?php echo number_format($partnership['item_data']['purchase_price'], 2); ?></td>
					<td>$<?php echo number_format($partnership['item_data']['item_price'], 2);?></td>
					<td><?php echo number_format($partnership['percentage'],2); ?>%</td>
					<td><?php echo number_format($partnership['our_ownership'], 2);?>%</td>
					<td>
						[<?php echo anchor('inventory/partnership/' . $partnership['item_id'], 'View All Partnerships'); ?>]
						<br />
						[<?php echo anchor('inventory/partnership_edit/' . $partnership['partnership_id'], 'Edit This Partnership'); ?>]
					</td>
				</tr>
				<?php endforeach;?>
			<?php else: ?>
				<tr>
					<td colspan='6'>No Partnerships Found...</td>
				</tr>
			<?php endif;?>
		</table>

		<h3 class='section'>Items They've Purchased From Us</h3>
		<table class='vendor_table'>
			<tr>
				<th nowrap>Invoice ID</th>
				<th nowrap>Item Number</th>
				<th nowrap>Item Name</th>
				<th nowrap>Item Price</th>
				<th nowrap>Item Tax</th>
				<th nowrap>Invoice Status</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($purchased) > 0): ?>
				<?php
				/*
				 * @TODO: remove this logic
				 * Move all of this stuff to Model or Controller
				 *
				 */
					$status_text = array(0 => 'Sold', 1=> 'Pending Sale', 2 => 'Returned', 3=> 'Memo Closed', 4=>'Converted Memo');
					$total_spent = 0;
				?>
				<?php foreach($purchased as $item): ?>
					<?php $total_spent = $total_spent + $item['sale_price']; ?>
					<tr>
						<td><?php echo $item['invoice_id']; ?></td>
						<td>
							<?php if($item['item_id'] != ''): ?>
								<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?> <br />
								<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
									<?php
										echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
									?>
								<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
									<?php
									echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
									?>
								<?php else: ?>
									<?php echo snappy_image('no_image.jpg', '', '' , 'height="75px" width="75px"'); ?>
								<?php endif; ?>
							<?php else: ?>
								No Inventory Items
							<?php endif; ?>
						</td>
						<td><?php echo $item['item_name']; ?></td>
						<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
						<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
						<td><?php echo $status_text[$item['invoice_status']]; ?></td>
						<td>[<?php echo anchor('sales/invoice/' . $item['invoice_id'], 'View Invoice'); ?>]</td>
					</tr>
				<?php endforeach;?>
				<tr>
					<td colspan='3' style='text-align: right; '>Total Spent:</td>
					<td>$<?php echo number_format($total_spent, 2); ?></td>
				</tr>
			<?php else:?>
				<tr>
					<td colspan='7'>No Invoices Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<h3 class='section'>Items We've Purchased From Them</h3>
		<table class='vendor_table'>
			<tr>
				<th>Item Number</th>
				<th>Item Name</th>
				<th>Item Price</th>
				<th>Item Status</th>
				<th>Purchase Date</th>
				<th>Purchase Price</th>
			</tr>
			<?php if(sizeof($sold) > 0): ?>
				<?php
					$status_text = array(0 => 'Sold', 2 => 'Returned');
				?>
				<?php foreach($sold as $item): ?>
					<tr>
						<td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?> <br />
							<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
								<?php
									echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />";
								?>
							<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
								<?php
									echo "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />";
								?>
							<?php else: ?>
								<?php echo snappy_image('no_image.jpg', '', '' , 'height="75px" width="75px"'); ?>
							<?php endif; ?>
						</td>
						<td><?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_name']); ?></td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td><?php echo $item['item_status_text']; ?></td>
						<td><?php echo $item['purchase_date']; ?></td>
						<td>$<?php echo number_format($item['purchase_price'], 2); ?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='6'>No Invoices Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<h3 class='section'>Vendor Returns</h3>
		<table class='customer_table'>
			<tr>
				<th>Return ID </th>
				<th>Invoice ID</th>
				<th>Refund Type</th>
				<th>Return Date</th>
				<th>Refund Amount</th>
			</tr>
			<?php if(sizeof($returns) > 0): ?>
				<?php foreach($returns as $return): ?>
					<tr>
						<td><?php echo anchor('sales/returns/' . $return['return_id'], $return['return_id']); ?></td>
						<td><?php echo anchor('sales/invoice/' . $return['invoice_id'], $return['invoice_id']); ?></td>
						<td><?php echo $refund_types[$return['refund_type']]['name']; ?></td>
						<td><?php echo date('m/d/Y', strtotime($return['date'])); ?></td>
						<td>$<?php echo number_format($return['refund'],2); ?></td>
					</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='5'>No Invoices Found</td>
				</tr>
			<?php endif; ?>
		</table>


		<p>Vendor Section of Project Mango</p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>