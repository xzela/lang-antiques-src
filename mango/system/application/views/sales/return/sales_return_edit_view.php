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
	<title><?php echo $this->config->item('project_name'); ?> - Returns - Edit Return # <?php echo $return_data['return_id']; ?></title>
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
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>
			Return - View Return #<?php echo $return_data['return_id']; ?> for:
			<?php if($return_data['buyer_type'] == 1 || $return_data['buyer_type'] == 3):?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($return_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
			<li><?php echo anchor('printer/returns/' . $return_data['return_id'], snappy_image('icons/printer.png') . ' Print', 'target="_blank"'); ?></li>
			<li>|</li>
		</ul>
		<h2 class='warning'><?php echo $header_message; //see controller; ?></h2>
		<p>
			If you want to change the amount of Store Credit or Cash Back please do so here.  Don't forget to add a note!

		</p>
		<?php echo form_open('sales/return_edit/' . $return_data['return_id'], 'name="return_edit_form"'); ?>
		<div id='change_message' style='display: none'>You've made changes to this record. These changes won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>
		<table class='form_table'>
			<tr>
				<td class='title'>Return ID:</td>
				<td><?php echo $return_data['return_id']; ?></td>
				<td class='title'>Orginial Invoice ID:</td>
				<td><?php echo anchor('sales/invoice/' . $return_data['invoice_id'], $return_data['invoice_id']); ?></td>
			</tr>
			<tr>
				<td class='title warning'>Return Date:</td>
				<td>
					<input type='text' id='date' name='date' class='input_field' value='<?php echo set_value('date', $return_data['date'] == '0000-00-00' ? '' : date('m/d/Y', strtotime($return_data['date']))); ?>' />
					<script type="text/javascript">
						A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
						new tcal ({
							// form name
							'formname': 'return_edit_form',
							// input name
							'controlname': 'date',
							'callback': function(str) {
								$('#date').trigger('keyup');
							}
						});
					</script>
				</td>
				<td class='title warning'>Return Amount:</td>
				<td>$<input name='refund' class='input_field' type='text' value='<?php echo set_value('refund', $return_amount); ?>' /></td>
			</tr>
			<tr>
				<td class='title'>Return Type:</td>
				<td colspan='3'>
					<select name='refund_type' class='input_field'>
						<?php if($return_data['refund_type'] == 1): ?>
							<option value='1' selected >Store Credit</option>
							<option value='2' >Cash/Credit Card</option>
						<?php else: ?>
							<option value='1' >Store Credit</option>
							<option value='2' selected >Cash/Credit Card</option>
						<?php endif; ?>
					</select>
					<input type='hidden' name='store_credit_id' value='<?php echo $credit_data['store_credit_id']; ?>'/>
				</td>
			</tr>
			<tr>
				<td class='title warning'>Notes: </td>
				<td colspan='3'>
					<textarea name='note' class='input_field' cols='40' rows='3'><?php echo set_value('note', $return_data['note'])?></textarea>
				</td>
			</tr>
			<tr>
				<td colspan='4'><?php validation_errors(); ?></td>
			</tr>
			<tr>
				<td></td>
				<td colspan='3'>
					<input type='submit' value='Update Return' />
				</td>
			</tr>
		</table>
		<h3>The following items have been used to calculate the return amount.</h3>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap>Retail</th>
				<th nowrap>Tax</th>
			</tr>
			<?php if(sizeof($return_items) > 0 ):?>
				<?php foreach($return_items as $item):?>
				<tr>
					<td>
						<?php echo anchor('inventory/edit/' . $item['item_id'], $item['item_number']); ?>
					</td>
					<td>
						<?php echo $item['item_name'];?>
					</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Inventory Items Found</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='5' class='top_lite header'>Special Orders</td>
			</tr>
			<?php if(sizeof($special_items) > 0):?>
				<?php foreach($special_items as $item):?>
					<tr>
						<td colspan='2'>
							<?php echo $item['item_description']; ?>
							<?php if($item['item_status'] == 1):?>
								<span class='warning large_text'>This item has been returned</span>
							<?php elseif($item['item_status'] == 2):?>
								<span class='yellow'>This item is pending a return. </span> <?php echo anchor('sales/return_items/' . $invoice_data['invoice_id'], 'Please fix it!'); ?>
							<?php endif;?>
						</td>
						<td>$<?php echo number_format($item['item_price'], 2); ?></td>
						<td>$<?php echo number_format($item['item_tax'], 2); ?></td>
					</tr>
				<?php endforeach; ?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Special Orders Found.</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='top'></td>
				<?php if($return_data['refund_type'] == 1 ): //store_credit ?>
					<td class='top title' style='text-align: right;'>Total Store Credit:</td>
				<?php else: //cash return ?>
					<td class='top title' style='text-align: right;'>Total Return:</td>
				<?php endif;?>
				<td class='top' colspan='2'>$<?php echo number_format($return_amount, 2); ?></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>