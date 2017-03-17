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
	<?php echo snappy_style('jquery.jeditable.css');?>

	<title><?php echo $this->config->item('project_name'); ?> - Invoices - View <?php echo $invoice_data['invoice_type_text']; ?></title>


	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>
	<?php echo snappy_script('jquery/jquery.jeditable.js'); ?>

	<script type="text/javascript">

	var base_url = <?php echo "'" . base_url() . "'"; ?>;

	var invoice_id = <?php echo $invoice_data['invoice_id']; ?>;
	var buyer_id = <?php echo $invoice_data['buyer_id']; ?>;
	var url = 'sales/jAJAX_updateInvoiceField/';

	$(document).ready(function() {
		$('#payment_link').bind('click', function() {
			var div = $('#invoice_payment_div');
			if(div.is(':hidden')) {
				div.slideDown('slow');
			}
			else {
				div.slideUp('slow');
			}
		});

		$('.textarea_edit').editable(base_url + url, {
			submitdata: {
				invoice_id: invoice_id
			},
			type: 'textarea',
			rows: '5',
			cols: '45',
			cssclass: 'inplace_field',
			onblur: 'submit'
		});

		$('#payment_method').bind('change', function() {
			var value = $(this).val();
			var p_div = $('#store_credit_amount_div');
			if(value == 4) {
				if(p_div.is(':hidden')) {
					var sc = 0;
					$.post(base_url + 'customer/AJAX_get_customer_store_credit',
						{
							customer_id : buyer_id
						},
						function(data) {
							sc = data;
							p_div.html('Credit: $' + sc);
							p_div.show('slow');
					});
				}
				else {
					p_div.hide('slow');
				}
			}
			else if(!p_div.is(':hidden')) {
				p_div.hide('slow');
			}
		});

		$('#pay_total_amount').bind('click', function() {
			var value = $('#payment_method').val();
			if(value == 4) {
				$.post(base_url + 'customer/AJAX_get_customer_store_credit',
					{
						customer_id : buyer_id
					},
					function(data) {
						var total_amount = <?php echo $total_invoice_price - $total_payments; ?>;
						var total_store_credit = data;
						var s_amount = 0;
						$('#store_credit_amount_div').html('Credit: $' + total_store_credit);
						$('#store_credit_amount_div').show('slow');
						if(parseInt(total_store_credit) > parseInt(total_amount)) {
							s_amount = total_amount;
						}
						else {
							s_amount = total_store_credit;
						}
						$('#payment_amount').val(s_amount);
				});
			}
			else {
				$('#payment_amount').val(<?php echo $total_invoice_price - $total_payments; ?>);
			}
		});
	});

	function useRemaingAmount(name) {
		var input = document.getElementById(name);
		input.value = <?php echo $total_invoice_price - $total_payments; ?>;
	}
	</script>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');
