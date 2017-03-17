<?php 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Memo - Return Selected Items</title>
	
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
					$(this).html('Keep Pending Item');
					status = 2; //pending return status
				} 
				else {
					$('#msg_' + id).slideUp('slow');
					$(this).html('Return This Item');
					status = 0; //normal item status
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
					$(this).html('Keep Pending Item');
					status = 2; //pending return status
				} 
				else {
					$('#msg_' + id).slideUp('slow');
					$(this).html('Return This Item');
					status = 0; //normal item status
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
			Memo - Return Selected Items on Memo #<?php echo $memo_data['invoice_id']; ?> for:
			<?php if($memo_data['buyer_type'] == 1 || $memo_data['buyer_type'] == 3):?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($memo_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>			 
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $memo_data['invoice_id'], '<< Back to Memo'); ?></li>
			<li>|</li>
		</ul>
		<p>
			Returning Selected Memo Items is a <strong class='warning'>two</strong> step process. 
			<br /> 
			<strong>First Setp:</strong> Select which items you wish to Return. 
			<br /> 
			<strong>Second Step:</strong> Verify which items you want returned and Click 'Return Items' button.
		</p>
		<table class='form_table'>
			<tr>
				<td class='title'>Memo ID:</td>
				<td><?php echo $memo_data['invoice_id']; ?></td>
				<td class='title'>Sales Slip Number:</td>
				<td><?php echo $memo_data['sales_slip_number']; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Date:</td>
				<td><?php echo $memo_data['sale_date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($memo_data['sale_date'])); ?></td>
				<td class='title'>Sales Person:</td>
				<td><?php echo $sales_person['first_name'] . ' ' . $sales_person['last_name']; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td>
					<?php echo $invoice_type_text[$memo_data['invoice_type']]; ?>
				</td>
				<?php if($memo_data['buyer_type'] == 1 || $memo_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($memo_data['buyer_type'] == 2):?>
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
			<?php if(sizeof($memo_items) > 0 ):?>
				<?php foreach($memo_items as $item):?>
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
					</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td nowrap>
						<?php if($item['invoice_item_status'] == 1):?>
							<div class='warning'>Returned.</div>
						<?php elseif($item['invoice_item_status'] == 2):?>
							<div id='msg_<?php echo $item['invoice_item_id'];?>' style='display:display;' class='warning'>Pending Return</div>
							[<a id='<?php echo $item['invoice_item_id'];?>' class='item green' href='javascript:void(0)' >Keep Pending Item</a>]
						<?php elseif($item['invoice_item_status'] == 3):?>
							<div class='warning'>Returned from Memo</div>
						<?php elseif($item['invoice_item_status'] == 4):?>
							<div id='msg_<?php echo $item['invoice_item_id'];?>' class='warning'>Item Converted</div>
						<?php elseif($item['invoice_item_status'] == 5):?>
							<div class='warning'>Pending Return</div>
						<?php else: ?>
							<div id='msg_<?php echo $item['invoice_item_id'];?>' style='display: none;' class='warning'>Pending Return</div>
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
			<?php if(sizeof($memo_special_items) > 0):?>
				<?php foreach($memo_special_items as $item):?>
					<tr>
						<td colspan='2'>
							<?php echo $item['item_description']; ?>
						</td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td nowrap>
							<?php if($item['item_status'] == 1):?>
								<span>This item has been returned</span>
							<?php elseif($item['item_status'] == 2): ?>
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:display;' class='warning'>Pending Return</div>
								[<a id='<?php echo $item['special_item_id'];?>' href='javascript:void(0)' class='special green' >Keep Pending Item</a>]
							<?php elseif($item['item_status'] == 3): ?>
								<div class='warning'>Returned From Memo</div>
							<?php elseif($item['item_status'] == 4): ?>
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:display;' class='warning'>Item Converted</div>
							<?php elseif($item['item_status'] == 5):?>								
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:display;' class='warning'>Pending Conversion</div>
							<?php else:?>
								<div id='msg_<?php echo $item['special_item_id'];?>' style='display:none;' class='warning'>Pending Conversion</div>
								[<a id='<?php echo $item['special_item_id'];?>' href='javascript:void(0)' class='special' >Convert This Item</a>]
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
		<?php if($memo_data['invoice_status'] != 2): ?>
		<h2 class='warning'>Step 2: Select a New Invoice Date</h2>
		<h3>Select New Invoice Date</h3>
		<?php echo form_open('sales/return_selected_memo_items/' . $memo_data['invoice_id'], 'name="convert_selected_items_form"')?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Errors:</td>
				<td>
					<?php echo validation_errors(); ?>
				</td>
			</tr>
			<tr>
				<td class='title'></td>
				<td colspan='3'>
					<input type='hidden' name='memo_id' value='<?php echo $memo_data['invoice_id']; ?>' />
					<input type='submit' value='Return Selected Items' />
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