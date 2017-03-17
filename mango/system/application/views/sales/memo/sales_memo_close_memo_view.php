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
	
	<title><?php echo $this->config->item('project_name'); ?> - Memo - Close this Memo</title>
	
	<?php echo snappy_script('calendar_us.js'); ?>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	
	<script type="text/javascript">
		var base_url = '<?php echo base_url(); ?>';
		var json = {"fields" : {}};

		$(document).ready(function () {
			var content = $('.input_field').val();

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
			Memo - Close Memo #<?php echo $memo_data['invoice_id']; ?> for:
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
		<h2 class='warning'>You are about to close this memo.</h2>
		<p class='error'>
			<strong>Please Read!</strong><br />
			This will return all of the items which are not already converted to an invoice or returned.
			Please verify that this is really what you want to do.
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
		</table>
		<h3>The following items will be returned</h3>
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
							<div class='warning'>Item returned.</div>
						<?php elseif($item['invoice_item_status'] == 2):?>
							<div class='warning'>Pending Return</div>
							<div>This will be returned!</div>
						<?php elseif($item['invoice_item_status'] == 3):?>
							<div class='warning'>Memo Closed</div>
						<?php elseif($item['invoice_item_status'] == 4):?>
							<div class='warning'>Item Converted</div>
						<?php elseif($item['invoice_item_status'] == 5):?>
							<div class='warning'>Pending Conversion</div>
							<div>This will be returned!</div>
						<?php else: ?>
							<div>This will be returned</div>
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
								<div class='warning'>Item returned</div>
							<?php elseif($item['item_status'] == 2): ?>
								<div class='warning'>Pending Return</div>
								<div>This will be returned!</div>								
							<?php elseif($item['item_status'] == 3): ?>
								<div class='warning'>Memo Closed</div>
							<?php elseif($item['item_status'] == 4): ?>
								<div class='warning'>Item Converted</div>
							<?php elseif($item['item_status'] == 5):?>								
								<div class='warning'>Pending Conversion</div>
								<br /> 
								<div>This will be returned!</div>
							<?php else:?>
								<div>This will be returned!</div>
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
		<h2 class='warning'>Step 2: Select a Memo Close Date</h2>
		<h3>Select Memo Close Date</h3>
		<?php echo form_open('sales/close_memo/' . $memo_data['invoice_id'], 'name="close_memo_date_form"')?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Memo Close Date:</td>
				<td>
					<input id='memo_close_date_input' name="memo_close_date_input" class='input_field' type="text" value="<?php echo set_value('conversion_date_input', date('m/d/Y')); ?>" />
					<script type="text/javascript">
					A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
					new tcal ({
						// form name
						'formname': 'close_memo_date_form',
						// input name
						'controlname': 'memo_close_date_input',
						'callback': function(str) {
							$('#conversion_date_input').trigger('keyup');
						}
					});
					</script>
					<input name='memo_id' type='hidden' value='<?php echo $memo_data['invoice_id']; ?>' />	
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
					<input type='hidden' name='invoice_id' value='<?php echo $memo_data['invoice_id']; ?>' />
					<input type='submit' value='Close This Memo' />
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