?>
	<div id="content">
		<h2>
			Invoices - View <?php echo $invoice_data['invoice_type_text']; ?> #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
				Customer - <?php echo anchor('customer/edit/' . $buyer_data['customer_id'], $buyer_data['first_name'] . ' ' . $buyer_data['last_name']); ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo anchor('vendor/edit/' . $buyer_data['vendor_id'], $buyer_data['name']); ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales', '<< Back to Sales Main'); ?></li>
			<li>|</li>
			<li>
				<?php echo anchor('printer/invoice/' . $invoice_data['invoice_id'], snappy_image('icons/printer.png') . ' Print', 'target="_blank"'); ?>
				||
				<?php echo anchor('printer/invoice/' . $invoice_data['invoice_id'] . '/false', snappy_image('icons/printer.png') . ' Print w/o SC', 'target="_blank", title="Print without displaying Store Credit"'); ?>
			</li>
			<li>|</li>
			<?php if($invoice_data['invoice_type'] == 3): //open memo ?>
				<?php if($invoice_data['invoice_status'] <> 3 && $invoice_data['invoice_status'] <> 4): ?>
					<li><?php echo anchor('sales/close_memo/' . $invoice_data['invoice_id'], snappy_image('icons/layout_delete.png') . ' Close Memo', 'title="returns all items and closes the memo"'); ?></li>
					<li>|</li>
				<?php endif;?>
				<?php if($invoice_data['invoice_status'] != 3): //closed memo ?>
					<li><?php echo anchor('sales/return_selected_memo_items/' . $invoice_data['invoice_id'], snappy_image('icons/package_delete.png') . ' Return Selected Items'); ?></li>
					<li>|</li>
					<li><?php echo anchor('sales/convert_selected_memo_items/' . $invoice_data['invoice_id'], snappy_image('icons/layout_link.png') . ' Convert Items to Invoice'); ?></li>
					<li>|</li>
				<?php endif;?>
			<?php else:?>
				<li><?php echo anchor('sales/return_items/' . $invoice_data['invoice_id'], snappy_image('icons/package_delete.png') . ' Return Items'); ?></li>
				<li>|</li>
				<li><?php echo anchor('sales/create_invoice/customer', 'New Invoice'); ?></li>
				<li>|</li>
				<li><?php echo anchor('sales/create_appraisal/invoice/' . $invoice_data['invoice_id'], 'Appraisal'); ?></li>
				<li>|</li>
			<?php endif;?>

			<?php if($invoice_data['invoice_status'] != 4 &&  $invoice_data['invoice_status'] != 3): ?>
				<li><?php echo anchor('sales/make_invoice_editable/' . $invoice_data['invoice_id'], snappy_image('icons/layout_edit.png') .  ' Make Editable'); ?></li>
			<?php endif;?>

		</ul>
		<table class='form_table'>
			<?php if($invoice_data['invoice_status'] == 2):?>
				<tr>
					<td colspan='4'>
						<h3>
							<span class='error'>This invoice has been returned!</span>
							<span class='normal_text'>[<?php echo anchor('sales/returns/' . $return_data['return_id'], 'View Return Slip'); ?>]</span>
						</h3>
					</td>
				</tr>
			<?php elseif($invoice_data['invoice_status'] == 4 || $invoice_data['memo_id'] != ''): ?>
				<tr>
					<td colspan='4'>
						<h3>

							<?php if($invoice_data['memo_id'] != ''): ?>
								<span class='warning'>This Invoice was once a memo! </span>
								<span class='normal_text'>[<?php echo anchor('sales/invoice/' . $invoice_data['memo_id'], 'View Memo'); ?>]</span>
							<?php else: ?>
								<span class='warning'>This Memo has been converted into an Invoice!</span>
								<span class='normal_text'>[<?php echo anchor('sales/invoice/' . $invoice_data['invoice_memo_id'], 'View Invoice'); ?>]</span>
							<?php endif;?>
						</h3>
					</td>
				</tr>
			<?php elseif($invoice_data['invoice_status'] == 3): //memo closed?>
				<tr>
					<td colspan='4'>
						<h2 class='warning'>This memo has been close!</h2>
					</td>
				</tr>
			<?php endif;?>
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
				<td>
					<?php echo $sales_people[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people[$invoice_data['user_id']]['last_name']  ; ?>
				</td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td>
					<?php
						//0 = closed/read-only
						//memo = 0 = open
						//1 = open/editable
						//2 = returned
						//3 = memo closed
					?>
					<?php echo $invoice_data['invoice_type_text'] . '[' . $invoice_data['invoice_status'] .  ']'; ?>
				</td>
				<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif;?>
			</tr>
			<?php if($invoice_data['buyer_type'] == 3): ?>
				<tr>
					<td class='title'>Internet Sale:</td>
					<td>
						[<?php echo anchor('sales/credit_card/' . $invoice_data['invoice_id'], 'Get Credit Card')?>]
					</td>
				</tr>
			<?php endif;?>
			<?php if($invoice_data['invoice_status'] == 3 || $invoice_data['invoice_status'] == 4): ?>
				<tr>
					<td class='title'>Memo Status:</td>
					<td>Memo Closed on <?php echo date('M d, Y', strtotime($invoice_data['memo_close_date'])); ?></td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='title'>Credit Card:</td>
				<td colspan='3'>[<?php echo anchor('gateway/invoice/' . $invoice_data['invoice_id'], 'Charge Credit Card')?>]</td>
			</tr>
		</table>
		<?php if($invoice_data['invoice_type'] == 3):?>
			<!-- Do not show Payments for Memos -->
		<?php else:?>
			<?php if($invoice_data['invoice_type'] == 0 ): //normal invoice ?>
				<h3>Payments <span class='small_text'>[<a id='payment_link' href='javascript:void(0);' >Add Payment</a>]</span></h3>
				<div id='invoice_payment_div' style='display: none;' >
					<?php echo form_open('sales/add_invoice_payment/' . $invoice_data['invoice_id'], 'name="add_invoice_payment"')?>
					<table class='form_table'>
						<tr>
							<td class='title'>Method: </td>
							<td>
								<select id="payment_method" name="payment_method" onchange="">
									<?php foreach($payment_methods as $method):?>
										<option value="<?php echo $method['id']; ?>" > <?php echo $method['name']; ?></option>
									<?php endforeach;?>
								</select>
								[<a id='pay_total_amount' href='javascript:void(0)'>Pay Total Amount</a>]
								<div id='store_credit_amount_div' style='display: hidden;'></div>
							</td>
						</tr>
						<tr>
							<td class='title'>Amount: </td>
							<td>
								<input type="text" id="payment_amount" name="payment_amount" />
							</td>
						</tr>
						<tr>
							<td class='title'>Date: </td>
							<td>
								<input type="text" name="payment_date" value="<?php echo date("m/d/Y")?>" />
								<?php echo snappy_script('calendar_us.js'); ?>
								<script language="JavaScript">
								A_TCALDEF.imgpath = '<?php echo $this->config->item('js_image_path'); ?>';
								new tcal ({
									// form name
									'formname': 'add_invoice_payment',
									// input name
									'controlname': 'payment_date'
								});
								</script>
							</td>
						</tr>
						<tr>
							<td class='title'></td>
							<td>
								<input type="submit" name="add_payment" value="Add Payment" />
							</td>
						</tr>
					</table>
					<?php echo form_close();?>
				</div>
				<table class='invoice_table'>
					<tr>
						<th>Payment Type</th>
						<th>Date</th>
						<th>Amount</th>
						<th>Options</th>
					</tr>
				<?php if(sizeof($payments) > 0):?>
					<?php foreach($payments as $payment):?>
						<tr>
							<td><?php echo $payment_methods[$payment['method']]['name']; ?></td>
							<td><?php echo date('M d, Y', strtotime($payment['date'])); ?></td>
							<td>$<?php echo number_format($payment['amount'], 2); ?></td>
							<td class='right'>
								<?php echo form_open('sales/remove_invoice_payment/' . $invoice_data['invoice_id'] . '/' . $payment['invoice_payment_id']);?>
									<input class='warning' type='submit' value='Remove Payment' />
								<?php echo form_close(); ?>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else:?>
					<tr>
						<td colspan='4'><span class='warning'>No Payments Found</span></td>
					</tr>
				<?php endif;?>
					<tr>
						<td class='title top_lite' colspan='2'>Total Payments:</td>
						<td class='top_lite' colspan='2'>$<?php echo number_format($total_payments, 2); ?></td>
					</tr>
					<tr>
						<td class='title' colspan='2'>Total Remaining: </td>
						<td class='warning' colspan='2'>$<?php echo number_format($total_invoice_price - $total_payments, 2);?></td>
					</tr>
				</table>
			<?php elseif($invoice_data['invoice_type'] == 1): //layaway invoice ?>
				<?php if($invoice_data['invoice_status'] == 5): //layaway was cancelled ?>
					<h3>This Layaway has been cancelled!</h3>
				<?php else: ?>
					<?php echo $this->load->view('sales/_components/layaway_view'); ?>
				<?php endif;?>
			<?php endif;?>

		<?php endif;?>
		<table class='invoice_table'>
			<tr>
				<th nowrap>Number</th>
				<th>Description</th>
				<th nowrap><?php echo ($invoice_data['invoice_type']  == 3) ? 'Price' : 'Retail Price'; ?></th>
				<th nowrap>Tax</th>
				<th nowrap>Options</th>
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
						<?php if($item['invoice_item_status'] == 1 || $item['invoice_item_status'] == 3):?>
							<span class='warning'>This item has been returned!</span>
						<?php elseif($item['invoice_item_status'] == 4): ?>
							<span class='warning'>This item has been converted to Invoice [<?php echo anchor('sales/invoice/' . $item['new_invoice_id'], 'View Invoice');?>]</span>
						<?php elseif($item['invoice_item_status'] == 2):?>
							<span class='warning'>This item is pending a return.</span> [<?php echo anchor('sales/return_items/' . $invoice_data['invoice_id'], 'Please fix it!'); ?>]
						<?php endif;?>

					</td>
					<td>$<?php echo number_format($item['sale_price'], 2); ?></td>
					<td>$<?php echo number_format($item['sale_tax'], 2); ?></td>
					<td></td>
				</tr>
				<?php endforeach;?>
			<?php else:?>
				<tr>
					<td colspan='4'><span class='warning'>No Inventory Items Found</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td colspan='6' class='top_lite header'>Special Orders</td>
			</tr>
			<?php if(sizeof($special_items) > 0): ?>
				<?php $repairs = array(); ?>
				<?php foreach($special_items as $item):?>
					<?php if($item['item_type'] != 3): //3=repair ?>
						<tr>
							<td colspan='2'>
								<?php echo $item['item_description']; ?>
								<?php if($item['item_status'] == 1 || $item['item_status'] == 3):?>
									<br /><span class='warning'>This item has been returned</span>
								<?php elseif($item['item_status'] == 2):?>
									<br /><span class='warning'>This item is pending a return. </span> [<?php echo anchor('sales/return_items/' . $invoice_data['invoice_id'], 'Please fix it!'); ?>]
								<?php elseif($item['item_status'] == 4):  //converted memo ?>
									<br /><span class='warning'>This item has been converted into an invoice <?php echo anchor('sales/invoice/' . $item['new_invoice_id'], 'View Invoice');?></span>
								<?php endif;?>
							</td>
							<td>$<?php echo number_format($item['item_price'], 2); ?></td>
							<td colspan='2'>$<?php echo number_format($item['item_tax'], 2); ?></td>
						</tr>
					<?php else: ?>
						<?php $repairs[] = $item; ?>
					<?php endif; ?>
				<?php endforeach; ?>
				<?php if(sizeof($repairs) > 0 ): ?>
					<tr>
						<td colspan="5" class="top_lite header" >Special Repairs</td>
					</tr>
					<?php foreach($repairs as $repair): ?>
						<tr>
							<td colspan='2'>[Repair] <?php echo $repair['item_description']; ?></td>
							<td>$<?php echo number_format($repair['item_price'], 2); ?></td>
							<td>$<?php echo number_format($repair['item_tax'], 2); ?></td>
							<td></td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			<?php else:?>
				<tr>
					<td colspan='5'><span class='warning'>No Special Orders Found.</span></td>
				</tr>
			<?php endif;?>
			<tr>
				<td class='top'></td>
				<td class='top title' >Price:</td>
				<td class='top' colspan='3'>$<?php echo number_format($invoice_data['total_price'], 2); ?></td>
			</tr>
			<tr>
				<td></td>
				<td class='title' >Tax:</td>
				<td colspan='3'>$<?php echo number_format($invoice_data['tax'], 2); ?></td>
			</tr>

			<?php if($invoice_data['is_shipped'] == 1):?>
			<tr>
				<td></td>
				<td class='title'>Address:</td>
				<td colspan='3'>
					<?php echo $invoice_data['ship_contact']; ?> <br />
					ph: <?php echo $invoice_data['ship_phone']; ?> <br />
					alt: <?php echo $invoice_data['ship_other_phone']; ?> <br />
					<?php echo $invoice_data['ship_address']; ?> <br />
					<?php if($invoice_data['ship_address2'] != ''): ?>
						<?php echo $invoice_data['ship_address2']; ?> <br />
					<?php endif;?>
					<?php echo $invoice_data['ship_city']; ?>, <?php echo $invoice_data['ship_state']; ?> <?php echo $invoice_data['ship_zip']; ?> <br />
					[<?php echo anchor('sales/add_shipping/' . $invoice_data['invoice_id'], 'Edit Shipping')?>]
				</td>
			</tr>
			<tr>
				<td></td>
				<td class='title'  nowrap>Shipping Method:</td>
				<td colspan='3'><?php echo $invoice_data['ship_method']; ?></td>
			</tr>
			<tr>
				<td ></td>
				<td class='title' nowrap>Shipping Cost:</td>
				<td colspan='3'>$<?php echo $invoice_data['ship_cost']; ?></td>
			</tr>
			<?php endif;?>
			<tr>
				<td ></td>
				<td class='title' nowrap>Total:</td>
				<td colspan='3'>$<?php echo number_format(($total_invoice_price), 2); ?></td>
			</tr>
			<?php if($invoice_data['invoice_type'] == 1): ?>
				<tr>
					<td ></td>
					<td class='title' nowrap>Total Remaining:</td>
					<td colspan='3' class='warning'>$<?php echo number_format($total_invoice_price - $total_layaway_payments, 2); ?></td>

				</tr>
			<?php endif;?>
		</table>
		<h4>Notes:</h4>
		<p>
			<?php echo $invoice_data['notes']; ?>
		</p>
		<h4>Printable Notes:</h4>
		<div>
			<div id='print_notes' class='textarea_edit'><?php echo $invoice_data['print_notes']; ?></div>
		</div>
		<p>Sales and Invoices Section of <?php echo $this->config->item('project_name'); ?></p>

</div>
<?php
	$this->load->view('_global/footer');
?>

</body>
</html>
