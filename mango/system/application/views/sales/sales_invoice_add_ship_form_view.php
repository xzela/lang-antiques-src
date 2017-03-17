<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo base_url();?>favicon.ico" type="image/x-icon" /> 
	<?php echo snappy_style('styles.css'); //autoloaded ?>
	
	<title><?php echo $this->config->item('project_name'); ?> - Invoices - Add Shipping to Invoice</title>
	<?php echo snappy_script('jquery/jquery-1.3.2.js'); ?>	

	<script type="text/javascript">
		var json = {"fields" : {}};
	
		$(document).ready(function () {
			var content = $('.input_field').val();
			$.each($('.input_field'), function(i, val) {
				json.fields[i] = {"name" : this.name, "value" : this.value};
			}); 

			$('#open_ship').bind('click', function(e, data) {
				var div = $('#new_ship_form');
				if(div.is(':hidden')) {
					div.slideDown('slow');
				}
				else {
					div.slideUp('slow');
				}
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
	</style>
</head>
<body>
<?php
	$this->load->view('_global/header');
	$this->load->view('_global/menu');	
?>
	<div id="content">
		<h2>
			Invoices - Add Shipping to Invoice #<?php echo $invoice_data['invoice_id']; ?> for:
			<?php if($invoice_data['buyer_type'] == 1):?>
				Customer - <?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?>
			<?php elseif($invoice_data['buyer_type'] == 2):?>
				Vendor - <?php echo $buyer_data['name']; ?>
			<?php endif; ?>
		</h2>
		<ul id="submenu">
			<li><?php echo anchor('sales/invoice/' . $invoice_data['invoice_id'], '<< Back to Invoice'); ?></li>
			<li>|</li>
		</ul>
		
		<table class='item_information'>
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
				<td><?php echo $sales_people[$invoice_data['user_id']]['first_name'] . ' ' . $sales_people[$invoice_data['user_id']]['last_name']  ; ?></td>
			</tr>
			<tr>
				<td class='title'>Invoice Type:</td>
				<td><?php echo $invoice_type_text[$invoice_data['invoice_type']]; ?></td>
				<?php if($invoice_data['buyer_type'] == 1 || $invoice_data['buyer_type'] == 3):?>
					<td class='title'>Customer Name:</td>
					<td><?php echo $buyer_data['first_name'] . ' ' . $buyer_data['last_name']; ?></td>
				<?php elseif($invoice_data['buyer_type'] == 2):?>
					<td class='title'>Vendor Name:</td>
					<td><?php echo $buyer_data['name']; ?></td>
				<?php endif; ?>				
			</tr>
		</table>
			<?php echo form_open('sales/add_shipping_address/' . $invoice_data['invoice_id'])?>
				<div id='change_message' style='display: none'>You've made changes to this record. They won't be saved until you press the SAVE button. <input type='submit' value='Save Changes' /></div>	
				<table class='form_table'>
					<tr>
						<td class='title'>Ship Contact:</td>
						<td>
							<input type='text' name='ship_contact' class='input_field' value='<?php echo set_value('ship_contact', $buyer_data['ship_contact']); ?>' />
						</td>
					</tr>
					<tr>
						<td class="title">Ship Phone:</td>
						<td>
							<input type='text' name='ship_phone' class='input_field' value='<?php echo set_value('ship_phone', $buyer_data['ship_phone']); ?>' />
						</td>
						<td class="title">Other Phone:</td>
						<td>
							<input type='text' name='ship_other_phone' class='input_field' value='<?php echo set_value('ship_other_phone', $buyer_data['ship_other_phone']); ?>' />
						</td>						
					</tr>						
					<tr>
						<td class="title">Address Line 1:</td>
						<td colspan="3">
							<input type='text' name='ship_address' class='input_field' value='<?php echo set_value('ship_address', $buyer_data['ship_address']); ?>' style='width: 300px;'/>
						</td>
					</tr> 
					<tr>
						<td class="title">Address Line 2:</td>
						<td colspan="3">
							<input type='text' name='ship_address2' class='input_field' value='<?php echo set_value('ship_address2', $buyer_data['ship_address2']); ?>' style='width: 300px;'/>
						</td>
					</tr> 
					<tr>
						<td class="title">City:</td>
						<td>
							<input type='text' name='ship_city' class='input_field' value='<?php echo set_value('ship_city', $buyer_data['ship_city']); ?>' />
						</td>
						<td class="title">State:</td>
						<td>
							<input type='text' name='ship_state' class='input_field' value='<?php echo set_value('ship_state', $buyer_data['ship_state']); ?>' style='width: 25px;' />
							<strong>Zip: </strong><input type='text' name='ship_zip' class='input_field' value='<?php echo set_value('ship_zip', $buyer_data['ship_zip']); ?>' style='width: 75px;' />
						</td>						
					</tr>						
					<tr>
						<td class="title">Country:</td>
						<td colspan='3'>
							<input type='text' name='ship_country' class='input_field' value='<?php echo set_value('ship_country', $buyer_data['ship_country']); ?>' />
						</td>
					</tr>
					<tr>
						<td class='title'></td>
						<td colspan='3'>
							<?php echo validation_errors(); ?>
						</td>
					</tr>
					<tr>
						<td class='title'></td>
						<td colspan='3'>
							<input type='hidden' name='invoice_id' value='<?php echo $invoice_data['invoice_id']; ?>' />
							<input type='hidden' name='buyer_id' value='<?php echo $invoice_data['buyer_id']; ?>' />
							<input type='submit' name='ship_shipping' value='Ship To Shipping Address' />
						</td>
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