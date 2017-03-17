<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('jquery.jeditable.css');?>

	<title>Project Mango - Edit Customer </title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>
	<?php echo snappy_script('jquery/jquery.conditional.js'); ?>

	<script type="text/javascript">
	var base_url = <?php echo "'" . base_url() . "'"; ?>;
	var id = <?php echo $customer['customer_id']; ?>;
	var url = 'customer/AJAX_updateCustomerField';

	$(document).ready(function() {
		$('#mailing_list').bind('change', function() {
			$.post(base_url + url, {
				customer_id : id,
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
					customer_id: id
				},
				type: 'textarea',
				rows: '5',
				cols: '50',
				cssclass: 'inplace_field',
				onblur: 'submit'
			})
	    .Else() //default input text field
	    	.editable(base_url + url, {
		    	submitdata: {
	    			customer_id: id
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
		<h2 class='item'>Edit Customer: <?php echo $customer['first_name'] . ' ' . $customer['last_name']; ?></h2>
		<ul id="submenu">
			<li><?php echo anchor('customer', snappy_image('icons/resultset_previous.png', '', 'pagination_image') . ' ' . 'Back to Customer Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/add_job/' . $customer['customer_id'], 'Create A Customer Job'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/jobs/' . $customer['customer_id'], 'Customer Jobs'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/special_orders/' . $customer['customer_id'], 'Customer Special Orders'); ?></li>
			<li>|</li>
			<li><?php echo anchor('customer/merge/' . $customer['customer_id'], 'Merge Customers'); ?></li>
		</ul>
		<h3>Billing Address</h3>
		<table class='form_table' >
			<tr>
				<td class='title' >Customer ID: </td>
				<td colspan='3'><div style='width: 400px'><?php echo $customer['customer_id']; ?></div></td>
			</tr>
			<tr>
				<td class='title'>Account: </td>
				<td colspan='3'>
					<?php if($customer['password'] != null): ?>
						Has Lang Account [<?php echo anchor('customer/reset_password/' . $customer['customer_id'], 'Reset Password'); ?>]
					<?php else: ?>
						N/A
					<?php endif;?>
				</td>
			</tr>
			<tr>
				<td class='title'>First:</td>
				<td>
					<div id='first_name' name='first_name' class='edit'><?php echo $customer['first_name']; ?></div>
				</td>
				<td class='title'>Last:</td>
				<td>
					<div id='last_name' name="last_name" class='edit'><?php echo $customer['last_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td></td>
				<td class='title'>Middle:</td>
				<td>
					<div id='middle_name' name="middle_name" class='edit'><?php echo $customer['middle_name']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Spouse First:</td>
				<td>
					<div id='spouse_first' name='spouse_first' class='edit'><?php echo $customer['spouse_first']; ?></div>
				</td>
				<td class='title'>Spouse Last:</td>
				<td>
					<div id='spouse_last' name='spouse_last' class='edit' ><?php echo $customer['spouse_last']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td></td>
				<td class='title'>Spouse Middle:</td>
				<td>
					<div id='spouse_middle' name="spouse_middle" class='edit'><?php echo $customer['spouse_middle']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Home Phone:</td>
				<td>
					<div id='home_phone' name='home_phone' class='edit' ><?php echo $customer['home_phone']; ?></div>
				</td>
				<td class="title">Work Phone:</td>
				<td>
					<div id='work_phone' name='work_phone' class='edit' ><?php echo $customer['work_phone']; ?></div>
				</td>
			</tr>
			<tr>
				<td class='title'>Cell Phone:</td>
				<td>
					<div id='cell_phone' name='cell_phone' class='edit' ><?php echo $customer['cell_phone']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Email:</td>
				<td colspan='3'>
					<div id='email' name='email' class='edit' ><?php echo $customer['email']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 1:</td>
				<td colspan="3" nowrap>
					<div id='address' name='address' class='edit' ><?php echo $customer['address']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Address Line 2:</td>
				<td colspan="3" nowrap>
					<div id='address2' name='address2' class='edit' ><?php echo $customer['address2']; ?></div>
					<input id='mailing_list' type="checkbox" name="mailing_list" <?php echo ($customer['mailing_list']?'checked':''); ?> />
					Mailing List
					<div id='mailing_message' style="display:none;">
						<div id='mailing_content' class='warning'></div>
					</div>
				</td>
			</tr>
			<tr>
				<td class="title">City:</td>
				<td>
					<div id='city' name='city' class='edit' ><?php echo $customer['city']; ?></div>
				</td>
				<td class="title">State <br /> <br /> Zip:</td>
				<td>
					<div id='state' name='state' class='edit' ><?php echo $customer['state']; ?></div>
					<div id='zip' name='zip' class='edit' ><?php echo $customer['zip']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Country:</td>
				<td colspan='3'>
					<div id='country' name='country' class='edit' ><?php echo $customer['country']; ?></div>
				</td>
			</tr>
			<tr>
				<td class="title">Notes:</td>
				<td colspan="3">
					<div id='notes' name='notes' class='textarea_edit'><?php echo $customer['notes']; ?></div>
				</td>
			</tr>
		</table>

		<h3 class='section'>Shipping Address <span class='small_text'>[<?php echo anchor('customer/edit_shipping/' . $customer['customer_id'], 'Edit Shipping')?>]</span> <span class='small_text'>[<?php echo anchor('customer/copy_billing_address/' . $customer['customer_id'], 'Same As Billing Address')?>]</span></h3>
		<?php if($customer['has_ship'] == 1):?>
			<table class='form_table'>
				<tr>
					<td class='title'>Contact: </td>
					<td colspan='3'><?php echo $customer['ship_contact']; ?></td>
				</tr>
				<tr>
					<td class="title">Ship Phone:</td>
					<td><?php echo $customer['ship_phone']; ?></td>
					<td class="title">Ship Other Phone:</td>
					<td><?php echo $customer['ship_other_phone']; ?> </td>
				</tr>
				<tr>
					<td class="title">Address:</td>
					<td colspan="3" >
						<?php echo $customer['ship_address']; ?><br />
						<?php echo $customer['ship_address2']; ?>
					</td>
				</tr>
				<tr>
					<td class="title">City:</td>
					<td><?php echo $customer['ship_city']; ?></td>
					<td class="title"> State:</td>
					<td><?php echo $customer['ship_state']; ?> <strong>Zip: </strong><?php echo $customer['ship_zip']; ?></td>
				</tr>
				<tr>
					<td class="title">Country:</td>
					<td colspan='3'><?php echo $customer['ship_country']; ?></td>
				</tr>
				<tr>
					<td class='title'></td>
					<td colspan='3'>
						<?php echo form_open('customer/remove_shipping')?>
							<input type='hidden' name='customer_id' value='<?php echo $customer['customer_id']?>' />
							<input type='submit' class='warning' value='Remove Shipping Address' />
						<?php echo form_close(); ?>
					</td>
				</tr>
			</table>
		<?php else: ?>
			<h4>No Shipping Address found...</h4>
		<?php endif;?>
		<h3 class='section' >Store Credit <span class='small_text'>[<?php echo anchor('customer/edit_store_credit/' . $customer['customer_id'] . '/add', 'Add Store Credit');?>] [<?php echo anchor('customer/edit_store_credit/' . $customer['customer_id'] . '/subtract', 'Subtract Store Credit')?>]</span></h3>
		<table class='customer_table'>
			<tr>
				<th nowrap>Store Credit ID</th>
				<th nowrap>Invoice ID</th>
				<th nowrap>Credit Amount</th>
				<th nowrap>Transaction</th>
				<th nowrap>Description</th>
				<th nowrap>Options</th>
			</tr>
			<?php if(sizeof($store_credit) > 0): ?>
				<?php
					$trans_array = array(0 => '<span class="warning">Subtraction</span>', 1 => 'Addition', 3 => 'Manual Add', 4 => '<span class="warning">Manual Subtraction</span>');
					$total_credit = 0;
				?>
				<?php foreach($store_credit as $credit): ?>
					<tr>
						<td><?php echo $credit['store_credit_id']; ?></td>
						<?php if($credit['invoice_id'] != 0): ?>
							<td><?php echo anchor('sales/invoice/' . $credit['invoice_id'], $credit['invoice_id']); ?></td>
						<?php else: ?>
							<td>No Invoice</td>
						<?php endif;?>

						<?php if($credit['action_type'] == 0 || $credit['action_type'] == 4):?>
							<td class='warning'>-$<?php echo number_format($credit['credit_amount'], 2); ?></td>
							<?php $total_credit = $total_credit - $credit['credit_amount']; ?>
						<?php else: ?>
							<td>$<?php echo number_format($credit['credit_amount'], 2); ?></td>
							<?php $total_credit = $total_credit + $credit['credit_amount']; ?>
						<?php endif; ?>
						<td><?php echo $trans_array[$credit['action_type']]; ?></td>
						<td><?php echo $credit['item_description']; ?></td>
						<td>[<?php echo anchor('customer/store_credit_delete/' . $customer['customer_id'] . '/' . $credit['store_credit_id'] , 'Delete')?>]</td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<td colspan='3' style='text-align: right; font-weight: bold;'>Total Store Credit:</td>
					<td >$<?php echo number_format($total_credit, 2); ?></td>
				</tr>
			<?php else: ?>
				<tr>
					<td colspan='4' >No Store Credit Transactions</td>
				</tr>
			<?php endif; ?>
		</table>

		<h3 class='section'>Items They've Purchased From Us</h3>
		<table class='customer_table'>
			<tr>
				<th width='90px;'>Invoice ID</th>
				<th width='150px;'>Item Number</th>
				<th>Item Name</th>
				<th width='75px;'>Invoice Date</th>
				<th width='75px;'>Item Price</th>
				<th width='75px;'>Item Tax</th>
				<th width='75px;'>Invoice Price</th>
				<th width='100px;'>Item Status</th>
				<th width='100px;'>Options</th>
			</tr>
			<?php if(sizeof($purchased) > 0): ?>
				<?php
				/*
				 * @TODO: remove this logic
				 * Move all of this stuff to Model or Controller
				 *
				 */
					unset($purchased['num_rows']); //removes the num_rows from array
					$i_status_text = array('' => '', 0 => 'Sold', 1 => 'Returned', 2 => 'Pending Return');
					$total_spent = 0;
				?>
				<?php foreach($purchased as $item): ?>
					<?php
						if($item['i_item_status'] != 1) {
							$total_spent += ($item['sale_price'] + $item['sale_tax']);
						}
					?>
					<tr>
						<td><?php echo anchor('sales/invoice/' . $item['invoice_id'], $item['invoice_id']); ?></td>
						<td>
							<?php if($item['item_id'] != ''): ?>
								<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?>
								<br />
								<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
									<?php
										echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
									?>
								<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
									<?php
									echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
									?>
								<?php else: ?>
									No Image Provided
								<?php endif; ?>
							<?php else: ?>
							No Inventory Items
							<?php endif;?>
						</td>
						<td><?php echo $item['item_name']; ?></td>
						<td><?php echo date('m/d/Y', strtotime($item['sale_date'])); ?></td>
						<td class='center'>$<?php echo number_format($item['sale_price'], 2); ?></td>
						<td class='center'>$<?php echo number_format($item['sale_tax'], 2); ?></td>
						<td class='center'>$<?php echo number_format($item['total_price'], 2); ?></td>
						<td class='center'><?php echo $i_status_text[$item['i_item_status']]; ?></td>
						<td>[<?php echo anchor('sales/invoice/' . $item['invoice_id'], 'View Invoice'); ?>]</td>
					</tr>
				<?php endforeach;?>
				<tr>
					<td colspan='5' style='text-align: right; '>Total Spent:</td>
					<td class='center'>$<?php echo number_format($total_spent, 2); ?></td>
				</tr>
			<?php else:?>
				<tr>
					<td colspan='7'>No Invoices Found</td>
				</tr>
			<?php endif; ?>
		</table>
		<h3 class='section'>Items We've Purchased From Them</h3>
		<table class='customer_table'>
			<tr>
				<th>Item Number</th>
				<th>Item Name</th>
				<th>Item Price</th>
				<th>Purchase Date</th>
				<th>Purchase Price</th>
			</tr>
			<?php if(sizeof($sold) > 0): ?>
				<?php
				/*
				 * @TODO: remove this logic
				 * Move all of this stuff to Model or Controller
				 *
				 */
					$status_text = array(0 => 'Sold', 2 => 'Returned');
				?>
				<?php foreach($sold as $item): ?>
					<tr>
						<td>
							<?php echo $item['item_number']; ?> <?php echo $item['item_status_text']; ?>
								<br />
								<?php if(sizeof($item['image_array']['external_images']) > 0): ?>
									<?php
										echo anchor('inventory/edit/' . $item['item_id'] , "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['external_images'][0]['image_location'] . '&image_type=' . $item['image_array']['external_images'][0]['image_class'] . '&image_size=' . $item['image_array']['external_images'][0]['image_size'] . "' />");
									?>
								<?php elseif(sizeof($item['image_array']['internal_images']) > 0):?>
									<?php
									echo anchor('inventory/edit/' . $item['item_id'], "<img src='" . base_url() . 'system/application/views/_global/thumbnail.php?image_location=' . $item['image_array']['internal_images'][0]['image_location'] . '&image_type=' . $item['image_array']['internal_images'][0]['image_class'] . '&image_size=' . $item['image_array']['internal_images'][0]['image_size'] . "' />");
									?>
								<?php else: ?>
									No Image Provided
								<?php endif; ?>
						</td>
						<td>
							<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_name']); ?>
						</td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
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
		<h3 class='section'>Customer Returns</h3>
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

		<p>Customer Section of Project Mango</p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>