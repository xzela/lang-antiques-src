<?php
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	<?php echo snappy_style('calendar.css');?>
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Return Invoice</title>
	<?php echo snappy_script('calendar_us.js'); ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>

	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		var json = {"fields" : {}};

		$(document).ready(function () {
			var content = $('.input_field').val();

			$('.item').bind('click', function(e) {
				var url = 'sales/AJAX_updateInvoiceItemField';
				var id = $(this).attr('id');
				var status = 1;
				if($('#msg_' + id).is(':hidden')) {
					$('#msg_' + id).slideDown('slow');
					$(this).html('Keep This Item');
					status = 2;
				}
				else {
					$('#msg_' + id).slideUp('slow');
					$(this).html('Return This Item');
					status = 0;
				}
				$.post(base_url + url, {
					invoice_item_id : id,
					id: 'item_status',
					value: status
				});
			});
			$('.special').bind('click', function(e) {
				var url = 'sales/AJAX_updateSpecialItemField';
				var id = $(this).attr('id');
				var status = 1;
				if($('#msg_' + id).is(':hidden')) {
					$('#msg_' + id).slideDown('slow');
					$(this).html('Keep This Item');
					status = 2;
				}
				else {
					$('#msg_' + id).slideUp('slow');
					$(this).html('Return This Item');
					status = 0;
				}
				$.post(base_url + url, {
					special_item_id : id,
					id: 'item_status',
					value: status
				});
			});

			$.each($('.input_field'), function(i, val) {
				json.fields[i] = {"name" : this.name, "value" : this.value};
			});

			$('.input_field').bind('keyup change', function(event) {
				var index = $('.input_field').index(this);
				var content = json.fields[index].value;

				//alert(content + '=' + $(this).val());
				var div = $('#change_message');
				if($(this).val() != content) {
					$(this).css('color', 'red');
					$(this).css('border', '1px solid red');
					if(div.is(':hidden')) {
						div.slideDown('slow');
					}
				}
				else {
					$(this).css('color', 'black');
					$(this).css('border', '1px solid #333333');
					var b = true;
					$.each($('.input_field'), function(i, val) {
						if(this.value != json.fields[i].value) {
							b = false;
						}
					});
					if(b) {
						div.slideUp('slow');
					}
				}
			});
		});
	</script>
	<style type='text/css'>
		.dead_link {
			color: #666666;
		}
		.dead_link:hover {
			color: #666666;
			text-decoration: none;
			cursor:text;
		}
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>
			Invoices - View Invoice #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
		</ul>
		<p>
			Returning Items is a <strong class='warning'>three</strong> part process.
			First you must select which items you wish to return.
			Then you must select which date these items are being returned.
			Lastly, you may modify the final amount.
		</p>

		<table class='form_table'>
			<tr>
				<td class='title'>Invoice ID:</td>
				<td><?php echo $invoice_data['invoice_id']; ?></td>
				<td class='title'>Sales Slip Number:</td>
				<td><?php echo $invoice_data['sales_slip_number']; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Date:</td>
				<td><?php echo $invoice_data['sale_date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($invoice_data['sale_date'])); ?></td>
				<td class='title'>Sales Person:</td>
				<td><?php echo $sales_person['first_name'] . ' ' . $sales_person['last_name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td>
					<?php echo $invoice_type_text[$invoice_data['invoice_type']]; ?>
				</td>
				<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif; ?>
			</tr>
			<tr>
				<td colspan='4'><?php echo validation_errors(); ?></td>
			</tr>
		</table>
		<h2 class='warning'>Step 1: Pick some items</h2>
		<h3>The following items are eligible for Return</h3>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap>Sale Price</th>
				<th nowrap>Option</th>
			</tr>
			<?php if(sizeof($invoice_items) > 0 ):?>
				<?php foreach($invoice_items as $item):?>
				<tr>
					<td>
						<div style='text-align: center;'><?php echo $item['item_number']; ?></div>
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
						<div class='item_name'><?php echo $item['item_name'];?></div>
						<p><?php echo $item['item_description'];?></p>
						<?php if($item['invoice_item_status'] == 1):?>
							<p class='warning large_text'>This item has been returned!</p>
						<?php endif;?>
					</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td nowrap>
						<?php if($item['invoice_item_status'] == 1):?>
							<div class='warning'>This item has been returned.</div>
						<?php elseif($item['invoice_item_status'] == 2):?>
							<div id='msg_<?php echo $item['invoice_item_id'];?>' style='display:display;' class='warning'>Pending Return</div>
							[<a id='<?php echo $item['invoice_item_id'];?>' class='item' href='javascript:void(0)'>Keep This Item</a>]
						<?php else:?>
							<div id='msg_<?php echo $item['invoice_item_id'];?>' style='display:none;' class='warning'>Pending Return</div>
							[<a id='<?php echo $item['invoice_item_id'];?>' class='item' href='javascript:void(0)' >Return This Item</a>]
						<?php endif;?>
					</td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Inventory Items Found</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='4' class='top_lite header'>Special Orders</td>
			</tr>
			<?php if(sizeof($special_items) > 0):?>
				<?php foreach($special_items as $item):?>
					<tr>
						<td colspan='2'>
							<?php echo $item['item_description']; ?>
							<?php if($item['item_status'] == 1):?>
								<p class='warning large_text'>This item has been returned!</p>
							<?php endif;?>

						</td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td nowrap>
							<?php if($item['item_status'] == 1):?>
								<span>This item has been returned</span>
							<?php elseif($item['item_status'] == 2):?>
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:display;' class='warning'>Pending Return</div>
								[<a id='<?php echo $item['special_item_id'];?>' href='javascript:void(0)' class='special' >Keep This Item</a>]
							<?php else:?>
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:none;' class='warning'>Pending Return</div>
								[<a id='<?php echo $item['special_item_id'];?>' href='javascript:void(0)' class='special' >Return This Item</a>]
							<?php endif;?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Special Orders Found.</span></td>
				</tr>
			<?php endif;?>
		</table>
		<?php if($invoice_data['invoice_status'] != 2): ?>
		<h2 class='warning'>Step 2: Select a date and refund type</h2>
		<p>Now you must select whether this is a Store Credit or Cash Back (return of Credit) return.</p>
		<h3>Select Return Date and Refund Type:</h3>
		<?php echo form_open('sales/return_items/' . $invoice_data['invoice_id'], 'name="return_selected_items_form"')?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Return Date:</td>
				<td>
					<input id='return_date_input' name="return_date_input" class='input_field' type="text" value="<?php echo set_value('return_date_input', date('m/d/Y')); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'return_selected_items_form',
						// input name
						'controlname': 'return_date_input',
						'callback': function(str) {
							$('#return_date_input').trigger('keyup');
						}
					});
					</script>
				</td>
			</tr>
			<tr>
				<td class='title'>Refund Type:</td>
				<td>
					<select name='refund_type' class='input_field'>
						<option value='1' >Store Credit</option>
						<option value='2' >Cash/Credit Card</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td>
					<?php echo validation_errors(); ?>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td colspan='3'>
					<input type='hidden' name='invoice_id' value='<?php echo $invoice_data['invoice_id']; ?>' />
					<input type='submit' value='Continue to 3rd Step' />
				</td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<?php endif;?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>